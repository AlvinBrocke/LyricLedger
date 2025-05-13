# #Working App.py file

# from flask import Flask, request, jsonify
# import torch
# import torchaudio
# import librosa
# import numpy as np
# import os
# from werkzeug.utils import secure_filename
# import tempfile
# from torch import nn

# app = Flask(__name__)

# # Define the model architecture
# class AudioFingerprintModel(nn.Module):
#     def __init__(self, fingerprint_dim=64):
#         super().__init__()
#         self.fingerprint_dim = fingerprint_dim
#         # Convolutional encoder f(.)
#         self.conv1 = nn.Conv2d(1, 16, kernel_size=(8, 4), stride=(4, 2), padding=(2, 1))
#         self.conv2 = nn.Conv2d(16, 32, kernel_size=(8, 4), stride=(4, 2), padding=(2, 1))
#         self.conv3 = nn.Conv2d(32, 64, kernel_size=(8, 4), stride=(4, 2), padding=(2, 1))
#         # Normalization layers
#         self.norm1 = nn.GroupNorm(1, 16)
#         self.norm2 = nn.GroupNorm(1, 32)
#         self.norm3 = nn.GroupNorm(1, 64)
#         # Projection head
#         self.head_linears1 = nn.ModuleList([nn.Linear(1024 // fingerprint_dim, 32) for _ in range(fingerprint_dim)])
#         self.head_linears2 = nn.ModuleList([nn.Linear(32, 1) for _ in range(fingerprint_dim)])

#     def forward(self, x):
#         x = torch.nn.functional.relu(self.norm1(self.conv1(x)))
#         x = torch.nn.functional.relu(self.norm2(self.conv2(x)))
#         x = torch.nn.functional.relu(self.norm3(self.conv3(x)))
#         x = x.view(x.size(0), -1)
#         chunks = torch.split(x, 1024 // self.fingerprint_dim, dim=1)
#         out_components = []
#         for i in range(self.fingerprint_dim):
#             h = torch.nn.functional.elu(self.head_linears1[i](chunks[i]))
#             o = self.head_linears2[i](h)
#             out_components.append(o)
#         z = torch.cat(out_components, dim=1)
#         z = torch.nn.functional.normalize(z, p=2, dim=1)
#         return z

# # Initialize and load the model
# model = AudioFingerprintModel(fingerprint_dim=64)
# model.load_state_dict(torch.load('fingerprint_model.pt'))
# model.eval()

# # Initialize mel spectrogram transform
# mel_transform = torchaudio.transforms.MelSpectrogram(
#     sample_rate=8000,
#     n_fft=1024,
#     hop_length=256,
#     n_mels=256,
#     f_min=300.0,
#     f_max=4000.0,
#     power=2.0
# )

# def process_audio(audio_path):
#     """Process audio file and return fingerprint"""
#     try:
#         # Load audio
#         try:
#             wav, sr = torchaudio.load(audio_path)
#             wav = wav.mean(dim=0)  # mono
#         except Exception:
#             audio_np, sr = librosa.load(audio_path, sr=None, mono=True)
#             wav = torch.from_numpy(audio_np).float()

#         # Resample to 8kHz if needed
#         if sr != 8000:
#             wav = torchaudio.functional.resample(wav, sr, 8000)

#         # Create mel spectrogram
#         mel_spec = mel_transform(wav.unsqueeze(0))
#         mel_db = 10.0 * torch.log10(torch.clamp(mel_spec, min=1e-10))
#         mel_db = mel_db - mel_db.max()
#         mel_db = torch.clamp(mel_db, min=-80.0)
#         mel_db = mel_db.unsqueeze(0)  # add batch dim

#         # Compute embedding
#         with torch.no_grad():
#             emb = model(mel_db).squeeze(0).numpy().astype('float32')

#         return emb.tolist()
#     except Exception as e:
#         print(f"Error processing audio: {str(e)}")  # Add error logging
#         return None
    

# @app.route('/fingerprint', methods=['POST'])
# def get_fingerprint():
#     if 'file' not in request.files:
#         return jsonify({'error': 'No file provided'}), 400
    
#     file = request.files['file']
#     if file.filename == '':
#         return jsonify({'error': 'No file selected'}), 400

#     # Create a temporary file to store the uploaded audio
#     with tempfile.NamedTemporaryFile(delete=False, suffix='.mp3') as temp_file:
#         file.save(temp_file.name)
#         temp_path = temp_file.name

#     try:
#         # Process the audio file
#         fingerprint = process_audio(temp_path)
        
#         if fingerprint is None:
#             return jsonify({'error': 'Failed to process audio file'}), 500

#         return jsonify({
#             'fingerprint': fingerprint,
#             'message': 'Successfully generated fingerprint'
#         })

#     except Exception as e:
#         return jsonify({'error': str(e)}), 500
    
