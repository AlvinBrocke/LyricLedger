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
    <style>
        /* Update modal styles to match user dashboard theme */
        .modal-overlay {
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
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: #ffffff;
            border-radius: 12px;
            padding: 28px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #eaeaea;
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-title i {
            color: #ff6b6b;
            font-size: 1.6rem;
        }

        .modal-body {
            color: #444;
            margin-bottom: 28px;
            line-height: 1.6;
            font-size: 1.05rem;
        }

        .modal-body p {
            margin-bottom: 16px;
        }

        .similar-tracks-list {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
            max-height: 240px;
            overflow-y: auto;
            border: 1px solid #eaeaea;
        }

        .similar-tracks-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .similar-tracks-list li {
            padding: 12px 0;
            border-bottom: 1px solid #eaeaea;
            color: #555;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .similar-tracks-list li:before {
            content: '🎵';
            font-size: 1.1rem;
        }

        .similar-tracks-list li:last-child {
            border-bottom: none;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            padding-top: 16px;
            border-top: 1px solid #eaeaea;
        }

        .modal-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .modal-btn i {
            font-size: 1rem;
        }

        .modal-btn.cancel {
            background: #f8f9fa;
            color: #555;
            border: 1px solid #eaeaea;
        }

        .modal-btn.cancel:hover {
            background: #e9ecef;
            color: #333;
        }

        .modal-btn.confirm {
            background: #4CAF50;
            color: white;
        }

        .modal-btn.confirm:hover {
            background: #43A047;
            transform: translateY(-1px);
        }

        .modal-btn.danger {
            background: #ff6b6b;
            color: white;
        }

        .modal-btn.danger:hover {
            background: #ff5252;
            transform: translateY(-1px);
        }

        /* Add scrollbar styling for the similar tracks list */
        .similar-tracks-list::-webkit-scrollbar {
            width: 8px;
        }

        .similar-tracks-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .similar-tracks-list::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        .similar-tracks-list::-webkit-scrollbar-thumb:hover {
            background: #bbb;
        }

        /* Add status modal styles */
        .status-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
        }

        .status-modal-content {
            background: #ffffff;
            border-radius: 12px;
            padding: 28px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            animation: modalSlideIn 0.3s ease-out;
            text-align: center;
        }

        .status-modal-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .status-modal-icon.success {
            color: #4CAF50;
        }

        .status-modal-icon.error {
            color: #ff6b6b;
        }

        .status-modal-message {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .status-modal-progress {
            width: 100%;
            height: 4px;
            background: #f0f0f0;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 20px;
        }

        .status-modal-progress-bar {
            height: 100%;
            background: #4CAF50;
            width: 100%;
            animation: progressShrink 5s linear forwards;
        }

        @keyframes progressShrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
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

    <!-- Add the status modal HTML before the similarity modal -->
    <div id="statusModal" class="status-modal-overlay">
        <div class="status-modal-content">
            <div class="status-modal-icon">
                <i class="fas fa-check-circle success"></i>
                <i class="fas fa-times-circle error"></i>
            </div>
            <div class="status-modal-message"></div>
            <div class="status-modal-progress">
                <div class="status-modal-progress-bar"></div>
            </div>
        </div>
    </div>

    <div id="similarityModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Similar Tracks Found
                </div>
            </div>
            <div class="modal-body">
                <p>We found some similar tracks in our database that might match your upload. Please review them carefully:</p>
                <div class="similar-tracks-list" id="similarTracksList">
                    <!-- Similar tracks will be inserted here -->
                </div>
                <p>Would you like to proceed with the upload anyway?</p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn cancel" onclick="closeSimilarityModal()">
                    <i class="fas fa-times"></i>
                    Cancel Upload
                </button>
                <button class="modal-btn confirm" onclick="confirmUpload()">
                    <i class="fas fa-check"></i>
                    Proceed with Upload
                </button>
            </div>
        </div>
    </div>

    <script>
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');
        
        let uploadPromiseResolve;
        let uploadPromiseReject;

        function showSimilarityModal(similarTracks) {
            const modal = document.getElementById('similarityModal');
            const tracksList = document.getElementById('similarTracksList');
            
            // Clear previous content
            tracksList.innerHTML = '';
            
            // Add similar tracks to the list
            const ul = document.createElement('ul');
            similarTracks.forEach(track => {
                const li = document.createElement('li');
                const similarity = (track.similarity * 100).toFixed(1);
                li.innerHTML = `
                    <span class="track-title">${track.title}</span>
                    <span class="similarity-badge" style="
                        background: ${similarity >= 80 ? '#ff6b6b' : similarity >= 50 ? '#ffd93d' : '#4CAF50'};
                        color: ${similarity >= 80 ? 'white' : '#333'};
                        padding: 2px 8px;
                        border-radius: 12px;
                        font-size: 0.9rem;
                        margin-left: auto;
                    ">
                        ${similarity}% match
                    </span>
                `;
                ul.appendChild(li);
            });
            tracksList.appendChild(ul);
            
            // Show the modal with animation
            modal.style.display = 'flex';
            
            // Return a promise that will be resolved when the user makes a choice
            return new Promise((resolve, reject) => {
                uploadPromiseResolve = resolve;
                uploadPromiseReject = reject;
            });
        }

        function closeSimilarityModal() {
            const modal = document.getElementById('similarityModal');
            modal.style.display = 'none';
            if (uploadPromiseReject) {
                uploadPromiseReject(new Error('Upload cancelled by user'));
            }
        }

        function confirmUpload() {
            const modal = document.getElementById('similarityModal');
            modal.style.display = 'none';
            if (uploadPromiseResolve) {
                uploadPromiseResolve();
            }
        }

        // Add status modal functions
        function showStatusModal(message, isSuccess) {
            const modal = document.getElementById('statusModal');
            const iconContainer = modal.querySelector('.status-modal-icon');
            const successIcon = iconContainer.querySelector('.success');
            const errorIcon = iconContainer.querySelector('.error');
            const messageElement = modal.querySelector('.status-modal-message');
            
            // Set message and icon
            messageElement.textContent = message;
            successIcon.style.display = isSuccess ? 'block' : 'none';
            errorIcon.style.display = isSuccess ? 'none' : 'block';
            
            // Show modal
            modal.style.display = 'flex';
            
            // Hide modal after 5 seconds
            setTimeout(() => {
                modal.style.display = 'none';
                if (isSuccess) {
                    window.location.href = 'my-tracks.php';
                }
            }, 2000);
        }

        // Update the handleUpload function
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
                fingerprintData.append('track_id', crypto.randomUUID());

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
                    const similarTracks = fingerprintResult.similar_tracks;
                    await showSimilarityModal(similarTracks);
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

                // Show success message in modal
                showStatusModal('Track uploaded successfully! Redirecting to your tracks...', true);

            } catch (error) {
                if (error.message === 'Upload cancelled by user') {
                    // Handle user cancellation
                    console.log('Upload cancelled by user');
                    return;
                }
                // Show error message in modal
                showStatusModal(error.message || 'An error occurred during upload', false);
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