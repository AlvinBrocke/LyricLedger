<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LyricLedger</title>
    <link rel="stylesheet" href="../assets/css/songs-genre.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="js/app.js"></script>

    <style>
        img {
            height: 250px;
        }
    </style>
    
</head>
<body>

<?php
    include("classes/db.php");
    

?>

    <!-- Sidebar Navigation (Header) -->
    <header class="sidebar">
        <div class="logo">LyricLedger</div>
        <ul>
            <li><a href="../index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="auth/login.php"><i class="fas fa-user"></i> Login</a></li>
            
        </ul>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero/Banner Section -->
       

        <?php

        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);



        include("../classes/db.php");
        $db = DB::getInstance();
        $genreId = $_GET['id'];
        $genreSongs = $db->fetchAll("SELECT * FROM genres JOIN content ON genres.id = content.genre_id WHERE genres.id = :id", ['id' => $genreId]);
        ?>

        

        <!-- Music Section -->
         
        <section class="music-section">
            <h3>Songs</h3>
            <div class="music-grid">
                <?php   foreach ($genreSongs as $songs) { 
                    echo '<div class="music-card">
                    <img src="../' . htmlspecialchars($songs['genre_coverart']) . '" alt="' . htmlspecialchars($songs['genre_name']) . '">
                    <h4>' . htmlspecialchars($songs['title']) . '</h4>
                    <audio controls class="audio-player" data-content-id="' . htmlspecialchars($songs['id']) . '">
                        <source src="../' . htmlspecialchars($songs['file_path']) . '" type="audio/mpeg" >
                    </audio>
                    <div class="track-stats">
                        <div class="play-count">
                            <i class="fas fa-play"></i>
                            <span class="count">' . number_format($songs['total_play_count'] ?? 0) . ' Plays</span>
                        </div>
                        <div class="download-count">
                            <i class="fas fa-download"></i>
                            <span class="count">' . number_format($songs['download_count'] ?? 0) . ' Downloads</span>
                        </div>
                    </div>
                    <div class="track-actions">
                        <a href="../' . htmlspecialchars($songs['file_path']) . '" 
                           class="download-btn" 
                           data-content-id="' . htmlspecialchars($songs['id']) . '"
                           download="' . htmlspecialchars($songs['title']) . '.mp3">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>';
                }
                ?>

               
            </div>
        </section>
    </main>

    
    <!-- Popup Login Form -->
<!--<div id="loginPopup" class="popup-overlay">
    <div class="popup-content">
        <span class="close-btn" onclick="closeLogin()">&times;</span>
        <h2>Login</h2>
        <form>
            <div class="input-group">
                <label for="number"><i class="fas fa-phone"></i> Phone Number</label>
                <input type="text" id="number" name="number" placeholder="Enter your number" required>
            </div>
            <div class="input-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <p class="signup-text">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</div> -->


    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 LyricLedger. All rights reserved.</p>
    </footer>

<script>
// Track play time for each audio player
document.querySelectorAll('.audio-player').forEach(player => {
    let playStartTime = null;
    let hasCounted = false;
    const contentId = player.dataset.contentId;
    const countElement = player.parentElement.querySelector('.play-count .count');

    // Stop other audio players when one starts playing
    player.addEventListener('play', function() {
        document.querySelectorAll('.audio-player').forEach(otherPlayer => {
            if (otherPlayer !== player) {
                otherPlayer.pause();
            }
        });
        playStartTime = Date.now();
        hasCounted = false;
    });

    // Handle pause
    player.addEventListener('pause', function() {
        if (playStartTime && !hasCounted) {
            const playDuration = (Date.now() - playStartTime) / 1000; // Convert to seconds
            if (playDuration >= 20) {
                updatePlayCount(contentId, countElement);
                hasCounted = true;
            }
        }
        playStartTime = null;
    });

    // Handle ended
    player.addEventListener('ended', function() {
        if (playStartTime && !hasCounted) {
            const playDuration = (Date.now() - playStartTime) / 1000;
            if (playDuration >= 20) {
                updatePlayCount(contentId, countElement);
                hasCounted = true;
            }
        }
        playStartTime = null;
    });

    // Handle timeupdate to check duration while playing
    player.addEventListener('timeupdate', function() {
        if (playStartTime && !hasCounted) {
            const playDuration = (Date.now() - playStartTime) / 1000;
            if (playDuration >= 20) {
                updatePlayCount(contentId, countElement);
                hasCounted = true;
            }
        }
    });
});

// Handle downloads
document.querySelectorAll('.download-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const contentId = this.dataset.contentId;
        const downloadCountElement = this.closest('.music-card').querySelector('.download-count .count');
        
        // Update download count before starting download
        updateDownloadCount(contentId, downloadCountElement);
    });
});

// Function to update play count
function updatePlayCount(contentId, countElement) {
    const formData = new FormData();
    formData.append('content_id', contentId);

    fetch('../actions/update_play_count.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the displayed count
            const currentCount = parseInt(countElement.textContent.replace(/[^0-9]/g, '')) || 0;
            countElement.textContent = (currentCount + 1).toLocaleString() + ' Plays';
        } else {
            console.error('Failed to update play count:', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating play count:', error);
    });
}

// Function to update download count
function updateDownloadCount(contentId, countElement) {
    const formData = new FormData();
    formData.append('content_id', contentId);

    fetch('../actions/update_download_count.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the displayed count
            const currentCount = parseInt(countElement.textContent.replace(/[^0-9]/g, '')) || 0;
            countElement.textContent = (currentCount + 1).toLocaleString() + ' Downloads';
        } else {
            console.error('Failed to update download count:', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating download count:', error);
    });
}
</script>

<style>
.music-card {
    position: relative;
    margin-bottom: 20px;
    background: #ffffff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.music-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 10px;
}

.music-card h4 {
    margin: 10px 0;
    color: #333333;
    font-size: 1.1rem;
}

.audio-player {
    width: 100%;
    margin: 10px 0;
    display: block;
}

.track-stats {
    display: flex;
    justify-content: space-between;
    margin: 10px 0;
}

.play-count, .download-count {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
    font-size: 0.9rem;
}

.play-count i {
    color: #4f46e5;
}

.download-count i {
    color: #10b981;
}

.count {
    font-weight: 500;
}

.track-actions {
    margin-top: 10px;
    display: flex;
    justify-content: flex-end;
}

.download-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.8rem;
    background-color: #4f46e5;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.85rem;
    transition: background-color 0.2s;
}

.download-btn:hover {
    background-color: #4338ca;
}

.download-btn i {
    font-size: 0.9rem;
}

/* Style the audio player controls */
.audio-player::-webkit-media-controls-panel {
    background-color: #f3f4f6;
}

.audio-player::-webkit-media-controls-play-button {
    background-color: #4f46e5;
    border-radius: 50%;
}

.audio-player::-webkit-media-controls-current-time-display,
.audio-player::-webkit-media-controls-time-remaining-display {
    color: #333333;
}

.audio-player::-webkit-media-controls-timeline {
    background-color: #e5e7eb;
    border-radius: 4px;
    height: 4px;
}

.audio-player::-webkit-media-controls-volume-slider {
    background-color: #e5e7eb;
    border-radius: 4px;
    height: 4px;
}
</style>

</body>
</html>