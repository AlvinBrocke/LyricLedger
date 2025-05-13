import mysql.connector
import faiss
import numpy as np
import json
import os
from datetime import datetime

class FingerprintManager:
    def __init__(self, db_config, index_path='fingerprint_index.faiss'):
        """
        Initialize the fingerprint manager with database connection and FAISS index
        
        Args:
            db_config: Dictionary containing MySQL connection parameters
            index_path: Path to save/load the FAISS index
        """
        self.db_config = db_config
        self.index_path = index_path
        self.dimension = 64  # Dimension of fingerprint vectors
        
        # Initialize database connection
        self.conn = mysql.connector.connect(**db_config)
        self.cursor = self.conn.cursor()
        
        # Create necessary tables if they don't exist
        self._create_tables()
        
        # Initialize or load FAISS index
        self._init_faiss_index()
        
    def _create_tables(self):
        """Create necessary database tables if they don't exist"""
        self.cursor.execute("""
            CREATE TABLE IF NOT EXISTS fingerprints (
                id INT AUTO_INCREMENT PRIMARY KEY,
                track_id VARCHAR(255) NOT NULL,
                fingerprint BLOB NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_track (track_id)
            )
        """)
        self.conn.commit()
        
    def _init_faiss_index(self):
        """Initialize or load the FAISS index"""
        if os.path.exists(self.index_path):
            # Load existing index
            self.index = faiss.read_index(self.index_path)
            print(f"Loaded existing FAISS index with {self.index.ntotal} vectors")
        else:
            # Create new index
            self.index = faiss.IndexFlatIP(self.dimension)  # Inner product for cosine similarity
            print("Created new FAISS index")
            
    def _save_faiss_index(self):
        """Save the FAISS index to disk"""
        faiss.write_index(self.index, self.index_path)
        print(f"Saved FAISS index with {self.index.ntotal} vectors")
        
    def add_fingerprint(self, track_id, fingerprint):
        """
        Add a new fingerprint to both MySQL and FAISS
        
        Args:
            track_id: Unique identifier for the track
            fingerprint: List of float values representing the fingerprint
            
        Returns:
            bool: True if successful, False otherwise
        """
        try:
            # Convert fingerprint to numpy array
            fingerprint_array = np.array(fingerprint, dtype=np.float32)
            
            # Check if track already exists
            self.cursor.execute("SELECT id FROM fingerprints WHERE track_id = %s", (track_id,))
            existing = self.cursor.fetchone()
            
            if existing:
                print(f"Track {track_id} already exists in database")
                return False
            
            # Add to MySQL
            self.cursor.execute(
                "INSERT INTO fingerprints (track_id, fingerprint) VALUES (%s, %s)",
                (track_id, json.dumps(fingerprint))
            )
            self.conn.commit()
            
            # Add to FAISS index
            self.index.add(fingerprint_array.reshape(1, -1))
            self._save_faiss_index()
            
            print(f"Successfully added fingerprint for track {track_id}")
            return True
            
        except Exception as e:
            print(f"Error adding fingerprint: {str(e)}")
            self.conn.rollback()
            return False
            
    def find_similar(self, fingerprint, k=5, threshold=0.8):
        """
        Find similar fingerprints using FAISS
        
        Args:
            fingerprint: List of float values representing the query fingerprint
            k: Number of similar fingerprints to return
            threshold: Similarity threshold (0-1)
            
        Returns:
            List of dictionaries containing similar tracks and their similarity scores
        """
        try:
            # Convert fingerprint to numpy array
            fingerprint_array = np.array(fingerprint, dtype=np.float32)
            
            # Search in FAISS index
            distances, indices = self.index.search(fingerprint_array.reshape(1, -1), k)
            
            # Get track information for matches
            results = []
            for i, (distance, idx) in enumerate(zip(distances[0], indices[0])):
                if idx < 0:  # Invalid index
                    continue
                    
                # Get track information from MySQL
                self.cursor.execute(
                    "SELECT track_id FROM fingerprints WHERE id = %s",
                    (int(idx) + 1,)  # FAISS indices are 0-based, MySQL is 1-based
                )
                track = self.cursor.fetchone()
                
                if track and distance >= threshold:
                    results.append({
                        'track_id': track[0],
                        'similarity': float(distance),
                        'rank': i + 1
                    })
            
            return results
            
        except Exception as e:
            print(f"Error finding similar fingerprints: {str(e)}")
            return []
            
    def get_all_fingerprints(self):
        """Get all fingerprints from the database"""
        try:
            self.cursor.execute("SELECT track_id, fingerprint FROM fingerprints")
            return self.cursor.fetchall()
        except Exception as e:
            print(f"Error getting fingerprints: {str(e)}")
            return []
            
    def rebuild_index(self):
        """Rebuild the FAISS index from MySQL data"""
        try:
            # Create new index
            self.index = faiss.IndexFlatIP(self.dimension)
            
            # Get all fingerprints
            fingerprints = self.get_all_fingerprints()
            
            if not fingerprints:
                print("No fingerprints found in database")
                return False
                
            # Convert fingerprints to numpy array
            fingerprint_arrays = []
            for _, fingerprint in fingerprints:
                fingerprint_arrays.append(np.array(json.loads(fingerprint), dtype=np.float32))
            
            fingerprint_matrix = np.vstack(fingerprint_arrays)
            
            # Add to FAISS index
            self.index.add(fingerprint_matrix)
            self._save_faiss_index()
            
            print(f"Successfully rebuilt index with {len(fingerprints)} fingerprints")
            return True
            
        except Exception as e:
            print(f"Error rebuilding index: {str(e)}")
            return False
            
    def close(self):
        """Close database connection"""
        self.cursor.close()
        self.conn.close()

# Example usage
if __name__ == "__main__":
    # Database configuration
    db_config = {
        'host': 'localhost',
        'user': 'your_username',
        'password': 'your_password',
        'database': 'fingerprint_db'
    }
    
    # Initialize manager
    manager = FingerprintManager(db_config)
    
    # Example fingerprint (64-dimensional vector)
    example_fingerprint = [0.1] * 64
    
    # Add fingerprint
    manager.add_fingerprint("track_001", example_fingerprint)
    
    # Find similar fingerprints
    similar = manager.find_similar(example_fingerprint)
    print("Similar tracks:", similar)
    
    # Close connection
    manager.close() 