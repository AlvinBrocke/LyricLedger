<?php
require_once __DIR__ . '/../classes/db.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$trackId = $input['track_id'] ?? null;

if (!$trackId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Track ID is required']);
    exit();
}

try {
    $db = DB::getInstance();
    
    // First verify that the track belongs to the user
    $track = $db->fetch(
        "SELECT * FROM content WHERE id = :id AND user_id = :user_id",
        ['id' => $trackId, 'user_id' => $_SESSION['user_id']]
    );

    if (!$track) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Track not found or unauthorized']);
        exit();
    }

    // Begin transaction
    $db->beginTransaction();

    try {
        // Delete related records first
        $db->delete("DELETE FROM plays WHERE content_id = :id", ['id' => $trackId]);
        $db->delete("DELETE FROM royalties WHERE content_id = :id", ['id' => $trackId]);
        
        // Delete the track
        $success = $db->delete("DELETE FROM content WHERE id = :id", ['id' => $trackId]);

        if ($success) {
            // Delete the audio file if it exists
            if ($track['audio_file'] && file_exists(__DIR__ . '/../' . $track['audio_file'])) {
                unlink(__DIR__ . '/../' . $track['audio_file']);
            }

            // Delete the cover image if it exists
            if ($track['cover_image'] && file_exists(__DIR__ . '/../' . $track['cover_image'])) {
                unlink(__DIR__ . '/../' . $track['cover_image']);
            }

            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Track deleted successfully']);
        } else {
            throw new Exception('Failed to delete track');
        }
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
} catch (Exception $e) {
    error_log("Error in delete_track.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred while deleting the track']);
} 