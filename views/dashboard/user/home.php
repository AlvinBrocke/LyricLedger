<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session at the very beginning
session_start();

// Debug session
error_log("Session data: " . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    error_log("No user_id in session");
    header('Location: ../../auth/login.php');
    exit();
}

// Include required files with absolute paths
require_once __DIR__ . '/../../../classes/db.php';
require_once __DIR__ . '/../../includes/user_sidebar.php';

// Get user data
$db = DB::getInstance();
$user = $db->fetch("SELECT * FROM users WHERE id = :id", ['id' => $_SESSION['user_id']]);

// Debug user data
error_log("User data: " . print_r($user, true));

if (!$user) {
    error_log("No user found in database");
    header('Location: ../../auth/login.php');
    exit();
}

// Initialize default values
$trackCount = 0;
$royalties = 0;
$monthlyPlays = 0;
$recentUploads = [];

try {
    // Get user's track count
    $trackResult = $db->fetch("SELECT COUNT(*) as count FROM content WHERE user_id = :user_id", 
        ['user_id' => $_SESSION['user_id']]);
    $trackCount = $trackResult ? $trackResult['count'] : 0;
    error_log("Track count: " . $trackCount);

    // Get total royalties
    $royaltiesResult = $db->fetch("SELECT COALESCE(SUM(amount), 0) as total FROM royalties WHERE user_id = :user_id", 
        ['user_id' => $_SESSION['user_id']]);
    $royalties = $royaltiesResult ? $royaltiesResult['total'] : 0;
    error_log("Royalties: " . $royalties);

    // Get monthly plays
    $playsResult = $db->fetch("SELECT COUNT(*) as count FROM plays WHERE content_id IN 
        (SELECT id FROM content WHERE user_id = :user_id) AND MONTH(played_at) = MONTH(CURRENT_DATE())", 
        ['user_id' => $_SESSION['user_id']]);
    $monthlyPlays = $playsResult ? $playsResult['count'] : 0;
    error_log("Monthly plays: " . $monthlyPlays);

    // Get recent uploads
    $recentUploads = $db->fetchAll(
        "SELECT c.*, c.id AS content_id, g.*
            FROM content c
            JOIN genres g ON c.genre_id = g.id
            WHERE c.user_id = :user_id
            ORDER BY c.created_at DESC LIMIT 3",
            [
                'user_id' => $_SESSION['user_id'],
                
            ]
        );
    error_log("Recent uploads: " . print_r($recentUploads, true));
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
}

// Set session variables if not already set
if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $user['full_name'] ?? 'User';
}
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = $user['role'] ?? 'user';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - LyricLedger</title>
    <link rel="stylesheet" href="../../../assets/css/user_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php 
    // Debug sidebar inclusion
    error_log("Including sidebar");
    userSidebar(); 
    error_log("Sidebar included");
    ?>
    
    <div class="main-content">
        <div class="welcome-section">
            <h1>Welcome, <?= htmlspecialchars($user['full_name'] ?? 'User') ?>!</h1>
            <p>Manage your music and track your royalties on LyricLedger.</p>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <i class="fa-solid fa-music"></i>
                <div class="stat-info">
                    <h3>Your Tracks</h3>
                    <p><?= number_format($trackCount) ?> tracks</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-file-signature"></i>
                <div class="stat-info">
                    <h3>Total Royalties</h3>
                    <p>$<?= number_format($royalties, 2) ?></p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-chart-line"></i>
                <div class="stat-info">
                    <h3>Monthly Plays</h3>
                    <p><?= number_format($monthlyPlays) ?> plays</p>
                </div>
            </div>
        </div>

        <div class="recent-uploads">
            <h2>Recent Uploads</h2>
            <div class="upload-list">
                <?php if (empty($recentUploads)): ?>
                    <p class="no-uploads">You haven't uploaded any tracks yet.</p>
                <?php else: ?>
                    <div class="track-grid">
                        <?php foreach ($recentUploads as $track): ?>
                            <div class="track-card">
                                
                                    <div class="no-cover">
                                        <i class="fas fa-music"></i>
                                    </div>
                                <div class="track-info">
                                    <h4><?= htmlspecialchars($track['title']) ?></h4>
                                    <p class="genre"><?= htmlspecialchars($track['genre_name']) ?></p>
                                    <p class="date">
                                        <?= date('M d, Y', strtotime($track['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../../../assets/js/script.js"></script>
</body>
</html> 