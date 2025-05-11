<?php
require_once __DIR__ . '/../classes/db.php';
require_once __DIR__ . '/includes/user_sidebar.php';

session_start();

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

// Fetch genres from display_genres table
$genres = $db->fetchAll("SELECT id, genre_name as name FROM display_genres ORDER BY genre_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Music - LyricLedger</title>
    <link rel="stylesheet" href="../assets/css/user_dashboard.css">
    <link rel="stylesheet" href="../assets/css/upload.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php userSidebar(); ?>
    
    <div class="main-content">
        <div class="welcome-section">
            <h1>Upload Your Music</h1>
            <p>Share your music with the world and start earning royalties.</p>
        </div>

        <div class="upload-container">
            <form action="../actions/upload_music.php" method="POST" enctype="multipart/form-data" id="uploadForm" onsubmit="return validateForm()">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success-message">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="title">Track Title *</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="genre">Genre *</label>
                    <select id="genre" name="genre" required>
                        <option value="">Select a genre</option>
                        <?php
                        $db = DB::getInstance();
                        $genres = $db->fetchAll("SELECT * FROM genres ORDER BY genre_name");
                        foreach ($genres as $genre) {
                            echo "<option value='" . htmlspecialchars($genre['id']) . "'>" . htmlspecialchars($genre['genre_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="audio">Audio File *</label>
                    <input type="file" id="audio" name="audio" accept=".mp3,.wav" required>
                    <div class="file-info" id="audioInfo"></div>
                </div>

                <div class="form-group">
                    <label for="cover">Cover Art (Optional)</label>
                    <input type="file" id="cover" name="cover" accept="image/*">
                    <div class="file-info" id="coverInfo"></div>
                    <div class="cover-preview" id="coverPreview"></div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" required>
                        I agree to the terms and conditions
                    </label>
                </div>

                <button type="submit" class="upload-btn">
                    <span class="btn-text">Upload Track</span>
                    <div class="progress-bar" id="uploadProgress">
                        <div class="progress"></div>
                    </div>
                </button>
            </form>
        </div>
    </div>

    <script>
    function validateForm() {
        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);
        
        // Log form data
        console.log('Form data being submitted:');
        for (let [key, value] of formData.entries()) {
            if (key === 'audio' || key === 'cover') {
                console.log(key + ': ' + value.name + ' (' + value.size + ' bytes)');
            } else {
                console.log(key + ': ' + value);
            }
        }
        
        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Cover image preview
        const coverInput = document.getElementById('cover');
        const coverPreview = document.getElementById('cover-preview');

        coverInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    coverPreview.innerHTML = `<img src="${e.target.result}" alt="Cover Preview">`;
                }
                reader.readAsDataURL(file);
            } else {
                coverPreview.innerHTML = '';
            }
        });

        // Form submission with progress
        const form = document.querySelector('.upload-form');
        const progressBar = document.querySelector('.progress-bar-fill');
        const progressText = document.querySelector('.progress-text');
        const progressContainer = document.querySelector('.upload-progress');

        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.submit-btn');
            submitBtn.disabled = true;
            progressContainer.style.display = 'block';
        });
    });
    </script>
</body>
</html> 