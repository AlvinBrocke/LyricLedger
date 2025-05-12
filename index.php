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
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
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

        <?php
        $db = DB::getInstance();

        // Fetch all genres from the database
        $genreData = $db->fetchAll("SELECT * FROM genres");

        ?>
        <!-- Music Section -->
        <section class="music-section">
            <h3>Made For You</h3>
            <div class="music-grid">
                <?php
                // Loop through each genre and display it as a music card
                foreach ($genreData as $genre) {
                    // Assuming each genre has a 'name' and 'album_count' field
                    echo '
                    <div class="music-card">
                        <a href="views/songs-genre.php?id=' . htmlspecialchars($genre['id']) . '">
                        <img src="' . htmlspecialchars($genre['genre_coverart']) . '" alt="' . htmlspecialchars($genre['genre_name']) . '">
                        <h4>' . htmlspecialchars($genre['genre_name']) . '</h4>
                    </a>
                    </div>';

                }
                ?>
            </div>
        </section>


    
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