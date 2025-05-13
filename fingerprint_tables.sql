-- Add fingerprint-related tables to music_royalty_db
USE music_royalty_db;

-- Modify the existing fingerprints table to include new fields
ALTER TABLE fingerprints
ADD COLUMN IF NOT EXISTS segment_start FLOAT DEFAULT 0,
ADD COLUMN IF NOT EXISTS segment_end FLOAT DEFAULT 0,
ADD COLUMN IF NOT EXISTS similarity_score FLOAT DEFAULT 0,
ADD COLUMN IF NOT EXISTS search_count INT DEFAULT 0;

-- Create a table for search history
CREATE TABLE IF NOT EXISTS search_history (
    id VARCHAR(36) PRIMARY KEY,
    content_id VARCHAR(36),
    matched_content_id VARCHAR(36),
    similarity_score FLOAT NOT NULL,
    search_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE SET NULL,
    FOREIGN KEY (matched_content_id) REFERENCES content(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create indexes for better query performance
CREATE INDEX IF NOT EXISTS idx_fingerprints_content ON fingerprints(content_id);
CREATE INDEX IF NOT EXISTS idx_fingerprints_segment ON fingerprints(segment_start, segment_end);
CREATE INDEX IF NOT EXISTS idx_search_history_timestamp ON search_history(search_timestamp);
CREATE INDEX IF NOT EXISTS idx_search_history_similarity ON search_history(similarity_score);

-- Create a view for recent searches
CREATE OR REPLACE VIEW recent_searches AS
SELECT 
    sh.id,
    sh.content_id,
    c1.title AS query_title,
    c1.file_path AS query_file_path,
    sh.matched_content_id,
    c2.title AS matched_title,
    c2.file_path AS matched_file_path,
    sh.similarity_score,
    sh.search_timestamp
FROM search_history sh
LEFT JOIN content c1 ON sh.content_id = c1.id
LEFT JOIN content c2 ON sh.matched_content_id = c2.id
ORDER BY sh.search_timestamp DESC;

-- Drop existing procedures if they exist
DROP PROCEDURE IF EXISTS add_fingerprint;
DROP PROCEDURE IF EXISTS search_similar_content;
DROP PROCEDURE IF EXISTS get_content_stats;

-- Create stored procedures with simplified syntax
DELIMITER //

CREATE PROCEDURE add_fingerprint(
    IN p_content_id VARCHAR(36),
    IN p_fingerprint LONGBLOB,
    IN p_segment_start FLOAT,
    IN p_segment_end FLOAT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error adding fingerprint';
    END;

    START TRANSACTION;
    
    INSERT INTO fingerprints (id, content_id, fingerprint, segment_start, segment_end)
    VALUES (UUID(), p_content_id, p_fingerprint, p_segment_start, p_segment_end);
    
    UPDATE content 
    SET content_status = 'processed',
        fingerprint_path = CONCAT('fingerprints/', p_content_id, '.pt')
    WHERE id = p_content_id;
    
    COMMIT;
END //

CREATE PROCEDURE search_similar_content(
    IN p_content_id VARCHAR(36),
    IN p_similarity_threshold FLOAT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error searching similar content';
    END;

    START TRANSACTION;
    
    INSERT INTO search_history (id, content_id, matched_content_id, similarity_score)
    SELECT 
        UUID(),
        p_content_id,
        f.content_id,
        1.0 as similarity_score
    FROM fingerprints f
    WHERE f.content_id != p_content_id;
    
    UPDATE fingerprints 
    SET search_count = search_count + 1
    WHERE content_id = p_content_id;
    
    SELECT * FROM recent_searches
    WHERE content_id = p_content_id
    ORDER BY search_timestamp DESC
    LIMIT 10;
    
    COMMIT;
END //

CREATE PROCEDURE get_content_stats()
BEGIN
    SELECT 
        c.id,
        c.title,
        c.content_status,
        COUNT(f.id) as fingerprint_count,
        COALESCE(SUM(f.search_count), 0) as total_searches,
        MAX(sh.search_timestamp) as last_searched
    FROM content c
    LEFT JOIN fingerprints f ON c.id = f.content_id
    LEFT JOIN search_history sh ON c.id = sh.content_id
    GROUP BY c.id, c.title, c.content_status;
END //

DELIMITER ; 