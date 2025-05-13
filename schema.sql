-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS fingerprint_db;
USE fingerprint_db;

-- Create the fingerprints table
CREATE TABLE IF NOT EXISTS fingerprints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    track_id VARCHAR(255) NOT NULL,
    fingerprint BLOB NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_track (track_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create a table for track metadata
CREATE TABLE IF NOT EXISTS track_metadata (
    track_id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255),
    artist VARCHAR(255),
    album VARCHAR(255),
    duration FLOAT,
    file_path VARCHAR(512),
    file_size BIGINT,
    file_format VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (track_id) REFERENCES fingerprints(track_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create a table for search history
CREATE TABLE IF NOT EXISTS search_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query_track_id VARCHAR(255),
    matched_track_id VARCHAR(255),
    similarity_score FLOAT,
    search_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (matched_track_id) REFERENCES fingerprints(track_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create indexes for better query performance
CREATE INDEX idx_track_metadata_title ON track_metadata(title);
CREATE INDEX idx_track_metadata_artist ON track_metadata(artist);
CREATE INDEX idx_search_history_timestamp ON search_history(search_timestamp);
CREATE INDEX idx_search_history_similarity ON search_history(similarity_score);

-- Create a view for recent searches
CREATE OR REPLACE VIEW recent_searches AS
SELECT 
    sh.id,
    sh.query_track_id,
    sh.matched_track_id,
    tm.title AS matched_title,
    tm.artist AS matched_artist,
    sh.similarity_score,
    sh.search_timestamp
FROM search_history sh
LEFT JOIN track_metadata tm ON sh.matched_track_id = tm.track_id
ORDER BY sh.search_timestamp DESC;

-- Create a stored procedure for adding a new track with metadata
DELIMITER //
CREATE PROCEDURE add_track_with_metadata(
    IN p_track_id VARCHAR(255),
    IN p_fingerprint BLOB,
    IN p_title VARCHAR(255),
    IN p_artist VARCHAR(255),
    IN p_album VARCHAR(255),
    IN p_duration FLOAT,
    IN p_file_path VARCHAR(512),
    IN p_file_size BIGINT,
    IN p_file_format VARCHAR(10)
)
BEGIN
    -- Start transaction
    START TRANSACTION;
    
    -- Insert fingerprint
    INSERT INTO fingerprints (track_id, fingerprint)
    VALUES (p_track_id, p_fingerprint);
    
    -- Insert metadata
    INSERT INTO track_metadata (
        track_id, title, artist, album, duration,
        file_path, file_size, file_format
    )
    VALUES (
        p_track_id, p_title, p_artist, p_album, p_duration,
        p_file_path, p_file_size, p_file_format
    );
    
    -- Commit transaction
    COMMIT;
END //
DELIMITER ;

-- Create a stored procedure for searching tracks
DELIMITER //
CREATE PROCEDURE search_tracks(
    IN p_query_track_id VARCHAR(255),
    IN p_similarity_threshold FLOAT
)
BEGIN
    -- Log the search
    INSERT INTO search_history (query_track_id, matched_track_id, similarity_score)
    SELECT 
        p_query_track_id,
        f.track_id,
        -- Note: This is a placeholder for the actual similarity score
        -- The actual similarity calculation is done by FAISS
        1.0 as similarity_score
    FROM fingerprints f
    WHERE f.track_id != p_query_track_id;
    
    -- Return recent searches for this query
    SELECT * FROM recent_searches
    WHERE query_track_id = p_query_track_id
    ORDER BY search_timestamp DESC
    LIMIT 10;
END //
DELIMITER ; 