#     finally:
#         # Clean up the temporary file
#         if os.path.exists(temp_path):
#             os.unlink(temp_path)

#Working App.py file

from flask import Flask, request, jsonify
import torch
import torchaudio
import librosa
import numpy as np
import os
from werkzeug.utils import secure_filename
import tempfile
from torch import nn
import re
import faiss
import mysql.connector
from datetime import datetime
import flask_cors


app = Flask(__name__)
cors = flask_cors.CORS(app)

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

def parse_php_credentials(php_file_path):
    """
    Parse PHP credentials file to extract database configuration
    """
    try:
        with open(php_file_path, 'r') as f:
            content = f.read()
            
        # Extract values using regex
        host = re.search(r"define\(\"DB_HOST\", \"(.*?)\"\)", content).group(1)
        user = re.search(r"define\(\"DB_USER\", \"(.*?)\"\)", content).group(1)
        password = re.search(r"define\(\"DB_PASS\", \"(.*?)\"\)", content).group(1)
        database = re.search(r"define\(\"DB_NAME\", \"(.*?)\"\)", content).group(1)
        charset = re.search(r"define\(\"DB_CHARSET\", \"(.*?)\"\)", content).group(1)
        
        return {
            'host': host,
            'user': user,
            'password': password,
            'database': database,
            'charset': charset,
            'unix_socket': '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock'
        }
    except Exception as e:
        print(f"Error parsing PHP credentials file: {str(e)}")
        return None

# Get database configuration from PHP file
DB_CONFIG = parse_php_credentials('../settings/db_cred.php')

if DB_CONFIG is None:
    raise Exception("Failed to load database configuration from PHP file")

