import os
import numpy as np
import torch
import torchaudio
import faiss
import mysql.connector
from datetime import datetime
from mcam import AudioFingerprintModel, AudioAugmentor

class AudioFingerprintService:
    def __init__(self, model_path=None):
        # Initialize the model
        self.device = 'cuda' if torch.cuda.is_available() else 'cpu'
        self.model = AudioFingerprintModel(fingerprint_dim=64).to(self.device)
        if model_path and os.path.exists(model_path):
            self.model.load_state_dict(torch.load(model_path, map_location=self.device))
        self.model.eval()
        
        # Initialize FAISS index
        self.d = 64  # dimension of fingerprint vectors
        self.index = faiss.IndexFlatIP(self.d)  # Inner-product for cosine similarity
        
        # Initialize database connection
        self.db_config = {
            'host': 'localhost',
            'user': 'root',
            'password': '',
            'database': 'music_copyright_db'
        }
        
        # Initialize audio augmentor
        self.augmentor = AudioAugmentor(noise_files=None)
        
        # Load existing fingerprints from database
        self._load_fingerprints()

    def _load_fingerprints(self):
        """Load existing fingerprints from database into FAISS index"""
        try:
            conn = mysql.connector.connect(**self.db_config)
            cursor = conn.cursor()
            
            # Get all fingerprints
            cursor.execute("""
                SELECT f.id, f.fingerprint, c.id as content_id 
                FROM fingerprints f 
                JOIN content c ON f.content_id = c.id
            """)
            
            fingerprints = []
            content_ids = []
            
            for row in cursor.fetchall():
                fingerprint_id, fingerprint_data, content_id = row
                # Convert binary fingerprint data to numpy array
                fingerprint = np.frombuffer(fingerprint_data, dtype=np.float32)
                fingerprints.append(fingerprint)
                content_ids.append(content_id)
            
            if fingerprints:
                fingerprints = np.vstack(fingerprints)
                self.index.add(fingerprints)
                self.content_ids = content_ids
                
            cursor.close()
            conn.close()
            
        except Exception as e:
            print(f"Error loading fingerprints: {str(e)}")

    def process_audio_file(self, audio_path):
        """Process an audio file and generate its fingerprint"""
        try:
            # Load audio
            wav, sr = torchaudio.load(audio_path)
            wav = wav.mean(dim=0)  # convert to mono
            
            # Take first 1s segment for fingerprint
            if wav.shape[0] < sr:
                raise ValueError("Audio file is too short (less than 1 second)")
                
            orig_audio_sr = sr
            orig_seg, _ = self.augmentor.process(wav, orig_start=0.0, aug_start=0.0)
            
            # Compute mel spectrogram
            mel_transform = torchaudio.transforms.MelSpectrogram(
                sample_rate=8000, n_fft=1024, hop_length=256, n_mels=256,
                f_min=300.0, f_max=4000.0, power=2.0
            )
            mel_spec = mel_transform(orig_seg.unsqueeze(0))
            mel_db = 10.0 * torch.log10(torch.clamp(mel_spec, min=1e-10))
            mel_db = mel_db - mel_db.max()
            mel_db = torch.clamp(mel_db, min=-80.0)
            
            # Generate fingerprint
            with torch.no_grad():
                fingerprint = self.model(mel_db).squeeze(0).numpy().astype('float32')
                
            return fingerprint
            
        except Exception as e:
            print(f"Error processing audio file: {str(e)}")
            return None

    def check_similarity(self, fingerprint, threshold=0.8):
        """Check if the fingerprint matches any existing content"""
        try:
            # Search for similar fingerprints
            D, I = self.index.search(fingerprint.reshape(1, -1), k=5)
            
            # Get similarity scores and corresponding content IDs
            similarities = D[0]
            content_ids = [self.content_ids[i] for i in I[0]]
            
            # Check if any similarity is above threshold
            matches = []
            for sim, content_id in zip(similarities, content_ids):
                if sim >= threshold:
                    matches.append({
                        'content_id': content_id,
                        'similarity_score': float(sim)
                    })
            
            return matches
            
        except Exception as e:
            print(f"Error checking similarity: {str(e)}")
            return []

    def store_fingerprint(self, content_id, fingerprint):
        """Store a new fingerprint in the database and FAISS index"""
        try:
            conn = mysql.connector.connect(**self.db_config)
            cursor = conn.cursor()
            
            # Store in database
            cursor.execute("""
                INSERT INTO fingerprints (id, content_id, fingerprint, segment_start, segment_end)
                VALUES (UUID(), %s, %s, %s, %s)
            """, (content_id, fingerprint.tobytes(), 0.0, 1.0))
            
            # Add to FAISS index
            self.index.add(fingerprint.reshape(1, -1))
            self.content_ids.append(content_id)
            
            conn.commit()
            cursor.close()
            conn.close()
            
            return True
            
        except Exception as e:
            print(f"Error storing fingerprint: {str(e)}")
            return False

    def report_violation(self, content_id, detected_content_id, similarity_score):
        """Report a copyright violation"""
        try:
            conn = mysql.connector.connect(**self.db_config)
            cursor = conn.cursor()
            
            # Insert violation record
            cursor.execute("""
                INSERT INTO violations (id, content_id, detected_url, similarity_score, reported_by, status)
                VALUES (UUID(), %s, %s, %s, %s, %s)
            """, (content_id, detected_content_id, similarity_score, 'system', 'pending'))
            
            # Update content status
            cursor.execute("""
                UPDATE content SET status = 'flagged' WHERE id = %s
            """, (content_id,))
            
            conn.commit()
            cursor.close()
            conn.close()
            
            return True
            
        except Exception as e:
            print(f"Error reporting violation: {str(e)}")
            return False

def process_upload(audio_path, content_id):
    """Process an uploaded audio file and check for copyright violations"""
    service = AudioFingerprintService()
    
    # Generate fingerprint
    fingerprint = service.process_audio_file(audio_path)
    if fingerprint is None:
        return False, "Error processing audio file"
    
    # Check for similar content
    matches = service.check_similarity(fingerprint)
    
    if matches:
        # Report violations for each match
        for match in matches:
            service.report_violation(
                content_id=content_id,
                detected_content_id=match['content_id'],
                similarity_score=match['similarity_score']
            )
        return False, "Content flagged for potential copyright violation"
    
    # Store fingerprint if no violations found
    if service.store_fingerprint(content_id, fingerprint):
        return True, "Content processed successfully"
    else:
        return False, "Error storing fingerprint" 