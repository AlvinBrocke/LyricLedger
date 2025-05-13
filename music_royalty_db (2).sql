-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 11, 2025 at 10:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

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
  `play_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `user_id`, `genre_id`, `title`, `file_path`, `fingerprint_path`, `content_status`, `created_at`, `play_count`) VALUES
('d9be7796-2e93-11f0-98f4-465c6e2b71b3', '766b42a6-153d-11f0-868e-465c6e2b71b3', 1, 'r', 'uploads/audio/audio_6820e923a32b1.mp3', NULL, 'pending', '2025-05-11 20:14:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `display_albums`
--

CREATE TABLE `display_albums` (
  `id` int(11) NOT NULL,
  `album_name` varchar(255) NOT NULL,
  `release_date` date DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `display_albums`
--

INSERT INTO `display_albums` (`id`, `album_name`, `release_date`, `cover_image`, `created_at`) VALUES
(1, 'Rhythms of Accra', '2023-01-15', 'https://via.placeholder.com/300x300.png?text=Rhythms+of+Accra', '2025-04-28 16:47:50'),
(2, 'Sunset Vibes', '2023-03-22', 'https://via.placeholder.com/300x300.png?text=Sunset+Vibes', '2025-04-28 16:47:50'),
(3, 'Heartbeat Lagos', '2022-11-05', 'https://via.placeholder.com/300x300.png?text=Heartbeat+Lagos', '2025-04-28 16:47:50'),
(4, 'Island Pulse', '2022-06-17', 'https://via.placeholder.com/300x300.png?text=Island+Pulse', '2025-04-28 16:47:50'),
(5, 'Golden Nights', '2021-08-19', 'https://via.placeholder.com/300x300.png?text=Golden+Nights', '2025-04-28 16:47:50'),
(6, 'Royal Groove', '2023-02-14', 'https://via.placeholder.com/300x300.png?text=Royal+Groove', '2025-04-28 16:47:50'),
(7, 'Melody Waters', '2022-09-10', 'https://via.placeholder.com/300x300.png?text=Melody+Waters', '2025-04-28 16:47:50'),
(8, 'Afro Sunrise', '2022-04-28', 'https://via.placeholder.com/300x300.png?text=Afro+Sunrise', '2025-04-28 16:47:50'),
(9, 'Sonic Tribes', '2021-11-15', 'https://via.placeholder.com/300x300.png?text=Sonic+Tribes', '2025-04-28 16:47:50'),
(10, 'Flaming Drums', '2021-06-21', 'https://via.placeholder.com/300x300.png?text=Flaming+Drums', '2025-04-28 16:47:50'),
(11, 'Neon Dreams', '2023-04-05', 'https://via.placeholder.com/300x300.png?text=Neon+Dreams', '2025-04-28 16:47:50'),
(12, 'Skyline Hearts', '2022-12-12', 'https://via.placeholder.com/300x300.png?text=Skyline+Hearts', '2025-04-28 16:47:50'),
(13, 'Pastel Waves', '2022-07-09', 'https://via.placeholder.com/300x300.png?text=Pastel+Waves', '2025-04-28 16:47:50'),
(14, 'Velvet Echo', '2022-03-17', 'https://via.placeholder.com/300x300.png?text=Velvet+Echo', '2025-04-28 16:47:50'),
(15, 'Electric Bloom', '2021-09-03', 'https://via.placeholder.com/300x300.png?text=Electric+Bloom', '2025-04-28 16:47:50'),
(16, 'Cosmic Bloom', '2023-03-15', 'https://via.placeholder.com/300x300.png?text=Cosmic+Bloom', '2025-04-28 16:47:50'),
(17, 'Euphoria Tides', '2022-10-20', 'https://via.placeholder.com/300x300.png?text=Euphoria+Tides', '2025-04-28 16:47:50'),
(18, 'Rose Gold Horizon', '2022-06-01', 'https://via.placeholder.com/300x300.png?text=Rose+Gold+Horizon', '2025-04-28 16:47:50'),
(19, 'Sapphire Skies', '2021-12-22', 'https://via.placeholder.com/300x300.png?text=Sapphire+Skies', '2025-04-28 16:47:50'),
(20, 'Lucid Bloom', '2021-07-30', 'https://via.placeholder.com/300x300.png?text=Lucid+Bloom', '2025-04-28 16:47:50'),
(21, 'Burn the Horizon', '2023-05-20', 'https://via.placeholder.com/300x300.png?text=Burn+the+Horizon', '2025-04-28 16:47:50'),
(22, 'Ashes and Echoes', '2022-10-08', 'https://via.placeholder.com/300x300.png?text=Ashes+and+Echoes', '2025-04-28 16:47:50'),
(23, 'Rebel Anthem', '2022-05-15', 'https://via.placeholder.com/300x300.png?text=Rebel+Anthem', '2025-04-28 16:47:50'),
(24, 'Crimson Storm', '2021-09-25', 'https://via.placeholder.com/300x300.png?text=Crimson+Storm', '2025-04-28 16:47:50'),
(25, 'Empire\'s Fall', '2021-04-11', 'https://via.placeholder.com/300x300.png?text=Empires+Fall', '2025-04-28 16:47:50'),
(26, 'Voltage Breaker', '2023-01-09', 'https://via.placeholder.com/300x300.png?text=Voltage+Breaker', '2025-04-28 16:47:50'),
(27, 'Neon Rush', '2022-08-04', 'https://via.placeholder.com/300x300.png?text=Neon+Rush', '2025-04-28 16:47:50'),
(28, 'Steel Heart', '2022-03-12', 'https://via.placeholder.com/300x300.png?text=Steel+Heart', '2025-04-28 16:47:50'),
(29, 'Sound Revolution', '2021-10-06', 'https://via.placeholder.com/300x300.png?text=Sound+Revolution', '2025-04-28 16:47:50'),
(30, 'Breaking Silence', '2021-05-15', 'https://via.placeholder.com/300x300.png?text=Breaking+Silence', '2025-04-28 16:47:50'),
(31, 'Moonlit Sax', '2023-03-08', 'https://via.placeholder.com/300x300.png?text=Moonlit+Sax', '2025-04-28 16:47:50'),
(32, 'Nightfall Serenade', '2022-11-01', 'https://via.placeholder.com/300x300.png?text=Nightfall+Serenade', '2025-04-28 16:47:50'),
(33, 'Twilight Jazz', '2022-07-15', 'https://via.placeholder.com/300x300.png?text=Twilight+Jazz', '2025-04-28 16:47:50'),
(34, 'Harlem Groove', '2022-02-20', 'https://via.placeholder.com/300x300.png?text=Harlem+Groove', '2025-04-28 16:47:50'),
(35, 'Velvet Sax', '2021-06-12', 'https://via.placeholder.com/300x300.png?text=Velvet+Sax', '2025-04-28 16:47:50'),
(36, 'Blue Mirage', '2023-04-15', 'https://via.placeholder.com/300x300.png?text=Blue+Mirage', '2025-04-28 16:47:50'),
(37, 'Crimson Jazz', '2022-09-17', 'https://via.placeholder.com/300x300.png?text=Crimson+Jazz', '2025-04-28 16:47:50'),
(38, 'Smooth Currents', '2022-05-30', 'https://via.placeholder.com/300x300.png?text=Smooth+Currents', '2025-04-28 16:47:50'),
(39, 'Midnight Soul', '2021-11-22', 'https://via.placeholder.com/300x300.png?text=Midnight+Soul', '2025-04-28 16:47:50'),
(40, 'Golden Breeze', '2021-05-11', 'https://via.placeholder.com/300x300.png?text=Golden+Breeze', '2025-04-28 16:47:50'),
(41, 'Hustle Diaries', '2023-02-25', 'https://via.placeholder.com/300x300.png?text=Hustle+Diaries', '2025-04-28 16:47:50'),
(42, 'Honor & Streets', '2022-10-29', 'https://via.placeholder.com/300x300.png?text=Honor+and+Streets', '2025-04-28 16:47:50'),
(43, 'Paper Routes', '2022-07-14', 'https://via.placeholder.com/300x300.png?text=Paper+Routes', '2025-04-28 16:47:50'),
(44, 'Skyline Hustlers', '2022-02-18', 'https://via.placeholder.com/300x300.png?text=Skyline+Hustlers', '2025-04-28 16:47:50'),
(45, 'City Shadows', '2021-07-01', 'https://via.placeholder.com/300x300.png?text=City+Shadows', '2025-04-28 16:47:50'),
(46, 'Ghetto Verses', '2023-01-14', 'https://via.placeholder.com/300x300.png?text=Ghetto+Verses', '2025-04-28 16:47:50'),
(47, 'Grind Hard', '2022-08-19', 'https://via.placeholder.com/300x300.png?text=Grind+Hard', '2025-04-28 16:47:50'),
(48, 'Cash Flow', '2022-05-10', 'https://via.placeholder.com/300x300.png?text=Cash+Flow', '2025-04-28 16:47:50'),
(49, 'Late Night Hustle', '2021-11-16', 'https://via.placeholder.com/300x300.png?text=Late+Night+Hustle', '2025-04-28 16:47:50'),
(50, 'Dreams to Dollars', '2021-05-27', 'https://via.placeholder.com/300x300.png?text=Dreams+to+Dollars', '2025-04-28 16:47:50'),
(51, 'Synthetic Pulse', '2023-05-02', 'https://via.placeholder.com/300x300.png?text=Synthetic+Pulse', '2025-04-28 16:47:50'),
(52, 'Neon Drifters', '2022-12-19', 'https://via.placeholder.com/300x300.png?text=Neon+Drifters', '2025-04-28 16:47:50'),
(53, 'Digital Hearts', '2022-08-22', 'https://via.placeholder.com/300x300.png?text=Digital+Hearts', '2025-04-28 16:47:50'),
(54, 'Electric Dreams', '2022-04-25', 'https://via.placeholder.com/300x300.png?text=Electric+Dreams', '2025-04-28 16:47:50'),
(55, 'Starborn Frequencies', '2021-08-18', 'https://via.placeholder.com/300x300.png?text=Starborn+Frequencies', '2025-04-28 16:47:50'),
(56, 'Solar Storm', '2023-03-19', 'https://via.placeholder.com/300x300.png?text=Solar+Storm', '2025-04-28 16:47:50'),
(57, 'Hypernova', '2022-09-02', 'https://via.placeholder.com/300x300.png?text=Hypernova', '2025-04-28 16:47:50'),
(58, 'Nova Rise', '2022-05-05', 'https://via.placeholder.com/300x300.png?text=Nova+Rise', '2025-04-28 16:47:50'),
(59, 'Neon Abyss', '2021-12-10', 'https://via.placeholder.com/300x300.png?text=Neon+Abyss', '2025-04-28 16:47:50'),
(60, 'Velocity Waves', '2021-06-07', 'https://via.placeholder.com/300x300.png?text=Velocity+Waves', '2025-04-28 16:47:50');

-- --------------------------------------------------------

--
-- Table structure for table `display_artistes`
--

CREATE TABLE `display_artistes` (
  `id` int(11) NOT NULL,
  `artiste_name` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `display_artistes`
--

INSERT INTO `display_artistes` (`id`, `artiste_name`, `bio`, `created_at`) VALUES
(1, 'Kwame Blaze', 'Blending traditional Ghanaian beats with modern Afrobeat.', '2025-04-28 16:46:12'),
(2, 'Ama Rhythm', 'Queen of Afrobeat melodies.', '2025-04-28 16:46:12'),
(3, 'Kojo Vibe', 'Fusing Afrobeat and street vibes.', '2025-04-28 16:46:12'),
(4, 'Nana Beat', 'Afrobeat artist with jazz influences.', '2025-04-28 16:46:12'),
(5, 'Zion Sound', 'Spiritual Afrobeat storyteller.', '2025-04-28 16:46:12'),
(6, 'Lana Skye', 'Pop sensation singing heartbreak and hope.', '2025-04-28 16:46:12'),
(7, 'Nova Starr', 'Rising pop artist with electrifying vocals.', '2025-04-28 16:46:12'),
(8, 'Ella Bright', 'Melancholic pop songwriter.', '2025-04-28 16:46:12'),
(9, 'Blake Monroe', 'Pop rebel challenging traditions.', '2025-04-28 16:46:12'),
(10, 'Zara Moon', 'Dreamy pop storyteller.', '2025-04-28 16:46:12'),
(11, 'Crimson Tide', 'Heavy guitars and epic vocals.', '2025-04-28 16:46:12'),
(12, 'The Voltage', 'Classic rock revival.', '2025-04-28 16:46:12'),
(13, 'Stone Legacy', 'Rock legends in the making.', '2025-04-28 16:46:12'),
(14, 'Steel Pulse', 'Merging modern rock with old-school rage.', '2025-04-28 16:46:12'),
(15, 'Blaze Theory', 'Rocking stages across the world.', '2025-04-28 16:46:12'),
(16, 'Ella Noir', 'Smooth jazz saxophonist.', '2025-04-28 16:46:12'),
(17, 'Miles Redd', 'Jazz pianist blending tradition with soul.', '2025-04-28 16:46:12'),
(18, 'Sophia Blue', 'Singing bluesy jazz ballads.', '2025-04-28 16:46:12'),
(19, 'The Midnight Trio', 'Jazz ensemble with nighttime vibes.', '2025-04-28 16:46:12'),
(20, 'Harlem Keys', 'Urban jazz bringing old New York alive.', '2025-04-28 16:46:12'),
(21, 'King Verse', 'Storytelling king of hip-hop.', '2025-04-28 16:46:12'),
(22, 'Lil Jinx', 'Urban youth voice in rap.', '2025-04-28 16:46:12'),
(23, 'Tasha Blaze', 'Hip-hop queen mixing soul and flow.', '2025-04-28 16:46:12'),
(24, 'Major Flex', 'Party starter and rapper.', '2025-04-28 16:46:12'),
(25, 'Ice Prophet', 'Chill rapper with deep lyrics.', '2025-04-28 16:46:12'),
(26, 'Nova Pulse', 'Electronic DJ of futuristic sound.', '2025-04-28 16:46:12'),
(27, 'DJ Solaris', 'Spinning cosmic beats.', '2025-04-28 16:46:12'),
(28, 'Aria Vox', 'Melodic trance queen.', '2025-04-28 16:46:12'),
(29, 'Blitzwave', 'Electronic chaos and order.', '2025-04-28 16:46:12'),
(30, 'Echo Nova', 'Smooth and surreal electronic soundscapes.', '2025-04-28 16:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `display_genres`
--

CREATE TABLE `display_genres` (
  `id` int(11) NOT NULL,
  `genre_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_link` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `display_genres`
--

INSERT INTO `display_genres` (`id`, `genre_name`, `description`, `created_at`, `image_link`) VALUES
(1, 'Afrobeat', 'Vibrant African rhythms mixed with funk and jazz', '2025-04-28 16:39:13', 'https://firebasestorage.googleapis.com/v0/b/webtech-test-api.firebasestorage.app/o/genres%2Fafrobeats.jpg?alt=media&token=995f4e1f-c18d-457e-a4c8-a7e766de46f5'),
(2, 'Pop', 'Catchy melodies and vibrant beats that dominate the charts', '2025-04-28 16:39:13', 'https://firebasestorage.googleapis.com/v0/b/webtech-test-api.firebasestorage.app/o/genres%2Fpop%20cpver.jpg?alt=media&token=e6ecbc56-5cab-4a8a-819c-27d5dc14c4b1'),
(3, 'Rock', 'Electric guitars, strong vocals, and rebellious spirit', '2025-04-28 16:39:13', 'https://firebasestorage.googleapis.com/v0/b/webtech-test-api.firebasestorage.app/o/genres%2Frock.jpg?alt=media&token=bd284d1e-fa90-40ea-aa64-c8e2b13c22ea'),
(4, 'Jazz', 'Smooth improvisational music blending instruments and soul', '2025-04-28 16:39:13', 'https://firebasestorage.googleapis.com/v0/b/webtech-test-api.firebasestorage.app/o/genres%2Fjazz.jpg?alt=media&token=6694a089-df86-47a2-b6c5-a50cfaeb0d09'),
(5, 'Hip-hop', 'Urban poetry with rhythmic beats and storytelling', '2025-04-28 16:39:13', 'https://firebasestorage.googleapis.com/v0/b/webtech-test-api.firebasestorage.app/o/genres%2Fhip-hop.jpg?alt=media&token=e71cabfc-7c2b-4e12-afe9-ce500e1356e4'),
(6, 'Electronic', 'Synth-heavy futuristic beats for the dancefloor', '2025-04-28 16:39:13', 'https://firebasestorage.googleapis.com/v0/b/webtech-test-api.firebasestorage.app/o/genres%2Felectronic%20music.jpg?alt=media&token=90a19ecf-e2a7-46b9-9bb4-1bba26c3d9b6');

-- --------------------------------------------------------

--
-- Table structure for table `display_songs`
--

CREATE TABLE `display_songs` (
  `id` int(11) NOT NULL,
  `song_title` varchar(255) NOT NULL,
  `album_id` int(11) DEFAULT NULL,
  `artiste_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `audio_file_path` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `display_songs`
