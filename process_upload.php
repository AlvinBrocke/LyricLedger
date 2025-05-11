<?php
require_once __DIR__ . '/models/content.php';
require_once __DIR__ . '/models/violation.php';

header('Content-Type: application/json');

// Check if file was uploaded
if (!isset($_FILES['audio']) || $_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

// Validate file type
$allowed_types = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/x-wav'];
if (!in_array($_FILES['audio']['type'], $allowed_types)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only MP3 and WAV files are allowed']);
    exit;
}

// Get user ID from session (assuming you have user authentication)
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Create upload directory if it doesn't exist
$upload_dir = __DIR__ . '/uploads/audio';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Generate unique filename
$filename = uniqid() . '_' . basename($_FILES['audio']['name']);
$filepath = $upload_dir . '/' . $filename;

// Move uploaded file
if (!move_uploaded_file($_FILES['audio']['tmp_name'], $filepath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
    exit;
}

try {
    // Create content record
    $content = new Content();
    $content_data = [
        'user_id' => $user_id,
        'album_id' => $_POST['album_id'] ?? null,
        'genre_id' => $_POST['genre_id'] ?? null,
        'title' => $_POST['title'] ?? 'Untitled',
        'file_path' => $filepath,
        'duration' => 0, // Will be updated after processing
        'fingerprint_path' => null,
        'status' => 'pending'
    ];
    
    $content_id = $content->uploadContent($content_data);
    
    if (!$content_id) {
        throw new Exception('Failed to create content record');
    }
    
    // Call Python script to process the audio
    $command = sprintf(
        'python3 %s/ai_service/audio_fingerprint_service.py %s %s',
        __DIR__,
        escapeshellarg($filepath),
        escapeshellarg($content_id)
    );
    
    $output = [];
    $return_var = 0;
    exec($command, $output, $return_var);
    
    if ($return_var !== 0) {
        throw new Exception('Failed to process audio file');
    }
    
    // Parse Python script output
    $result = json_decode($output[0], true);
    
    if (!$result['success']) {
        // If processing failed, update content status
        $content->updateContentStatus($content_id, 'failed');
        throw new Exception($result['message']);
    }
    
    // Update content status to success
    $content->updateContentStatus($content_id, 'active');
    
    echo json_encode([
        'success' => true,
        'message' => 'File uploaded and processed successfully',
        'content_id' => $content_id
    ]);
    
} catch (Exception $e) {
    // Clean up uploaded file if processing failed
    if (file_exists($filepath)) {
        unlink($filepath);
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 