<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LyricLedger</title>
    <link rel="stylesheet" href="assets/css/index.css">
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
        <div class="banner">
            <div class="banner-content">
                <h2>Protect Your Music, Secure Your Rights</h2>
                <p>Contact us to load your catalogue.</p>
                <a href="mailto:lyricledger@support.com" class="cta-btn">Contact Us</a>
            </div>
        </div>



        <!-- Music Section -->
        <section class="music-section">
            <h3>Made For You</h3>
            <div class="music-grid">
                <div class="music-card">
                    <img src="assets/media/images/afrobeats.jpg" alt="Afrobeats">
                    <h4>Afrobeats</h4>
                    <p>13 albums</p>
                </div>
                <div class="music-card">
                    <img src="assets/media/images/pop_cover.jpg" alt="Pop">
                    <h4>Pop</h4>
                    <p>20 albums</p>
                </div>
                <div class="music-card">
                    <img src="assets/media/images/rock.jpg" alt="Rock">
                    <h4>Rock</h4>
                    <p>10 albums</p>
                </div>
                <div class="music-card">
                    <img src="assets/media/images/jazz.jpg" alt="Jazz">
                    <h4>Jazz</h4>
                    <p>15 albums</p>
                </div>
                <div class="music-card">
                    <img src="assets/media/images/hip-hop.jpg" alt="Hip-Hop">
                    <h4>Hip-Hop</h4>
                    <p>10 albums</p>
                </div>
                <div class="music-card">
                    <img src="assets/media/images/electronic_music.jpg" alt="Electronic">
                    <h4>Electronic</h4>
                    <p>15 albums</p>
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