<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LyricLedger</title>
    <link rel="stylesheet" href="../assets/css/index.css">
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
            <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="trending.php"><i class="fas fa-fire"></i> Trending</a></li>
            <li><a href="songs.php"><i class="fas fa-music"></i> Songs</a></li>
            <li><a href="albums.php"><i class="fas fa-compact-disc"></i> Albums</a></li>
            <li><a href="artists.php"><i class="fas fa-microphone-alt"></i> Artists</a></li>
            <li><a href="podcasts.php"><i class="fas fa-podcast"></i> Podcast</a></li>
            <li><a href="views/auth/register.php"><i class="fas fa-user"></i> Login</a></li>
            
        </ul>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero/Banner Section -->
       



        <!-- Music Section -->
        <section class="music-section">
            <h3>AfroBeats Albums</h3>
            <div class="music-grid">
                <div class="music-card">
                    <img src="../assets/media/images/rhythms_of_Accra.jpg" alt="Rhythms of Accra">
                    <h4>Rhythms of Accra</h4>
                    <p>13 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/sunset_vibes.jpg" alt="Sunset Vibes">
                    <h4>Sunset Vibes</h4>
                    <p>20 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/Lagos.jpg" alt="Heartbeat Lagos">
                    <h4>Heartbeat Lagos</h4>
                    <p>10 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/island_pulse.jpg" alt="Island Pulse">
                    <h4>Island Pulse</h4>
                    <p>15 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/golden_nights.jpg" alt="Hip-Hop">
                    <h4>Golden Nights</h4>
                    <p>10 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/royal_groove.jpg" alt="Electronic">
                    <h4>Royal Groove</h4>
                    <p>15 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/melody_waters.jpg" alt="Pop">
                    <h4>Melody Waters</h4>
                    <p>20 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/afro_sunrise.jpg" alt="Pop">
                    <h4>Afro Sunrise</h4>
                    <p>20 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/soni_tribes.jpg" alt="Pop">
                    <h4>Sonic Tribes</h4>
                    <p>20 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/neon_dreams.jpg" alt="Pop">
                    <h4>Neon Dreams</h4>
                    <p>20 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/flaming_drums.jpg" alt="Pop">
                    <h4>Flaming Drums</h4>
                    <p>20 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/skyline_hearts.jpg" alt="Pop">
                    <h4>Skyline Hearts</h4>
                    <p>20 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/pastel_waves.jpg" alt="Pop">
                    <h4>Pastel Waves</h4>
                    <p>20 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/velvet_echo.jpg" alt="Pop">
                    <h4>Velvet Echo</h4>
                    <p>20 songs</p>
                </div>
                <div class="music-card">
                    <img src="../assets/media/images/electric_boom.jpg" alt="Pop">
                    <h4>Electric Bloom</h4>
                    <p>20 songs</p>
                </div>
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

</body>
</html>