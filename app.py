from flask import Flask, request, jsonify
import torch
import torchaudio
import librosa
import numpy as np
import os
from werkzeug.utils import secure_filename
import tempfile
from torch import nn
from fingerprint_manager import FingerprintManager

app = Flask(__name__)

# Database configuration
db_config = {
    'host': 'localhost',
    'user': 'your_username',
    'password': 'your_password',
    'database': 'fingerprint_db'
}

# Initialize fingerprint manager
fingerprint_manager = FingerprintManager(db_config)

# Define the model architecture
class AudioFingerprintModel(nn.Module):
    def __init__(self, fingerprint_dim=64):
        super().__init__()
        self.fingerprint_dim = fingerprint_dim
        # Convolutional encoder f(.)
        self.conv1 = nn.Conv2d(1, 16, kernel_size=(8, 4), stride=(4, 2), padding=(2, 1))
        self.conv2 = nn.Conv2d(16, 32, kernel_size=(8, 4), stride=(4, 2), padding=(2, 1))
        self.conv3 = nn.Conv2d(32, 64, kernel_size=(8, 4), stride=(4, 2), padding=(2, 1))
        # Normalization layers
        self.norm1 = nn.GroupNorm(1, 16)
        self.norm2 = nn.GroupNorm(1, 32)
        self.norm3 = nn.GroupNorm(1, 64)
        # Projection head
        self.head_linears1 = nn.ModuleList([nn.Linear(1024 // fingerprint_dim, 32) for _ in range(fingerprint_dim)])
        self.head_linears2 = nn.ModuleList([nn.Linear(32, 1) for _ in range(fingerprint_dim)])

    def forward(self, x):
        x = torch.nn.functional.relu(self.norm1(self.conv1(x)))
        x = torch.nn.functional.relu(self.norm2(self.conv2(x)))
        x = torch.nn.functional.relu(self.norm3(self.conv3(x)))
        x = x.view(x.size(0), -1)
        chunks = torch.split(x, 1024 // self.fingerprint_dim, dim=1)
        out_components = []
        for i in range(self.fingerprint_dim):
            h = torch.nn.functional.elu(self.head_linears1[i](chunks[i]))
            o = self.head_linears2[i](h)
            out_components.append(o)
        z = torch.cat(out_components, dim=1)
        z = torch.nn.functional.normalize(z, p=2, dim=1)
        return z

# Initialize and load the model
model = AudioFingerprintModel(fingerprint_dim=64)
model.load_state_dict(torch.load('fingerprint_model.pt'))
model.eval()

# Initialize mel spectrogram transform
mel_transform = torchaudio.transforms.MelSpectrogram(
    sample_rate=8000,
    n_fft=1024,
    hop_length=256,
    n_mels=256,
    f_min=300.0,
    f_max=4000.0,
    power=2.0
)

def process_audio(audio_path):
    """Process audio file and return fingerprint"""
    try:
        # Load audio
        try:
            wav, sr = torchaudio.load(audio_path)
            wav = wav.mean(dim=0)  # mono
        except Exception:
            audio_np, sr = librosa.load(audio_path, sr=None, mono=True)
            wav = torch.from_numpy(audio_np).float()

        # Resample to 8kHz if needed
        if sr != 8000:
            wav = torchaudio.functional.resample(wav, sr, 8000)

        # Create mel spectrogram
        mel_spec = mel_transform(wav.unsqueeze(0))
        mel_db = 10.0 * torch.log10(torch.clamp(mel_spec, min=1e-10))
        mel_db = mel_db - mel_db.max()
        mel_db = torch.clamp(mel_db, min=-80.0)
        mel_db = mel_db.unsqueeze(0)  # add batch dim

        # Compute embedding
        with torch.no_grad():
            emb = model(mel_db).squeeze(0).numpy().astype('float32')

        return emb.tolist()
    except Exception as e:
        print(f"Error processing audio: {str(e)}")  # Add error logging
        return None

@app.route('/fingerprint', methods=['POST'])
def get_fingerprint():
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400
    
    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No file selected'}), 400

    # Get track_id from form data or generate one from filename
    track_id = request.form.get('track_id', os.path.splitext(file.filename)[0])

    # Create a temporary file to store the uploaded audio
    with tempfile.NamedTemporaryFile(delete=False, suffix='.mp3') as temp_file:
        file.save(temp_file.name)
        temp_path = temp_file.name

    try:
        # Process the audio file
        fingerprint = process_audio(temp_path)
        
        if fingerprint is None:
            return jsonify({'error': 'Failed to process audio file'}), 500

        # Add fingerprint to database
        success = fingerprint_manager.add_fingerprint(track_id, fingerprint)
        
        if not success:
            return jsonify({
                'fingerprint': fingerprint,
                'message': 'Fingerprint generated but not added to database (track may already exist)'
            })

        return jsonify({
            'fingerprint': fingerprint,
            'track_id': track_id,
            'message': 'Successfully generated and stored fingerprint'
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500
    
    finally:
        # Clean up the temporary file
        if os.path.exists(temp_path):
            os.unlink(temp_path)

@app.route('/search', methods=['POST'])
def search_similar():
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400
    
    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No file selected'}), 400

    # Get search parameters
    k = int(request.form.get('k', 5))
    threshold = float(request.form.get('threshold', 0.8))

    # Create a temporary file to store the uploaded audio
    with tempfile.NamedTemporaryFile(delete=False, suffix='.mp3') as temp_file:
        file.save(temp_file.name)
        temp_path = temp_file.name

    try:
        # Process the audio file
        fingerprint = process_audio(temp_path)
        
        if fingerprint is None:
            return jsonify({'error': 'Failed to process audio file'}), 500

        # Search for similar fingerprints
        similar_tracks = fingerprint_manager.find_similar(fingerprint, k=k, threshold=threshold)

        return jsonify({
            'query_fingerprint': fingerprint,
            'similar_tracks': similar_tracks,
            'message': f'Found {len(similar_tracks)} similar tracks'
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500
    
    finally:
        # Clean up the temporary file
        if os.path.exists(temp_path):
            os.unlink(temp_path)

@app.route('/rebuild_index', methods=['POST'])
def rebuild_index():
    """Endpoint to rebuild the FAISS index from MySQL data"""
    try:
        success = fingerprint_manager.rebuild_index()
        if success:
            return jsonify({'message': 'Successfully rebuilt index'})
        else:
            return jsonify({'error': 'Failed to rebuild index'}), 500
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True, port=5000) 