# from flask import Flask, request, jsonify
# import io
# import torch
# import torchaudio
# import librosa
# import numpy as np
# from mcam import AudioFingerprintModel

# app = Flask(__name__)

# # Initialize model
# device = torch.device('cuda' if torch.cuda.is_available() else 'cpu')
# model = AudioFingerprintModel(fingerprint_dim=64).to(device)
# model.load_state_dict(torch.load('fingerprint_model.pt', map_location=device))
# model.eval()

# @app.route('/fingerprint', methods=['POST'])
# def get_fingerprint():
#     if 'file' not in request.files:
#         return jsonify({'error': 'No file provided'}), 400
    
#     file = request.files['file']
#     if file.filename == '':
#         return jsonify({'error': 'No file selected'}), 400
    
#     try:
#         # Read audio file
#         audio_data = file.read()
#         audio_io = io.BytesIO(audio_data)
        
#         # Load audio using librosa (handles various formats)
#         wav, sr = librosa.load(audio_io, sr=8000, mono=True)
#         wav = torch.from_numpy(wav).float()
        
#         # Compute mel spectrogram
#         mel_transform = torchaudio.transforms.MelSpectrogram(
#             sample_rate=8000, n_fft=1024, hop_length=256, n_mels=256,
#             f_min=300.0, f_max=4000.0, power=2.0
#         )
#         mel_spec = mel_transform(wav.unsqueeze(0))
#         mel_db = 10.0 * torch.log10(torch.clamp(mel_spec, min=1e-10))
#         mel_db = mel_db - mel_db.max()
#         mel_db = torch.clamp(mel_db, min=-80.0)
        
#         # Generate fingerprint
#         with torch.no_grad():
#             fingerprint = model(mel_db.to(device)).cpu().numpy().flatten()
        
#         return jsonify({
#             'success': True,
#             'fingerprint': fingerprint.tolist()
#         })
        
#     except Exception as e:
#         return jsonify({
#             'success': False,
#             'error': str(e)
#         }), 500

# if __name__ == '__main__':
#     app.run(host='0.0.0.0', port=5000) 


from flask import Flask, request, jsonify
import torch
import torchaudio
import os
from werkzeug.utils import secure_filename

# Load your model (adjust based on your architecture)
model = torch.load("fingerprint_model.pt", map_location=torch.device("cpu"))
model.eval()

# Flask app
app = Flask(__name__)
UPLOAD_FOLDER = "uploads"
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

@app.route('/fingerprint', methods=['POST'])
def fingerprint():
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400

    file = request.files['file']
    filename = secure_filename(file.filename)
    filepath = os.path.join(UPLOAD_FOLDER, filename)
    file.save(filepath)

    # Load and preprocess the audio file
    try:
        waveform, sample_rate = torchaudio.load(filepath)
        waveform = preprocess_audio(waveform, sample_rate)

        # Generate fingerprint
        with torch.no_grad():
            fingerprint_vector = model(waveform.unsqueeze(0))  # add batch dim
            fingerprint_json = fingerprint_vector.squeeze().tolist()
        return jsonify({'fingerprint': fingerprint_json})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

def preprocess_audio(waveform, sample_rate, target_rate=8000):
    if sample_rate != target_rate:
        resampler = torchaudio.transforms.Resample(orig_freq=sample_rate, new_freq=target_rate)
        waveform = resampler(waveform)
    if waveform.shape[0] > 1:
        waveform = torch.mean(waveform, dim=0, keepdim=True)  # convert to mono
    return waveform

if __name__ == '__main__':
    app.run(debug=True)