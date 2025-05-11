-- Create test transactions table
CREATE TABLE IF NOT EXISTS test_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create royalty transactions table if it doesn't exist
CREATE TABLE IF NOT EXISTS royalty_transactions (
    id VARCHAR(36) PRIMARY KEY,
    content_id VARCHAR(36) NOT NULL,
    user_id VARCHAR(36) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    transaction_hash VARCHAR(255),
    blockchain_status ENUM('pending', 'confirmed', 'failed') DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
); 