<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LyricLedger</title>
    <link rel="stylesheet" href="../music_royalty_2.0/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="js/app.js"></script>
    
</head>
<body>

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
            <li><a href="#" onclick="openLogin()"><i class="fas fa-user"></i> Login</a></li>
            <li><a href="signup.php"><i class="fas fa-user-plus"></i> Sign Up</a></li>
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
                    <img src="images/Asem-Beba-Dabi-afrocharts-5f5e81505d46c7d9cd7e38f-500x500.png" alt="HighLife">
                    <h4>HighLife</h4>
                    <p>13 songs</p>
                </div>
                <div class="music-card">
                    <img src="images/christian-music-album-cover-design-template-6dfdbeed0d86f1f6c472ca71170edc30_screen.jpg" alt="Worship">
                    <h4>Worship</h4>
                    <p>20 songs</p>
                </div>
                <div class="music-card">
                    <img src="images/08a9b0e93fa0bd61e83f1e3a9be9e547.jpg" alt="AfroPop">
                    <h4>AfroPop</h4>
                    <p>10 songs</p>
                </div>
                <div class="music-card">
                    <img src="images/Screen+Shot+2019-12-31+at+3.08.47+PM.png" alt="Gospel">
                    <h4>Gospel</h4>
                    <p>15 songs</p>
                </div>
                <div class="music-card">
                    <img src="images/0x1900-000000-80-0-0.jpg" alt="Amapiano">
                    <h4>Amapiano</h4>
                    <p>10 songs</p>
                </div>
                <div class="music-card">
                    <img src="images/images.jpeg" alt="R&B">
                    <h4>R&B</h4>
                    <p>15 songs</p>
                </div>
            </div>
        </section>
    </main>

    
    <!-- Popup Login Form -->
<div id="loginPopup" class="popup-overlay">
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
</div>


    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 LyricLedger. All rights reserved.</p>
    </footer>

</body>
</html>