--

INSERT INTO `display_songs` (`id`, `song_title`, `album_id`, `artiste_id`, `genre_id`, `audio_file_path`, `duration`, `created_at`) VALUES
(1, 'Midnight Market', 1, 1, 1, '/music/midnight_market.mp3', 210, '2025-04-28 16:50:48'),
(2, 'Sunrise Dance', 1, 1, 1, '/music/sunrise_dance.mp3', 265, '2025-04-28 16:50:48'),
(3, 'Golden Skies', 1, 1, 1, '/music/golden_skies.mp3', 185, '2025-04-28 16:50:48'),
(4, 'Island Vibe', 1, 1, 1, '/music/island_vibe.mp3', 276, '2025-04-28 16:50:48'),
(5, 'Heartbeat of Accra', 1, 1, 1, '/music/heartbeat_accra.mp3', 245, '2025-04-28 16:50:48'),
(6, 'Twilight Groove', 2, 1, 1, '/music/twilight_groove.mp3', 195, '2025-04-28 16:50:48'),
(7, 'Ocean Winds', 2, 1, 1, '/music/ocean_winds.mp3', 288, '2025-04-28 16:50:48'),
(8, 'Sun Dancers', 2, 1, 1, '/music/sun_dancers.mp3', 203, '2025-04-28 16:50:48'),
(9, 'Flaming Skies', 2, 1, 1, '/music/flaming_skies.mp3', 226, '2025-04-28 16:50:48'),
(10, 'Lagoon Dreams', 2, 1, 1, '/music/lagoon_dreams.mp3', 218, '2025-04-28 16:50:48'),
(11, 'Lagos Pulse', 3, 1, 1, '/music/lagos_pulse.mp3', 192, '2025-04-28 16:50:48'),
(12, 'Nocturnal Beat', 3, 1, 1, '/music/nocturnal_beat.mp3', 207, '2025-04-28 16:50:48'),
(13, 'Moonlight Fiesta', 3, 1, 1, '/music/moonlight_fiesta.mp3', 250, '2025-04-28 16:50:48'),
(14, 'Urban Sunset', 3, 1, 1, '/music/urban_sunset.mp3', 229, '2025-04-28 16:50:48'),
(15, 'Freedom Drums', 3, 1, 1, '/music/freedom_drums.mp3', 265, '2025-04-28 16:50:48'),
(16, 'Savannah Nights', 4, 1, 1, '/music/savannah_nights.mp3', 185, '2025-04-28 16:50:48'),
(17, 'Island Dreams', 4, 1, 1, '/music/island_dreams.mp3', 240, '2025-04-28 16:50:48'),
(18, 'Jungle Pulse', 4, 1, 1, '/music/jungle_pulse.mp3', 202, '2025-04-28 16:50:48'),
(19, 'Golden Dunes', 4, 1, 1, '/music/golden_dunes.mp3', 290, '2025-04-28 16:50:48'),
(20, 'Sahara Winds', 4, 1, 1, '/music/sahara_winds.mp3', 279, '2025-04-28 16:50:48'),
(21, 'Echoes of Africa', 5, 1, 1, '/music/echoes_africa.mp3', 207, '2025-04-28 16:50:48'),
(22, 'Spirit Walk', 5, 1, 1, '/music/spirit_walk.mp3', 230, '2025-04-28 16:50:48'),
(23, 'Sunfire Beats', 5, 1, 1, '/music/sunfire_beats.mp3', 184, '2025-04-28 16:50:48'),
(24, 'Breeze on the Nile', 5, 1, 1, '/music/breeze_nile.mp3', 255, '2025-04-28 16:50:48'),
(25, 'Tribal Anthem', 5, 1, 1, '/music/tribal_anthem.mp3', 265, '2025-04-28 16:50:48'),
(26, 'Royal Bloodlines', 6, 2, 1, '/music/royal_bloodlines.mp3', 212, '2025-04-28 16:51:10'),
(27, 'Dawn over Ghana', 6, 2, 1, '/music/dawn_over_ghana.mp3', 284, '2025-04-28 16:51:10'),
(28, 'Velvet Skies', 6, 2, 1, '/music/velvet_skies.mp3', 188, '2025-04-28 16:51:10'),
(29, 'Harmony Hills', 6, 2, 1, '/music/harmony_hills.mp3', 267, '2025-04-28 16:51:10'),
(30, 'Fiesta Royale', 6, 2, 1, '/music/fiesta_royale.mp3', 232, '2025-04-28 16:51:10'),
(31, 'Aqua Pulse', 7, 2, 1, '/music/aqua_pulse.mp3', 218, '2025-04-28 16:51:10'),
(32, 'Ocean Chant', 7, 2, 1, '/music/ocean_chant.mp3', 299, '2025-04-28 16:51:10'),
(33, 'Tidal Groove', 7, 2, 1, '/music/tidal_groove.mp3', 204, '2025-04-28 16:51:10'),
(34, 'Melody Waters', 7, 2, 1, '/music/melody_waters.mp3', 246, '2025-04-28 16:51:10'),
(35, 'Raindance', 7, 2, 1, '/music/raindance.mp3', 223, '2025-04-28 16:51:10'),
(36, 'Sun Rays', 8, 2, 1, '/music/sun_rays.mp3', 195, '2025-04-28 16:51:10'),
(37, 'Morning Serenade', 8, 2, 1, '/music/morning_serenade.mp3', 220, '2025-04-28 16:51:10'),
(38, 'Dawn Horizon', 8, 2, 1, '/music/dawn_horizon.mp3', 207, '2025-04-28 16:51:10'),
(39, 'Soul Awakening', 8, 2, 1, '/music/soul_awakening.mp3', 250, '2025-04-28 16:51:10'),
(40, 'Bright Skies', 8, 2, 1, '/music/bright_skies.mp3', 280, '2025-04-28 16:51:10'),
(41, 'Spirit Drums', 9, 2, 1, '/music/spirit_drums.mp3', 199, '2025-04-28 16:51:10'),
(42, 'Warrior Beat', 9, 2, 1, '/music/warrior_beat.mp3', 225, '2025-04-28 16:51:10'),
(43, 'Savannah Dance', 9, 2, 1, '/music/savannah_dance.mp3', 260, '2025-04-28 16:51:10'),
(44, 'Tribal Fusion', 9, 2, 1, '/music/tribal_fusion.mp3', 239, '2025-04-28 16:51:10'),
(45, 'Legacy Grooves', 9, 2, 1, '/music/legacy_grooves.mp3', 186, '2025-04-28 16:51:10'),
(46, 'Ignite the Sky', 10, 2, 1, '/music/ignite_the_sky.mp3', 215, '2025-04-28 16:51:10'),
(47, 'Flame Beats', 10, 2, 1, '/music/flame_beats.mp3', 278, '2025-04-28 16:51:10'),
(48, 'Rising Phoenix', 10, 2, 1, '/music/rising_phoenix.mp3', 201, '2025-04-28 16:51:10'),
(49, 'Bongo Nights', 10, 2, 1, '/music/bongo_nights.mp3', 258, '2025-04-28 16:51:10'),
(50, 'Jungle Fiesta', 10, 2, 1, '/music/jungle_fiesta.mp3', 240, '2025-04-28 16:51:10'),
(51, 'Street Drummer', 11, 3, 1, '/music/street_drummer.mp3', 223, '2025-04-28 16:51:36'),
(52, 'Concrete Jungle', 11, 3, 1, '/music/concrete_jungle.mp3', 270, '2025-04-28 16:51:36'),
(53, 'City Nights', 11, 3, 1, '/music/city_nights.mp3', 185, '2025-04-28 16:51:36'),
(54, 'Neon Lagos', 11, 3, 1, '/music/neon_lagos.mp3', 261, '2025-04-28 16:51:36'),
(55, 'Vibes Avenue', 11, 3, 1, '/music/vibes_avenue.mp3', 249, '2025-04-28 16:51:36'),
(56, 'Golden Sands', 12, 3, 1, '/music/golden_sands.mp3', 197, '2025-04-28 16:51:36'),
(57, 'Sundown Beats', 12, 3, 1, '/music/sundown_beats.mp3', 288, '2025-04-28 16:51:36'),
(58, 'Tropic Heat', 12, 3, 1, '/music/tropic_heat.mp3', 202, '2025-04-28 16:51:36'),
(59, 'Bassline Shuffle', 12, 3, 1, '/music/bassline_shuffle.mp3', 230, '2025-04-28 16:51:36'),
(60, 'Afterglow Party', 12, 3, 1, '/music/afterglow_party.mp3', 215, '2025-04-28 16:51:36'),
(61, 'Midtown Groove', 13, 3, 1, '/music/midtown_groove.mp3', 189, '2025-04-28 16:51:36'),
(62, 'City Lights', 13, 3, 1, '/music/city_lights.mp3', 221, '2025-04-28 16:51:36'),
(63, 'Skyhigh Flow', 13, 3, 1, '/music/skyhigh_flow.mp3', 205, '2025-04-28 16:51:36'),
(64, 'Concrete Samba', 13, 3, 1, '/music/concrete_samba.mp3', 248, '2025-04-28 16:51:36'),
(65, 'Summer Bounce', 13, 3, 1, '/music/summer_bounce.mp3', 276, '2025-04-28 16:51:36'),
(66, 'Heatwave Nights', 14, 3, 1, '/music/heatwave_nights.mp3', 196, '2025-04-28 16:51:36'),
(67, 'Breeze Avenue', 14, 3, 1, '/music/breeze_avenue.mp3', 236, '2025-04-28 16:51:36'),
(68, 'Underground Pulse', 14, 3, 1, '/music/underground_pulse.mp3', 261, '2025-04-28 16:51:36'),
(69, 'Metro Jam', 14, 3, 1, '/music/metro_jam.mp3', 214, '2025-04-28 16:51:36'),
(70, 'Skyline Drums', 14, 3, 1, '/music/skyline_drums.mp3', 277, '2025-04-28 16:51:36'),
(71, 'Street Lights', 15, 3, 1, '/music/street_lights.mp3', 209, '2025-04-28 16:51:36'),
(72, 'Rhythm Railways', 15, 3, 1, '/music/rhythm_railways.mp3', 239, '2025-04-28 16:51:36'),
(73, 'Dawn Express', 15, 3, 1, '/music/dawn_express.mp3', 203, '2025-04-28 16:51:36'),
(74, 'Fast Lane Fiesta', 15, 3, 1, '/music/fast_lane_fiesta.mp3', 266, '2025-04-28 16:51:36'),
(75, 'Night Drive Vibe', 15, 3, 1, '/music/night_drive_vibe.mp3', 242, '2025-04-28 16:51:36'),
(76, 'Jazz Drums', 16, 4, 1, '/music/jazz_drums.mp3', 215, '2025-04-28 16:52:00'),
(77, 'Groove Avenue', 16, 4, 1, '/music/groove_avenue.mp3', 263, '2025-04-28 16:52:00'),
(78, 'Echoes of Gold', 16, 4, 1, '/music/echoes_of_gold.mp3', 194, '2025-04-28 16:52:00'),
(79, 'Tropical Keys', 16, 4, 1, '/music/tropical_keys.mp3', 277, '2025-04-28 16:52:00'),
(80, 'Sunset Brass', 16, 4, 1, '/music/sunset_brass.mp3', 232, '2025-04-28 16:52:00'),
(81, 'Golden Horizon', 17, 4, 1, '/music/golden_horizon.mp3', 221, '2025-04-28 16:52:00'),
(82, 'Mellow Pulse', 17, 4, 1, '/music/mellow_pulse.mp3', 282, '2025-04-28 16:52:00'),
(83, 'Palm Wine Beats', 17, 4, 1, '/music/palm_wine_beats.mp3', 205, '2025-04-28 16:52:00'),
(84, 'Sunkissed Lounge', 17, 4, 1, '/music/sunkissed_lounge.mp3', 238, '2025-04-28 16:52:00'),
(85, 'City Soul', 17, 4, 1, '/music/city_soul.mp3', 248, '2025-04-28 16:52:00'),
(86, 'Afternoon Breeze', 18, 4, 1, '/music/afternoon_breeze.mp3', 192, '2025-04-28 16:52:00'),
(87, 'Jazz Lagoon', 18, 4, 1, '/music/jazz_lagoon.mp3', 229, '2025-04-28 16:52:00'),
(88, 'Nostalgia Nights', 18, 4, 1, '/music/nostalgia_nights.mp3', 203, '2025-04-28 16:52:00'),
(89, 'Old Skool Vibe', 18, 4, 1, '/music/old_skool_vibe.mp3', 260, '2025-04-28 16:52:00'),
(90, 'Sax On Fire', 18, 4, 1, '/music/sax_on_fire.mp3', 284, '2025-04-28 16:52:00'),
(91, 'Festival Lights', 19, 4, 1, '/music/festival_lights.mp3', 198, '2025-04-28 16:52:00'),
(92, 'Savannah Jam', 19, 4, 1, '/music/savannah_jam.mp3', 244, '2025-04-28 16:52:00'),
(93, 'Electric Sunset', 19, 4, 1, '/music/electric_sunset.mp3', 255, '2025-04-28 16:52:00'),
(94, 'City Sparks', 19, 4, 1, '/music/city_sparks.mp3', 218, '2025-04-28 16:52:00'),
(95, 'Moonrise Beats', 19, 4, 1, '/music/moonrise_beats.mp3', 270, '2025-04-28 16:52:00'),
(96, 'Freedom Pulse', 20, 4, 1, '/music/freedom_pulse.mp3', 210, '2025-04-28 16:52:00'),
(97, 'Golden Echo', 20, 4, 1, '/music/golden_echo.mp3', 258, '2025-04-28 16:52:00'),
(98, 'Urban Carnival', 20, 4, 1, '/music/urban_carnival.mp3', 199, '2025-04-28 16:52:00'),
(99, 'Backyard Vibe', 20, 4, 1, '/music/backyard_vibe.mp3', 245, '2025-04-28 16:52:00'),
(100, 'Sunset Samba', 20, 4, 1, '/music/sunset_samba.mp3', 287, '2025-04-28 16:52:00'),
(101, 'Roots Awakening', 21, 5, 1, '/music/roots_awakening.mp3', 222, '2025-04-28 16:52:18'),
(102, 'Mystic Winds', 21, 5, 1, '/music/mystic_winds.mp3', 269, '2025-04-28 16:52:18'),
(103, 'Heritage Beat', 21, 5, 1, '/music/heritage_beat.mp3', 189, '2025-04-28 16:52:18'),
(104, 'Lionheart Anthem', 21, 5, 1, '/music/lionheart_anthem.mp3', 253, '2025-04-28 16:52:18'),
(105, 'Spiritual Groove', 21, 5, 1, '/music/spiritual_groove.mp3', 246, '2025-04-28 16:52:18'),
(106, 'Tribal Sands', 22, 5, 1, '/music/tribal_sands.mp3', 194, '2025-04-28 16:52:18'),
(107, 'Sacred Fire', 22, 5, 1, '/music/sacred_fire.mp3', 288, '2025-04-28 16:52:18'),
(108, 'Jungle Call', 22, 5, 1, '/music/jungle_call.mp3', 207, '2025-04-28 16:52:18'),
(109, 'Echo Chamber', 22, 5, 1, '/music/echo_chamber.mp3', 229, '2025-04-28 16:52:18'),
(110, 'River Spirits', 22, 5, 1, '/music/river_spirits.mp3', 216, '2025-04-28 16:52:18'),
(111, 'Ancient Voices', 23, 5, 1, '/music/ancient_voices.mp3', 186, '2025-04-28 16:52:18'),
(112, 'Moonlight Chant', 23, 5, 1, '/music/moonlight_chant.mp3', 242, '2025-04-28 16:52:18'),
(113, 'Sacred Grove', 23, 5, 1, '/music/sacred_grove.mp3', 205, '2025-04-28 16:52:18'),
(114, 'Echoes of the Land', 23, 5, 1, '/music/echoes_of_the_land.mp3', 261, '2025-04-28 16:52:18'),
(115, 'Mountain Drums', 23, 5, 1, '/music/mountain_drums.mp3', 280, '2025-04-28 16:52:18'),
(116, 'Winds of Eden', 24, 5, 1, '/music/winds_of_eden.mp3', 197, '2025-04-28 16:52:18'),
(117, 'Savannah Ritual', 24, 5, 1, '/music/savannah_ritual.mp3', 234, '2025-04-28 16:52:18'),
(118, 'Temple Spirits', 24, 5, 1, '/music/temple_spirits.mp3', 259, '2025-04-28 16:52:18'),
(119, 'River Echoes', 24, 5, 1, '/music/river_echoes.mp3', 223, '2025-04-28 16:52:18'),
(120, 'Sacred Pulse', 24, 5, 1, '/music/sacred_pulse.mp3', 273, '2025-04-28 16:52:18'),
(121, 'Soul Roots', 25, 5, 1, '/music/soul_roots.mp3', 211, '2025-04-28 16:52:18'),
(122, 'Sunrise Anthem', 25, 5, 1, '/music/sunrise_anthem.mp3', 252, '2025-04-28 16:52:18'),
(123, 'Golden Path', 25, 5, 1, '/music/golden_path.mp3', 202, '2025-04-28 16:52:18'),
(124, 'Mystic Drums', 25, 5, 1, '/music/mystic_drums.mp3', 265, '2025-04-28 16:52:18'),
(125, 'Rising Earth', 25, 5, 1, '/music/rising_earth.mp3', 237, '2025-04-28 16:52:18'),
(126, 'Heartbreak Highway', 26, 6, 2, '/music/heartbreak_highway.mp3', 216, '2025-04-28 16:53:09'),
(127, 'Neon Dreams', 26, 6, 2, '/music/neon_dreams.mp3', 267, '2025-04-28 16:53:09'),
(128, 'Velvet Skies', 26, 6, 2, '/music/velvet_skies.mp3', 194, '2025-04-28 16:53:09'),
(129, 'Late Night Confessions', 26, 6, 2, '/music/late_night_confessions.mp3', 275, '2025-04-28 16:53:09'),
(130, 'Skyline Love', 26, 6, 2, '/music/skyline_love.mp3', 242, '2025-04-28 16:53:09'),
(131, 'Electric Romance', 27, 6, 2, '/music/electric_romance.mp3', 222, '2025-04-28 16:53:09'),
(132, 'Midnight Echoes', 27, 6, 2, '/music/midnight_echoes.mp3', 280, '2025-04-28 16:53:09'),
(133, 'Pastel Heart', 27, 6, 2, '/music/pastel_heart.mp3', 208, '2025-04-28 16:53:09'),
(134, 'Fading Neon', 27, 6, 2, '/music/fading_neon.mp3', 230, '2025-04-28 16:53:09'),
(135, 'Sunrise Serenade', 27, 6, 2, '/music/sunrise_serenade.mp3', 218, '2025-04-28 16:53:09'),
(136, 'Lucid Lights', 28, 6, 2, '/music/lucid_lights.mp3', 190, '2025-04-28 16:53:09'),
(137, 'Dancing in Silence', 28, 6, 2, '/music/dancing_in_silence.mp3', 223, '2025-04-28 16:53:09'),
(138, 'Broken Mirrors', 28, 6, 2, '/music/broken_mirrors.mp3', 205, '2025-04-28 16:53:09'),
(139, 'Sapphire Skies', 28, 6, 2, '/music/sapphire_skies.mp3', 259, '2025-04-28 16:53:09'),
(140, 'Midtown Glow', 28, 6, 2, '/music/midtown_glow.mp3', 279, '2025-04-28 16:53:09'),
(141, 'Paper Planes', 29, 6, 2, '/music/paper_planes.mp3', 197, '2025-04-28 16:53:09'),
(142, 'Urban Fairytales', 29, 6, 2, '/music/urban_fairytales.mp3', 243, '2025-04-28 16:53:09'),
(143, 'Starlight Avenue', 29, 6, 2, '/music/starlight_avenue.mp3', 265, '2025-04-28 16:53:09'),
(144, 'City of Dreams', 29, 6, 2, '/music/city_of_dreams.mp3', 219, '2025-04-28 16:53:09'),
(145, 'Fluorescent Love', 29, 6, 2, '/music/fluorescent_love.mp3', 276, '2025-04-28 16:53:09'),
(146, 'Skybound Heart', 30, 6, 2, '/music/skybound_heart.mp3', 212, '2025-04-28 16:53:09'),
(147, 'Silver Linings', 30, 6, 2, '/music/silver_linings.mp3', 258, '2025-04-28 16:53:09'),
(148, 'Neon Lovers', 30, 6, 2, '/music/neon_lovers.mp3', 200, '2025-04-28 16:53:09'),
(149, 'Dreamer\'s Skyline', 30, 6, 2, '/music/dreamers_skyline.mp3', 264, '2025-04-28 16:53:09'),
(150, 'Electric Tears', 30, 6, 2, '/music/electric_tears.mp3', 239, '2025-04-28 16:53:09'),
(151, 'Supernova Love', 31, 7, 2, '/music/supernova_love.mp3', 217, '2025-04-28 16:53:49'),
(152, 'Galactic Dreams', 31, 7, 2, '/music/galactic_dreams.mp3', 270, '2025-04-28 16:53:49'),
(153, 'Eclipse Heart', 31, 7, 2, '/music/eclipse_heart.mp3', 193, '2025-04-28 16:53:49'),
(154, 'Venus Skyline', 31, 7, 2, '/music/venus_skyline.mp3', 262, '2025-04-28 16:53:49'),
(155, 'Cosmic Bloom', 31, 7, 2, '/music/cosmic_bloom.mp3', 249, '2025-04-28 16:53:49'),
(156, 'Moonlight Escape', 32, 7, 2, '/music/moonlight_escape.mp3', 200, '2025-04-28 16:53:49'),
(157, 'Astro Vibes', 32, 7, 2, '/music/astro_vibes.mp3', 284, '2025-04-28 16:53:49'),
(158, 'Neon Orbit', 32, 7, 2, '/music/neon_orbit.mp3', 207, '2025-04-28 16:53:49'),
(159, 'Pluto\'s Dance', 32, 7, 2, '/music/plutos_dance.mp3', 233, '2025-04-28 16:53:49'),
(160, 'Gravity Groove', 32, 7, 2, '/music/gravity_groove.mp3', 218, '2025-04-28 16:53:49'),
(161, 'Stellar Serenade', 33, 7, 2, '/music/stellar_serenade.mp3', 194, '2025-04-28 16:53:49'),
(162, 'Meteor Showers', 33, 7, 2, '/music/meteor_showers.mp3', 228, '2025-04-28 16:53:49'),
(163, 'Beyond Horizons', 33, 7, 2, '/music/beyond_horizons.mp3', 204, '2025-04-28 16:53:49'),
(164, 'Neon Universe', 33, 7, 2, '/music/neon_universe.mp3', 260, '2025-04-28 16:53:49'),
(165, 'Astronaut Heart', 33, 7, 2, '/music/astronaut_heart.mp3', 277, '2025-04-28 16:53:49'),
(166, 'Infinite Skies', 34, 7, 2, '/music/infinite_skies.mp3', 195, '2025-04-28 16:53:49'),
(167, 'Celestial Pulse', 34, 7, 2, '/music/celestial_pulse.mp3', 236, '2025-04-28 16:53:49'),
(168, 'Milky Way Groove', 34, 7, 2, '/music/milky_way_groove.mp3', 262, '2025-04-28 16:53:49'),
(169, 'Stardust Affair', 34, 7, 2, '/music/stardust_affair.mp3', 214, '2025-04-28 16:53:49'),
(170, 'Lunar Glow', 34, 7, 2, '/music/lunar_glow.mp3', 278, '2025-04-28 16:53:49'),
(171, 'Solar Winds', 35, 7, 2, '/music/solar_winds.mp3', 211, '2025-04-28 16:53:49'),
(172, 'Zero Gravity', 35, 7, 2, '/music/zero_gravity.mp3', 253, '2025-04-28 16:53:49'),
(173, 'Astro Love', 35, 7, 2, '/music/astro_love.mp3', 203, '2025-04-28 16:53:49'),
(174, 'Orbiting Hearts', 35, 7, 2, '/music/orbiting_hearts.mp3', 266, '2025-04-28 16:53:49'),
(175, 'Crashing Stars', 35, 7, 2, '/music/crashing_stars.mp3', 238, '2025-04-28 16:53:49'),
(176, 'Broken Wings', 36, 8, 2, '/music/broken_wings.mp3', 215, '2025-04-28 16:55:04'),
(177, 'Fading Echoes', 36, 8, 2, '/music/fading_echoes.mp3', 263, '2025-04-28 16:55:04'),
(178, 'Lost in Silence', 36, 8, 2, '/music/lost_in_silence.mp3', 194, '2025-04-28 16:55:04'),
(179, 'Empty Streets', 36, 8, 2, '/music/empty_streets.mp3', 278, '2025-04-28 16:55:04'),
(180, 'Whispered Skies', 36, 8, 2, '/music/whispered_skies.mp3', 231, '2025-04-28 16:55:04'),
(181, 'Mirror Heart', 37, 8, 2, '/music/mirror_heart.mp3', 218, '2025-04-28 16:55:04'),
(182, 'Crimson Haze', 37, 8, 2, '/music/crimson_haze.mp3', 283, '2025-04-28 16:55:04'),
(183, 'Frosted Dreams', 37, 8, 2, '/music/frosted_dreams.mp3', 205, '2025-04-28 16:55:04'),
(184, 'Twilight Memories', 37, 8, 2, '/music/twilight_memories.mp3', 239, '2025-04-28 16:55:04'),
(185, 'Snowbound', 37, 8, 2, '/music/snowbound.mp3', 226, '2025-04-28 16:55:04'),
(186, 'Hollow Stars', 38, 8, 2, '/music/hollow_stars.mp3', 193, '2025-04-28 16:55:04'),
(187, 'Frozen Tides', 38, 8, 2, '/music/frozen_tides.mp3', 220, '2025-04-28 16:55:04'),
(188, 'Quiet Horizons', 38, 8, 2, '/music/quiet_horizons.mp3', 204, '2025-04-28 16:55:04'),
(189, 'Dreamless Nights', 38, 8, 2, '/music/dreamless_nights.mp3', 260, '2025-04-28 16:55:04'),
(190, 'Distant Lights', 38, 8, 2, '/music/distant_lights.mp3', 277, '2025-04-28 16:55:04'),
(191, 'Midnight Mirage', 39, 8, 2, '/music/midnight_mirage.mp3', 197, '2025-04-28 16:55:04'),
(192, 'Pale Sun', 39, 8, 2, '/music/pale_sun.mp3', 236, '2025-04-28 16:55:04'),
(193, 'Glass Gardens', 39, 8, 2, '/music/glass_gardens.mp3', 261, '2025-04-28 16:55:04'),
(194, 'Shattered Hearts', 39, 8, 2, '/music/shattered_hearts.mp3', 213, '2025-04-28 16:55:04'),
(195, 'Blue Reverie', 39, 8, 2, '/music/blue_reverie.mp3', 275, '2025-04-28 16:55:04'),
(196, 'Frozen Lake', 40, 8, 2, '/music/frozen_lake.mp3', 209, '2025-04-28 16:55:04'),
(197, 'Snowfall Dreams', 40, 8, 2, '/music/snowfall_dreams.mp3', 255, '2025-04-28 16:55:04'),
(198, 'Faded Footprints', 40, 8, 2, '/music/faded_footprints.mp3', 200, '2025-04-28 16:55:04'),
(199, 'Silent Serenade', 40, 8, 2, '/music/silent_serenade.mp3', 267, '2025-04-28 16:55:04'),
(200, 'Arctic Glow', 40, 8, 2, '/music/arctic_glow.mp3', 241, '2025-04-28 16:55:04'),
(201, 'Rebel Anthem', 41, 9, 2, '/music/rebel_anthem.mp3', 221, '2025-04-28 16:57:24'),
(202, 'Burning Bridges', 41, 9, 2, '/music/burning_bridges.mp3', 272, '2025-04-28 16:57:24'),
(203, 'Concrete Heart', 41, 9, 2, '/music/concrete_heart.mp3', 189, '2025-04-28 16:57:24'),
(204, 'Friction Skies', 41, 9, 2, '/music/friction_skies.mp3', 259, '2025-04-28 16:57:24'),
(205, 'Underground Dreams', 41, 9, 2, '/music/underground_dreams.mp3', 248, '2025-04-28 16:57:24'),
(206, 'Torn Horizons', 42, 9, 2, '/music/torn_horizons.mp3', 198, '2025-04-28 16:57:24'),
(207, 'Broken Crown', 42, 9, 2, '/music/broken_crown.mp3', 281, '2025-04-28 16:57:24'),
(208, 'Neon Rebellion', 42, 9, 2, '/music/neon_rebellion.mp3', 207, '2025-04-28 16:57:24'),
(209, 'City Walls', 42, 9, 2, '/music/city_walls.mp3', 232, '2025-04-28 16:57:24'),
(210, 'Ashes in the Wind', 42, 9, 2, '/music/ashes_in_the_wind.mp3', 217, '2025-04-28 16:57:24'),
(211, 'Outlaw Heart', 43, 9, 2, '/music/outlaw_heart.mp3', 195, '2025-04-28 16:57:24'),
(212, 'Iron Sky', 43, 9, 2, '/music/iron_sky.mp3', 225, '2025-04-28 16:57:24'),
(213, 'Shadow Dancer', 43, 9, 2, '/music/shadow_dancer.mp3', 205, '2025-04-28 16:57:24'),
(214, 'Freedom Call', 43, 9, 2, '/music/freedom_call.mp3', 260, '2025-04-28 16:57:24'),
(215, 'Bloodlines', 43, 9, 2, '/music/bloodlines.mp3', 278, '2025-04-28 16:57:24'),
(216, 'Broken Halos', 44, 9, 2, '/music/broken_halos.mp3', 196, '2025-04-28 16:57:24'),
(217, 'Dusty Roads', 44, 9, 2, '/music/dusty_roads.mp3', 237, '2025-04-28 16:57:24'),
(218, 'Neon Angels', 44, 9, 2, '/music/neon_angels.mp3', 263, '2025-04-28 16:57:24'),
(219, 'Gravity Falls', 44, 9, 2, '/music/gravity_falls.mp3', 215, '2025-04-28 16:57:24'),
(220, 'City Smoke', 44, 9, 2, '/music/city_smoke.mp3', 277, '2025-04-28 16:57:24'),
(221, 'Endless Run', 45, 9, 2, '/music/endless_run.mp3', 211, '2025-04-28 16:57:24'),
(222, 'Silent Revolt', 45, 9, 2, '/music/silent_revolt.mp3', 251, '2025-04-28 16:57:24'),
(223, 'Last Light', 45, 9, 2, '/music/last_light.mp3', 204, '2025-04-28 16:57:24'),
(224, 'Desert Mirage', 45, 9, 2, '/music/desert_mirage.mp3', 268, '2025-04-28 16:57:24'),
(225, 'Ashen Dreams', 45, 9, 2, '/music/ashen_dreams.mp3', 240, '2025-04-28 16:57:24'),
(226, 'Burn the Horizon', 46, 11, 3, '/music/burn_the_horizon.mp3', 219, '2025-04-28 16:58:01'),
(227, 'Ashes and Echoes', 46, 11, 3, '/music/ashes_and_echoes.mp3', 275, '2025-04-28 16:58:01'),
(228, 'Scarred Lands', 46, 11, 3, '/music/scarred_lands.mp3', 188, '2025-04-28 16:58:01'),
(229, 'Crimson Storm', 46, 11, 3, '/music/crimson_storm.mp3', 259, '2025-04-28 16:58:01'),
(230, 'Empires Fall', 46, 11, 3, '/music/empires_fall.mp3', 244, '2025-04-28 16:58:01'),
(231, 'Fire Within', 47, 11, 3, '/music/fire_within.mp3', 200, '2025-04-28 16:58:01'),
(232, 'Electric Dust', 47, 11, 3, '/music/electric_dust.mp3', 285, '2025-04-28 16:58:01'),
(233, 'Battle Cry', 47, 11, 3, '/music/battle_cry.mp3', 206, '2025-04-28 16:58:01'),
(234, 'Rogue Nation', 47, 11, 3, '/music/rogue_nation.mp3', 231, '2025-04-28 16:58:01'),
(235, 'Blackout Skies', 47, 11, 3, '/music/blackout_skies.mp3', 217, '2025-04-28 16:58:01'),
(236, 'Stone Walls', 48, 11, 3, '/music/stone_walls.mp3', 195, '2025-04-28 16:58:01'),
(237, 'Blazing Trails', 48, 11, 3, '/music/blazing_trails.mp3', 224, '2025-04-28 16:58:01'),
(238, 'Scream for Dawn', 48, 11, 3, '/music/scream_for_dawn.mp3', 205, '2025-04-28 16:58:01'),
(239, 'Shadow Empire', 48, 11, 3, '/music/shadow_empire.mp3', 259, '2025-04-28 16:58:01'),
(240, 'Iron Heart', 48, 11, 3, '/music/iron_heart.mp3', 273, '2025-04-28 16:58:01'),
(241, 'Silent Rebellion', 49, 11, 3, '/music/silent_rebellion.mp3', 198, '2025-04-28 16:58:01'),
(242, 'Thunder Breaks', 49, 11, 3, '/music/thunder_breaks.mp3', 237, '2025-04-28 16:58:01'),
(243, 'Ash Rain', 49, 11, 3, '/music/ash_rain.mp3', 262, '2025-04-28 16:58:01'),
(244, 'Walls of Fire', 49, 11, 3, '/music/walls_of_fire.mp3', 216, '2025-04-28 16:58:01'),
(245, 'Dust and Bones', 49, 11, 3, '/music/dust_and_bones.mp3', 279, '2025-04-28 16:58:01'),
(246, 'Rising Embers', 50, 11, 3, '/music/rising_embers.mp3', 211, '2025-04-28 16:58:01'),
(247, 'Night Warriors', 50, 11, 3, '/music/night_warriors.mp3', 250, '2025-04-28 16:58:01'),
(248, 'Broken Chains', 50, 11, 3, '/music/broken_chains.mp3', 204, '2025-04-28 16:58:01'),
(249, 'Lost Horizons', 50, 11, 3, '/music/lost_horizons.mp3', 267, '2025-04-28 16:58:01'),
(250, 'Stormfront', 50, 11, 3, '/music/stormfront.mp3', 239, '2025-04-28 16:58:01'),
(251, 'Voltage Breaker', 51, 12, 3, '/music/voltage_breaker.mp3', 218, '2025-04-28 16:59:06'),
(252, 'Neon Rush', 51, 12, 3, '/music/neon_rush.mp3', 270, '2025-04-28 16:59:06'),
(253, 'Electric Maze', 51, 12, 3, '/music/electric_maze.mp3', 195, '2025-04-28 16:59:06'),
(254, 'Static Heart', 51, 12, 3, '/music/static_heart.mp3', 260, '2025-04-28 16:59:06'),
(255, 'Surge Anthem', 51, 12, 3, '/music/surge_anthem.mp3', 244, '2025-04-28 16:59:06'),
(256, 'Midnight Voltage', 52, 12, 3, '/music/midnight_voltage.mp3', 201, '2025-04-28 16:59:06'),
(257, 'Urban Sparks', 52, 12, 3, '/music/urban_sparks.mp3', 285, '2025-04-28 16:59:06'),
(258, 'Radio Dreams', 52, 12, 3, '/music/radio_dreams.mp3', 207, '2025-04-28 16:59:06'),
(259, 'City Currents', 52, 12, 3, '/music/city_currents.mp3', 233, '2025-04-28 16:59:06'),
(260, 'Power Surge', 52, 12, 3, '/music/power_surge.mp3', 219, '2025-04-28 16:59:06'),
(261, 'Amped Horizons', 53, 12, 3, '/music/amped_horizons.mp3', 192, '2025-04-28 16:59:06'),
(262, 'Electric Fever', 53, 12, 3, '/music/electric_fever.mp3', 224, '2025-04-28 16:59:06'),
(263, 'Pulse Station', 53, 12, 3, '/music/pulse_station.mp3', 205, '2025-04-28 16:59:06'),
(264, 'Wired Skies', 53, 12, 3, '/music/wired_skies.mp3', 259, '2025-04-28 16:59:06'),
(265, 'Lightning Falls', 53, 12, 3, '/music/lightning_falls.mp3', 273, '2025-04-28 16:59:06'),
(266, 'Friction Lights', 54, 12, 3, '/music/friction_lights.mp3', 198, '2025-04-28 16:59:06'),
(267, 'Circuit Wars', 54, 12, 3, '/music/circuit_wars.mp3', 236, '2025-04-28 16:59:06'),
(268, 'Neon Highway', 54, 12, 3, '/music/neon_highway.mp3', 263, '2025-04-28 16:59:06'),
(269, 'Turbo Dreams', 54, 12, 3, '/music/turbo_dreams.mp3', 215, '2025-04-28 16:59:06'),
(270, 'Charged Nights', 54, 12, 3, '/music/charged_nights.mp3', 278, '2025-04-28 16:59:06'),
(271, 'Shockwave Parade', 55, 12, 3, '/music/shockwave_parade.mp3', 210, '2025-04-28 16:59:06'),
(272, 'Energy Fields', 55, 12, 3, '/music/energy_fields.mp3', 251, '2025-04-28 16:59:06'),
(273, 'Neon Reign', 55, 12, 3, '/music/neon_reign.mp3', 204, '2025-04-28 16:59:06'),
(274, 'Voltage Dreams', 55, 12, 3, '/music/voltage_dreams.mp3', 268, '2025-04-28 16:59:06'),
(275, 'Amped Souls', 55, 12, 3, '/music/amped_souls.mp3', 240, '2025-04-28 16:59:06'),
(276, 'Stone Walls', 56, 13, 3, '/music/stone_walls_legacy.mp3', 220, '2025-04-28 17:08:48'),
(277, 'Crumbling Kingdom', 56, 13, 3, '/music/crumbling_kingdom.mp3', 273, '2025-04-28 17:08:48'),
(278, 'Dust and Thunder', 56, 13, 3, '/music/dust_and_thunder.mp3', 194, '2025-04-28 17:08:48'),
(279, 'Ancient Roar', 56, 13, 3, '/music/ancient_roar.mp3', 263, '2025-04-28 17:08:48'),
(280, 'Legacy of Fire', 56, 13, 3, '/music/legacy_of_fire.mp3', 245, '2025-04-28 17:08:48'),
(281, 'Iron Thrones', 57, 13, 3, '/music/iron_thrones.mp3', 202, '2025-04-28 17:08:48'),
(282, 'Rise of Titans', 57, 13, 3, '/music/rise_of_titans.mp3', 286, '2025-04-28 17:08:48'),
(283, 'Shattered Shields', 57, 13, 3, '/music/shattered_shields.mp3', 206, '2025-04-28 17:08:48'),
(284, 'Battle Hymns', 57, 13, 3, '/music/battle_hymns.mp3', 232, '2025-04-28 17:08:48'),
(285, 'Last Crusade', 57, 13, 3, '/music/last_crusade.mp3', 218, '2025-04-28 17:08:48'),
(286, 'Stone Hearted', 58, 13, 3, '/music/stone_hearted.mp3', 193, '2025-04-28 17:08:48'),
(287, 'Legacy Flames', 58, 13, 3, '/music/legacy_flames.mp3', 225, '2025-04-28 17:08:48'),
(288, 'Titan\'s March', 58, 13, 3, '/music/titans_march.mp3', 204, '2025-04-28 17:08:48'),
(289, 'Caves of Echoes', 58, 13, 3, '/music/caves_of_echoes.mp3', 261, '2025-04-28 17:08:48'),
(290, 'Ancient Crown', 58, 13, 3, '/music/ancient_crown.mp3', 278, '2025-04-28 17:08:48'),
(291, 'Mountain Blood', 59, 13, 3, '/music/mountain_blood.mp3', 197, '2025-04-28 17:08:48'),
(292, 'Thunder Legacy', 59, 13, 3, '/music/thunder_legacy.mp3', 236, '2025-04-28 17:08:48'),
(293, 'Crownless', 59, 13, 3, '/music/crownless.mp3', 260, '2025-04-28 17:08:48'),
(294, 'Dustbound', 59, 13, 3, '/music/dustbound.mp3', 214, '2025-04-28 17:08:48'),
(295, 'Stone Breaker', 59, 13, 3, '/music/stone_breaker.mp3', 275, '2025-04-28 17:08:48'),
(296, 'Silent Castles', 60, 13, 3, '/music/silent_castles.mp3', 209, '2025-04-28 17:08:48'),
(297, 'Throne Remnants', 60, 13, 3, '/music/throne_remnants.mp3', 256, '2025-04-28 17:08:48'),
(298, 'Gravel Road', 60, 13, 3, '/music/gravel_road.mp3', 202, '2025-04-28 17:08:48'),
(299, 'Catacombs', 60, 13, 3, '/music/catacombs.mp3', 266, '2025-04-28 17:08:48'),
(300, 'Stone Requiem', 60, 13, 3, '/music/stone_requiem.mp3', 241, '2025-04-28 17:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `fingerprints`
--

CREATE TABLE `fingerprints` (
  `id` varchar(36) NOT NULL,
  `content_id` varchar(36) DEFAULT NULL,
  `fingerprint` longblob NOT NULL,
  `segment_start` float NOT NULL,
  `segment_end` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `genre_name`) VALUES
(1, 'Afrobeat'),
(2, 'Electronic'),
(3, 'Hip-hop'),
(4, 'Jazz'),
(5, 'Pop'),
(6, 'Rock');

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `playlist_content`
--

CREATE TABLE `playlist_content` (
  `playlist_id` varchar(36) NOT NULL,
  `content_id` varchar(36) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `role`, `wallet_address`, `full_name`, `bio`, `created_at`) VALUES
('49c48e2c-14c2-11f0-8be0-d2eaad4e27d1', 'alvinbrocke@gmail.com', '$2y$10$J1gHt.3VyFZPz3UzPPYwlumYU.AN7okJb65aVujzO43so0GqFpo.S', 'admin', NULL, 'Alvin Brocke', NULL, '2025-04-08 21:41:54'),
('766b42a6-153d-11f0-868e-465c6e2b71b3', 'adeijeffreyadei@gmail.com', '$2y$10$qlsviG79cykCqr5MMOfYAOdfzdTpq9LkNc91ca8DXbQFI6s0E95lG', 'artist', NULL, 'Jeffrey Adei', NULL, '2025-04-09 12:23:37'),
('9b8e5002-2387-11f0-9934-465c6e2b71b3', 'vuhakozow@mailinator.com', '$2y$10$CuNXHOgrMIQQUdUVVihS4eP4Mgo4B3OwF8ftyPR8RLzuaU0eqOQOG', 'admin', NULL, 'Vladimir Wallace', NULL, '2025-04-27 16:49:38');

-- --------------------------------------------------------

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `display_albums`
--
ALTER TABLE `display_albums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `display_artistes`
--
ALTER TABLE `display_artistes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `display_genres`
--
ALTER TABLE `display_genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genre_name` (`genre_name`);

--
-- Indexes for table `display_songs`
--
ALTER TABLE `display_songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `album_id` (`album_id`),
  ADD KEY `artiste_id` (`artiste_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `fingerprints`
--
ALTER TABLE `fingerprints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`genre_name`);

--
-- Indexes for table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `playlist_content`
--
ALTER TABLE `playlist_content`
  ADD PRIMARY KEY (`playlist_id`,`content_id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `royalty_transactions`
--
ALTER TABLE `royalty_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content_id` (`content_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content_id` (`content_id`),
  ADD KEY `reported_by` (`reported_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `display_albums`
--
ALTER TABLE `display_albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `display_artistes`
--
ALTER TABLE `display_artistes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `display_genres`
--
ALTER TABLE `display_genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `display_songs`
--
ALTER TABLE `display_songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=376;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `content_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `content_ibfk_3` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);

--
-- Constraints for table `display_songs`
--
ALTER TABLE `display_songs`
  ADD CONSTRAINT `display_songs_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `display_albums` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `display_songs_ibfk_2` FOREIGN KEY (`artiste_id`) REFERENCES `display_artistes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `display_songs_ibfk_3` FOREIGN KEY (`genre_id`) REFERENCES `display_genres` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fingerprints`
--
ALTER TABLE `fingerprints`
  ADD CONSTRAINT `fingerprints_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`);

--
-- Constraints for table `playlists`
--
ALTER TABLE `playlists`
  ADD CONSTRAINT `playlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `playlist_content`
--
ALTER TABLE `playlist_content`
  ADD CONSTRAINT `playlist_content_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlists` (`id`),
  ADD CONSTRAINT `playlist_content_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`);

--
-- Constraints for table `royalty_transactions`
--
ALTER TABLE `royalty_transactions`
  ADD CONSTRAINT `royalty_transactions_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`),
  ADD CONSTRAINT `royalty_transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`),
  ADD CONSTRAINT `violations_ibfk_2` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
