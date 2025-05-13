-- Add download_count column to content table if it doesn't exist
ALTER TABLE content ADD COLUMN IF NOT EXISTS download_count INT DEFAULT 0;

-- Update existing records to have 0 download count if NULL
UPDATE content SET download_count = 0 WHERE download_count IS NULL;

-- Create downloads table for detailed tracking if it doesn't exist
CREATE TABLE IF NOT EXISTS downloads (
    id VARCHAR(36) PRIMARY KEY,
    content_id VARCHAR(36) NOT NULL,
    downloaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE
); 