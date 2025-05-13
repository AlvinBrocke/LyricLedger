<?php
require_once __DIR__ . '/../../../classes/db.php';
session_start();

// Check if user is logged in and is an admin


// Get database connection
$db = DB::getInstance();

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_content'])) {
    $content_id = isset($_POST['content_id']) ? (int)$_POST['content_id'] : 0;
    
    if ($content_id > 0) {
        try {
            // Delete the content record
            $sql = "DELETE FROM content WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$content_id]);
            
            $_SESSION['success'] = "Content deleted successfully.";
        } catch (PDOException $e) {
            error_log("Error deleting content: " . $e->getMessage());
            $_SESSION['error'] = "Failed to delete content.";
        }
    }
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch titles, genre names, and creation dates from content and genres tables
try {
    $content = $db->fetchAll("
        SELECT c.id, c.title, g.genre_name, c.created_at 
        FROM content c
        LEFT JOIN genres g ON c.genre_id = g.id
        ORDER BY c.created_at DESC
    ");
} catch (Exception $e) {
    error_log("Error fetching content: " . $e->getMessage());
    $content = [];
    $error = "Failed to load content. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management - LyricLedger</title>
    <link rel="stylesheet" href="../../../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/content.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.2s;
        }
        
        .delete-btn:hover {
            background-color: #c82333;
        }
        
        .delete-btn i {
            font-size: 14px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
        
        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        
        .modal-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .modal-btn.confirm {
            background-color: #dc3545;
            color: white;
        }
        
        .modal-btn.cancel {
            background-color: #6c757d;
            color: white;
        }
        
        .modal-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php require_once '../../includes/sidebar.php'; sidebar(); ?>

        <div class="main-content">
            <div class="header">
                <h1>Content Management</h1>
                <div class="search-date">
                    <div class="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search content...">
                    </div>
                    <div class="date"><?= date('l, F j') ?></div>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (empty($content)): ?>
                <div class="empty-state">
                    <i class="fas fa-music fa-3x"></i>
                    <p>No content found in the system.</p>
                </div>
            <?php else: ?>
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Content List</div>
                    </div>
                    <div class="content-list">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Genre</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($content as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="content-info">
                                                <i class="fas fa-music"></i>
                                                <?= htmlspecialchars($item['title']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="genre-info">
                                                <i class="fas fa-tag"></i>
                                                <?= htmlspecialchars($item['genre_name'] ?? 'Uncategorized') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <i class="fas fa-clock"></i>
                                                <?= date('M j, Y g:i A', strtotime($item['created_at'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this content?');">
                                                <input type="hidden" name="content_id" value="<?= $item['id'] ?>">
                                                <input type="hidden" name="delete_content" value="1">
                                                <button type="submit" class="delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Confirm Delete</h3>
            <p>Are you sure you want to delete "<span id="contentTitle"></span>"?</p>
            <p>This action cannot be undone.</p>
            <form method="POST" id="deleteForm">
                <input type="hidden" name="content_id" id="contentId">
                <input type="hidden" name="delete_content" value="1">
                <div class="modal-buttons">
                    <button type="button" class="modal-btn cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="modal-btn confirm">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Search functionality
    document.querySelector('.search input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");
        
        rows.forEach(row => {
            const title = row.querySelector('.content-info').textContent.toLowerCase();
            const genre = row.querySelector('.genre-info').textContent.toLowerCase();
            const date = row.querySelector('.date-info').textContent.toLowerCase();
            
            if (title.includes(searchTerm) || genre.includes(searchTerm) || date.includes(searchTerm)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    // Delete confirmation modal functions
    function confirmDelete(contentId, contentTitle) {
        const modal = document.getElementById('deleteModal');
        const titleSpan = document.getElementById('contentTitle');
        const contentIdInput = document.getElementById('contentId');
        
        titleSpan.textContent = contentTitle;
        contentIdInput.value = contentId;
        modal.style.display = 'flex';
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeModal();
        }
    }

    // Handle form submission
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Error deleting content. Please try again.');
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('Error deleting content. Please try again.');
        });
    });
    </script>
</body>
</html>