class EnhancedFingerprintDatabase:
    def __init__(self, model, segments_per_track=5, dimension=64):
        self.model = model
        self.segments_per_track = segments_per_track
        self.dimension = dimension
        self.index = faiss.IndexFlatIP(dimension)
        self.track_ids = []
        self.segment_map = []
        self.mel_transform = torchaudio.transforms.MelSpectrogram(
            sample_rate=8000, n_fft=1024, hop_length=256, n_mels=256,
            f_min=300.0, f_max=4000.0, power=2.0
        )
        self.db_connection = mysql.connector.connect(**DB_CONFIG)
        self._init_database()

    def _init_database(self):
        """Initialize the MySQL database tables if they don't exist"""
        cursor = self.db_connection.cursor()
        
        # Create fingerprints table
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS audio_fingerprints (
                id INT AUTO_INCREMENT PRIMARY KEY,
                track_id VARCHAR(255) NOT NULL,
                fingerprint_data BLOB NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (track_id)
            )
        """)
        
        self.db_connection.commit()
        cursor.close()

    def add_fingerprint(self, track_id, fingerprint_data):
        """Add a fingerprint to both MySQL and FAISS databases"""
        try:
            # Add to MySQL
            cursor = self.db_connection.cursor()
            cursor.execute(
                "INSERT INTO audio_fingerprints (track_id, fingerprint_data) VALUES (%s, %s)",
                (track_id, np.array(fingerprint_data).tobytes())
            )
            self.db_connection.commit()
            cursor.close()

            # Add to FAISS
            fingerprint_array = np.array(fingerprint_data, dtype=np.float32).reshape(1, -1)
            self.index.add(fingerprint_array)
            self.track_ids.append(track_id)
            self.segment_map.append((track_id, 0, 0))  # (track_id, segment_idx, start_time)

            return True
        except Exception as e:
            print(f"Error adding fingerprint: {str(e)}")
            return False

    def find_similar(self, query_fingerprint, threshold=0.8):
        """Search for similar fingerprints in FAISS index"""
        try:
            # Convert query fingerprint to numpy array
            query_array = np.array(query_fingerprint, dtype=np.float32).reshape(1, -1)
            
            # Search in FAISS index
            distances, indices = self.index.search(query_array, k=10)  # Get top 10 matches
            
            # Filter results by threshold and format
            results = []
            for i, (distance, idx) in enumerate(zip(distances[0], indices[0])):
                if distance >= threshold and idx < len(self.track_ids):
                    track_id = self.track_ids[idx]
                    results.append({
                        'track_id': track_id,
                        'similarity': float(distance),
                        'rank': i + 1
                    })
            
            return results
        except Exception as e:
            print(f"Error finding similar fingerprints: {str(e)}")
            return []

    def rebuild_faiss_index(self):
        """Rebuild FAISS index from MySQL database"""
        try:
            cursor = self.db_connection.cursor()
            cursor.execute("SELECT track_id, fingerprint_data FROM audio_fingerprints")
            rows = cursor.fetchall()
            cursor.close()

            # Clear existing index
            self.index = faiss.IndexFlatIP(self.dimension)
            self.track_ids = []
            self.segment_map = []

            # Add fingerprints to FAISS
            for track_id, fingerprint_data in rows:
                fingerprint_array = np.frombuffer(fingerprint_data, dtype=np.float32).reshape(1, -1)
                self.index.add(fingerprint_array)
                self.track_ids.append(track_id)
                self.segment_map.append((track_id, 0, 0))

            return True
        except Exception as e:
            print(f"Error rebuilding FAISS index: {str(e)}")
            return False

class EnhancedAudioFingerprinter:
    def __init__(self, model, database):
        self.model = model
        self.database = database
        self.segments_per_query = 3
        self.mel_transform = torchaudio.transforms.MelSpectrogram(
            sample_rate=8000, n_fft=1024, hop_length=256, n_mels=256,
            f_min=300.0, f_max=4000.0, power=2.0
        )

    def extract_fingerprint(self, audio_path):
        """Extract fingerprint from audio file"""
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
            mel_spec = self.mel_transform(wav.unsqueeze(0))
            mel_db = 10.0 * torch.log10(torch.clamp(mel_spec, min=1e-10))
            mel_db = mel_db - mel_db.max()
            mel_db = torch.clamp(mel_db, min=-80.0)
            mel_db = mel_db.unsqueeze(0)

            # Compute embedding
            with torch.no_grad():
                emb = self.model(mel_db).squeeze(0).numpy().astype('float32')

            return emb.tolist()
        except Exception as e:
            print(f"Error extracting fingerprint: {str(e)}")
            return None

# Initialize model and database
model = AudioFingerprintModel(fingerprint_dim=64)
model.load_state_dict(torch.load('fingerprint_model.pt'))
model.eval()

# Initialize database and fingerprinter
database = EnhancedFingerprintDatabase(model)
fingerprinter = EnhancedAudioFingerprinter(model, database)

# Rebuild FAISS index from MySQL database
database.rebuild_faiss_index()

@app.route('/fingerprint', methods=['POST'])
def get_fingerprint():
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400
    
    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No file selected'}), 400

    # Get track_id from request if provided
    track_id = request.form.get('track_id', None)
    if not track_id:
        return jsonify({'error': 'track_id is required'}), 400

    # Create a temporary file to store the uploaded audio
    with tempfile.NamedTemporaryFile(delete=False, suffix='.mp3') as temp_file:
        file.save(temp_file.name)
        temp_path = temp_file.name

    try:
        # Extract fingerprint using EnhancedAudioFingerprinter
        fingerprint = fingerprinter.extract_fingerprint(temp_path)
        
        if fingerprint is None:
            return jsonify({'error': 'Failed to process audio file'}), 500

        # First, check for similar tracks
        similar_tracks = database.find_similar(fingerprint, threshold=0.85)
        
        if similar_tracks:
            # If similar tracks found, return them without adding the new fingerprint
            return jsonify({
                'status': 'similar_tracks_found',
                'similar_tracks': similar_tracks,
                'message': 'Similar tracks found in database'
            })
        
        # If no similar tracks found, add the new fingerprint
        success = database.add_fingerprint(track_id, fingerprint)
        
        if not success:
            return jsonify({'error': 'Failed to store fingerprint in database'}), 500

        return jsonify({
            'status': 'new_track_added',
            'track_id': track_id,
            'message': 'No similar tracks found. Successfully added new track to database',
            'fingerprint': fingerprint
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500
    
    finally:
        # Clean up the temporary file
        if os.path.exists(temp_path):
            os.unlink(temp_path)

<<<<<<< Updated upstream
=======


# @app.route('/royalties', methods=['GET'])
# def get_royalties():
#     return jsonify({'message': 'Royalties endpoint'})

>>>>>>> Stashed changes
# @app.route('/search', methods=['POST'])
# def search_similar():
#     if 'file' not in request.files:
#         return jsonify({'error': 'No file provided'}), 400
    
#     file = request.files['file']
#     if file.filename == '':
#         return jsonify({'error': 'No file selected'}), 400

#     # Create a temporary file to store the uploaded audio
#     with tempfile.NamedTemporaryFile(delete=False, suffix='.mp3') as temp_file:
#         file.save(temp_file.name)
#         temp_path = temp_file.name

#     try:
#         # Process the audio file
#         fingerprint = fingerprinter.extract_fingerprint(temp_path)
        
#         if fingerprint is None:
#             return jsonify({'error': 'Failed to process audio file'}), 500

#         # Search for similar fingerprints
#         similar_tracks = database.find_similar(fingerprint)
        
#         return jsonify({
#             'similar_tracks': similar_tracks,
#             'message': 'Successfully searched for similar tracks'
#         })

#     except Exception as e:
#         return jsonify({'error': str(e)}), 500
    
#     finally:
#         # Clean up the temporary file
#         if os.path.exists(temp_path):
#             os.unlink(temp_path)

if __name__ == '__main__':
    app.run(debug=True, port=5000)