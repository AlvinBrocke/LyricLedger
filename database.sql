-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS music_royalty_db;
USE music_royalty_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(36) PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create content table
CREATE TABLE IF NOT EXISTS content (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    genre VARCHAR(50) NOT NULL,
    audio_path VARCHAR(255) NOT NULL,
    cover_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create plays table
CREATE TABLE IF NOT EXISTS plays (
    id VARCHAR(36) PRIMARY KEY,
    content_id VARCHAR(36) NOT NULL,
    played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE
);

-- Create royalties table
CREATE TABLE IF NOT EXISTS royalties (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    content_id VARCHAR(36) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE
); 