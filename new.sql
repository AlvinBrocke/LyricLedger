-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `music_royalty_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('artist','admin','user') NOT NULL,
  `wallet_address` varchar(42) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre_name` varchar(50) NOT NULL,
  `genre_coverart` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`genre_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `fingerprint_path` varchar(255) DEFAULT NULL,
  `content_status` enum('pending','processed','active','inactive') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_play_count` int(11) DEFAULT 0,
  `download_count` int(11) DEFAULT 0,
  `current_play_count` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `genre_id` (`genre_id`),
  CONSTRAINT `content_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `content_ibfk_3` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_number` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(36) NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','inactive','closed') NOT NULL DEFAULT 'active',
  `account_type` varchar(50) NOT NULL DEFAULT 'standard',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`account_number`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `albums_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `fingerprints`
--

CREATE TABLE `fingerprints` (
  `id` varchar(36) NOT NULL,
  `content_id` varchar(36) DEFAULT NULL,
  `fingerprint` longblob NOT NULL,
  `segment_start` float NOT NULL,
  `segment_end` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `idx_fingerprints_content` (`content_id`),
  CONSTRAINT `fingerprints_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `playlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `playlist_content`
--

CREATE TABLE `playlist_content` (
  `playlist_id` varchar(36) NOT NULL,
  `content_id` varchar(36) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`playlist_id`,`content_id`),
  KEY `content_id` (`content_id`),
  CONSTRAINT `playlist_content_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `playlist_content_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `royalty_payments`
--

CREATE TABLE `royalty_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(36) NOT NULL,
  `content_id` varchar(36) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `stream_count` int(11) NOT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `content_id` (`content_id`),
  CONSTRAINT `royalty_payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `royalty_payments_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `royalty_transactions`
--

CREATE TABLE `royalty_transactions` (
  `id` varchar(36) NOT NULL,
  `content_id` varchar(36) DEFAULT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `amount` decimal(20,8) NOT NULL,
  `transaction_hash` varchar(66) NOT NULL,
  `blockchain_status` enum('pending','confirmed','failed') NOT NULL DEFAULT 'pending',
  `payment_method` enum('blockchain','traditional') NOT NULL DEFAULT 'blockchain',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `royalty_transactions_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE SET NULL,
  CONSTRAINT `royalty_transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `id` varchar(36) NOT NULL,
  `content_id` varchar(36) DEFAULT NULL,
  `detected_url` varchar(512) NOT NULL,
  `similarity_score` float NOT NULL,
  `reported_by` varchar(36) DEFAULT NULL,
  `status` enum('pending','confirmed','rejected','resolved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `reported_by` (`reported_by`),
  CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE SET NULL,
  CONSTRAINT `violations_ibfk_2` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `audio_fingerprints`
--

CREATE TABLE `audio_fingerprints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `track_id` varchar(255) NOT NULL,
  `fingerprint_data` blob NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `track_id` (`track_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Insert initial data for genres
--

INSERT INTO `genres` (`id`, `genre_name`, `genre_coverart`) VALUES
(1, 'Afrobeat', 'uploads/genre_images/afrobeats.jpg'),
(2, 'Electronic', 'uploads/genre_images/electronic_music.jpg'),
(3, 'Hip-hop', 'uploads/genre_images/hip-hop.jpg'),
(4, 'Jazz', 'uploads/genre_images/jazz.jpg'),
(5, 'Pop', 'uploads/genre_images/pop_cover.jpg'),
(6, 'Rock', 'uploads/genre_images/rock.jpg');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
