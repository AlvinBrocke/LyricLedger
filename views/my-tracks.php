<?php
require_once __DIR__ . '/../classes/db.php';
require_once __DIR__ . '/includes/user_sidebar.php';
require_once __DIR__ . '/../classes/db_connection.php';
session_start();

// Enable error reporting for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Get user data
$db = DB::getInstance();
$user = $db->fetch("SELECT * FROM users WHERE id = :id", ['id' => $_SESSION['user_id']]);

if (!$user) {
    header('Location: ../auth/login.php');
    exit();
}

// Initialize variables
$tracks = [];
$error = null;

try {
    // Get user's tracks with pagination
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    // Get total count for pagination
    $countResult = $db->fetch(
        "SELECT COUNT(*) as total FROM content WHERE user_id = :user_id",
        ['user_id' => $_SESSION['user_id']]
    );
    $totalTracks = $countResult ? $countResult['total'] : 0;
    $totalPages = ceil($totalTracks / $perPage);

    // Get tracks for current page
    $tracks = $db->fetchAll(
    "SELECT c.*, c.id AS content_id, g.*
        FROM content c
        JOIN genres g ON c.genre_id = g.id
        WHERE c.user_id = :user_id
        ORDER BY c.created_at DESC LIMIT :page_limit OFFSET :offset",
        [
            'user_id' => $_SESSION['user_id'],
            'page_limit' => $perPage,
            'offset' => $offset
        ]
    );
} catch (Exception $e) {
    error_log("Error in my-tracks.php: " . $e->getMessage());
    $error = "An error occurred while fetching your tracks. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tracks - LyricLedger</title>
    <link rel="stylesheet" href="../assets/css/user_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php userSidebar(); ?>
    
    <div class="main-content">
        <div class="welcome-section">
            <h1>My Tracks</h1>
            <p>Manage and monitor your uploaded tracks.</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="tracks-container">
            <?php if (empty($tracks)): ?>
                <div class="no-tracks">
                    <i class="fas fa-music"></i>
                    <p>You haven't uploaded any tracks yet.</p>
                    <a href="upload.php" class="upload-btn">Upload Your First Track</a>
                </div>
            <?php else: ?>
                <div class="tracks-grid">
                    <?php foreach ($tracks as $track): ?>
                        <div class="track-card">
                            <!-- if you have a title card add php code here to cater for it -->
                             <!-- should be and if else statement -->
                            
                                <div class="no-cover">
                                    <i class="fas fa-music"></i>
                                </div>
                          
                               
                           
                            <div class="track-info">
                                <h3><?= htmlspecialchars($track['title']) ?></h3>
                                <p class="genre"><?= htmlspecialchars($track['genre_name']) ?></p>
                                <div class="track-stats">
                                    <audio controls class="audio-player">
                                        <source src="../<?= htmlspecialchars($track['file_path']) ?>" type="audio/mpeg" autoplay>
                                    </audio>
                                    <div class="stat">
                                        <i class="fas fa-play"></i>
                                        <span><?= number_format($track['play_count']) ?> Plays</span>
                                    </div>
                                    
                                </div>
                                <div class="track-actions">
                                    <!-- edit button can be added here -->
                                    <button class="delete-btn" data-track-id="<?= $track['content_id'] ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="page-btn">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        <?php endif; ?>
                        
                        <span class="page-info">Page <?= $page ?> of <?= $totalPages ?></span>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="page-btn">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete button clicks
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const trackId = this.dataset.trackId;
                if (confirm('Are you sure you want to delete this track? This action cannot be undone.')) {
                    // Send delete request
                    fetch('../actions/delete_track.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ track_id: trackId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the track card from the DOM
                            this.closest('.track-card').remove();
                        } else {
                            alert(data.message || 'Failed to delete track');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the track');
                    });
                }
            });
        });
    });
    </script>
</body>
</html> 