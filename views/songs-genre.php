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
            <h3>AfroBeats Songs</h3>
            <div class="music-grid">
                <?php   foreach ($genreSongs as $songs) { 
                    echo '<div class="music-card">
                    <img src="../' . htmlspecialchars($songs['genre_coverart']) . '" alt="' . htmlspecialchars($songs['genre_name']) . '">
                    <h4>' . htmlspecialchars($songs['title']) . '</h4>
                    <audio controls class="audio-player">
                        <source src="../' . htmlspecialchars($songs['file_path']) . '" type="audio/mpeg">
                    </audio>

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

</body>
</html>