<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LyricLedger - Songs</title>
    <link rel="stylesheet" href="../assets/css/songs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="js/app.js"></script>
    <style>
        body {
    margin: 0;
    background-color: #121212;
    font-family: 'Poppins', sans-serif;
    color: #fff;
}

/* Sidebar Navigation */
.sidebar {
    width: 200px;
    background-color: #0d0d0d;
    padding: 20px;
    position: fixed;
    height: 100%;
    overflow-y: auto;
}

.sidebar .logo {
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    padding: 10px 0;
    color: orange;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    margin: 15px 0;
}

.sidebar ul li a {
    color: orange;
    text-decoration: none;
    font-size: 16px;
    display: block;
    padding: 10px;
    border-radius: 5px;
    transition: 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: orange;
    color: black;
}

.main-content {
    margin-left: 240px;
    padding: 30px;
}

.songs-section h3 {
    font-size: 28px;
    margin-bottom: 20px;
    color: orange;
}

.song-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.song-item {
    background-color: #1e1e1e;
    border-radius: 12px;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: transform 0.3s ease, background-color 0.3s ease;
    cursor: pointer;
}

.song-item:hover {
    background-color: #ff6a00;
    transform: scale(1.02);
}

.song-info h4 {
    margin: 0;
    font-size: 18px;
}

.song-info p {
    margin: 5px 0 0 0;
    font-size: 14px;
    color: #bbb;
}

.duration {
    font-size: 14px;
    color: #bbb;
}

.footer {
    text-align: center;
    padding: 20px;
    margin-top: 40px;
    color: #777;
    font-size: 14px;
}


    </style>
</head>
<body>

<?php
    include("classes/db.php");
?>

<!-- Sidebar Navigation -->
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
    <section class="songs-section">
        <h3>Featured Songs</h3>
        <div class="song-list">
            <div class="song-item">
                <div class="song-info">
                    <h4>Midnight Market</h4>
                    <p>Kwame Blaze</p>
                </div>
                <span class="duration">3:45</span>
            </div>

            <div class="song-item">
                <div class="song-info">
                    <h4>Ocean Breeze</h4>
                    <p>Sophia Blue</p>
                </div>
                <span class="duration">4:10</span>
            </div>

            <div class="song-item">
                <div class="song-info">
                    <h4>Voltage Breaker</h4>
                    <p>The Voltage</p>
                </div>
                <span class="duration">3:55</span>
            </div>

            <div class="song-item">
                <div class="song-info">
                    <h4>Neon Dreams</h4>
                    <p>Lana Skye</p>
                </div>
                <span class="duration">4:02</span>
            </div>

            <div class="song-item">
                <div class="song-info">
                    <h4>Roots Awakening</h4>
                    <p>Zion Sound</p>
                </div>
                <span class="duration">4:30</span>
            </div>

            <!-- Add more song items below -->
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2024 LyricLedger. All rights reserved.</p>
</footer>

</body>
</html>
