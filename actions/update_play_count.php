<?php
require_once __DIR__ . '/../classes/db.php';
session_start();

// Enable error reporting for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Get the content ID from the request
$contentId = $_POST['content_id'] ?? null;

if (!$contentId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Content ID is required']);
    exit();
}

try {
    $db = DB::getInstance();
    
    // Update both total and current play counts
    $sql = "UPDATE content SET 
            total_play_count = COALESCE(total_play_count, 0) + 1,
            current_play_count = COALESCE(current_play_count, 0) + 1 
            WHERE id = :id";
    $result = $db->update($sql, ['id' => $contentId]);
    
    if ($result) {
        // Also record the play in the plays table for detailed tracking
        $playSql = "INSERT INTO plays (id, content_id, played_at) VALUES (UUID(), :content_id, NOW())";
        $db->insert($playSql, ['content_id' => $contentId]);
        
        echo json_encode(['success' => true, 'message' => 'Play count updated successfully']);
    } else {
        throw new Exception('Failed to update play count');
    }
} catch (Exception $e) {
    error_log("Error in update_play_count.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred while updating play count']);
} 