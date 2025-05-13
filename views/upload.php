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
                    <div class="error-message" id="error-message">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>

                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success-message" id="success-message">
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
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');
        

    async function handleUpload(event) {
        event.preventDefault();
        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);
        const submitBtn = form.querySelector('.upload-btn');
        const btnText = submitBtn.querySelector('.btn-text');
        const progressBar = submitBtn.querySelector('.progress-bar');
        
        try {
            // Disable submit button and show loading state
            submitBtn.disabled = true;
            btnText.textContent = 'Processing...';
            progressBar.style.display = 'block';

            // First, send the audio file to the Flask API for fingerprinting
            const audioFile = formData.get('audio');
            const fingerprintData = new FormData();
            fingerprintData.append('file', audioFile);
            fingerprintData.append('track_id', crypto.randomUUID()); // Generate a unique track ID

            console.log('Sending file:', audioFile);
            const fingerprintResponse = await fetch('http://127.0.0.1:5000/fingerprint', {
                method: 'POST',
                body: fingerprintData
            });

            const fingerprintResult = await fingerprintResponse.json();

            if (!fingerprintResponse.ok) {
                throw new Error(fingerprintResult.error || 'Failed to process audio file');
            }

            // Handle the fingerprint response
            if (fingerprintResult.status === 'similar_tracks_found') {
                // Show similar tracks warning
                const similarTracksList = fingerprintResult.similar_tracks.map(track => 
                    `Track ID: ${track.track_id} (Similarity: ${(track.similarity * 100).toFixed(2)}%)`
                ).join('\n');

                if (!confirm(`Similar tracks found in the database:\n${similarTracksList}\n\nDo you still want to upload this track?`)) {
                    throw new Error('Upload cancelled by user');
                }
            }

            // Add the fingerprint to the form data
            formData.append('fingerprint', JSON.stringify(fingerprintResult));

            // Now send the complete form data to upload_music.php
            const uploadResponse = await fetch('../actions/upload_music.php', {
                method: 'POST',
                body: formData
            });

            const uploadResult = await uploadResponse.text();

            if (!uploadResponse.ok) {
                throw new Error('Failed to upload track');
            }

            // Show success message and redirect
            document.getElementById('success-message').textContent = uploadResult;
            setTimeout(() => {
            errorMessage.textContent = '';
            successMessage.textContent = '';
        }, 5000);
            // window.location.href = 'my-tracks.php';

        } catch (error) {
            console.error('Upload error:', error);
            document.getElementById('error-message').textContent = error.message || 'An error occurred during upload';
            setTimeout(() => {
                errorMessage.innerText = '';
                successMessage.innerHTML = '';
            }, 5000);
        } finally {
            // Reset button state
            submitBtn.disabled = false;
            btnText.textContent = 'Upload Track';
            progressBar.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('uploadForm');
        form.addEventListener('submit', handleUpload);

        // Cover image preview
        const coverInput = document.getElementById('cover');
        const coverPreview = document.getElementById('coverPreview');

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

        // File info display
        const audioInput = document.getElementById('audio');
        const audioInfo = document.getElementById('audioInfo');

        audioInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const size = (file.size / (1024 * 1024)).toFixed(2);
                audioInfo.textContent = `Selected file: ${file.name} (${size} MB)`;
            } else {
                audioInfo.textContent = '';
            }
        });
    });
    </script>
</body>
</html> 