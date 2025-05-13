<?php
require_once __DIR__ . '/../classes/db.php';
require_once __DIR__ . '/../classes/db_connection.php';
session_start();

// Enable error reporting for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    error_log("No user_id in session");
    header('Location: ../auth/login.php');
    exit();
}

// error_log("Session user_id: " . $_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    header('Location: ../views/upload.php');
    exit();
}

// Log upload attempt
error_log("Upload attempt by user " . $_SESSION['user_id']);
error_log("POST data: " . print_r($_POST, true));
error_log("FILES data: " . print_r($_FILES, true));

// Validate input
$title = trim($_POST['title'] ?? '');
$genreId = trim($_POST['genre'] ?? '');
$description = trim($_POST['description'] ?? '');

if (empty($title) || empty($genreId)) {
    error_log("Missing required fields - Title: " . $title . ", Genre ID: " . $genreId);
    $_SESSION['error'] = 'Please fill in all required fields.';
    header('Location: ../views/upload.php');
    exit();
}

// Validate genre ID
$db = DB::getInstance();
error_log("Validating genre ID: " . $genreId);

// Get the genre name from display_genres
$genreName = $db->fetch("SELECT genre_name FROM genres WHERE id = :id", ['id' => $genreId]);

if (!$genreName) {
    $genreName['genre_name'];
    error_log("Invalid display genre ID: " . $genreId);
    $_SESSION['error'] = 'Invalid genre selected.';
    header('Location: ../views/upload.php');
    exit();
}

// error_log("Found display genre: " . );

// Get the corresponding genre ID from genres table
// $genre = $db->fetch("SELECT id FROM genres WHERE name = :name", ['name' => $genreName['genre_name']]);
// if (!$genre) {
//     error_log("Genre not found in genres table, attempting to create: " . $genreName['genre_name']);
//     // If genre doesn't exist in genres table, create it
//     $insertGenre = $db->insert("INSERT INTO genres (name) VALUES (:name)", ['name' => $genreName['genre_name']]);
//     if (!$insertGenre) {
//         $error = $db->getLastError();
//         error_log("Failed to create genre. Error: " . $error);
//         error_log("Error info: " . print_r($db->getLastErrorInfo(), true));
//         $_SESSION['error'] = 'Failed to process genre. Please try again.';
//         header('Location: ../views/upload.php');
//         exit();
//     }
//     $genreId = $insertGenre;
//     error_log("Created new genre with ID: " . $genreId);
// } else {
//     $genreId = $genre['id'];
//     error_log("Found existing genre ID: " . $genreId);
// }

// Handle file uploads
$baseDir = __DIR__ . '/../';
$uploadDir = $baseDir . 'uploads/';
$audioDir = $uploadDir . 'audio/';
// $coverDir = $uploadDir . 'covers/';

// Log directory paths
error_log("Base directory: " . $baseDir);
error_log("Upload directory: " . $uploadDir);
error_log("Audio directory: " . $audioDir);
error_log("Cover directory: " . $coverDir);

// Check if directories exist and are writable
if (!file_exists($uploadDir)) {
    error_log("Upload directory does not exist: " . $uploadDir);
    $_SESSION['error'] = 'Upload directory does not exist. Please contact support.';
    header('Location: ../views/upload.php');
    exit();
}

if (!is_writable($uploadDir)) {
    error_log("Upload directory is not writable: " . $uploadDir);
    $_SESSION['error'] = 'Upload directory is not writable. Please contact support.';
    header('Location: ../views/upload.php');
    exit();
}

if (!file_exists($audioDir)) {
    error_log("Audio directory does not exist: " . $audioDir);
    $_SESSION['error'] = 'Audio directory does not exist. Please contact support.';
    header('Location: ../views/upload.php');
    exit();
}

if (!is_writable($audioDir)) {
    error_log("Audio directory is not writable: " . $audioDir);
    $_SESSION['error'] = 'Audio directory is not writable. Please contact support.';
    header('Location: ../views/upload.php');
    exit();
}

// if (!file_exists($coverDir)) {
//     error_log("Cover directory does not exist: " . $coverDir);
//     $_SESSION['error'] = 'Cover directory does not exist. Please contact support.';
//     header('Location: ../views/upload.php');
//     exit();
// }

// if (!is_writable($coverDir)) {
//     error_log("Cover directory is not writable: " . $coverDir);
//     $_SESSION['error'] = 'Cover directory is not writable. Please contact support.';
//     header('Location: ../views/upload.php');
//     exit();
// }

// Handle audio file
$audioFile = $_FILES['audio'] ?? null;
$audioPath = null;

if ($audioFile && $audioFile['error'] === UPLOAD_ERR_OK) {
    error_log("Processing audio file: " . $audioFile['name']);
    
    $audioExt = strtolower(pathinfo($audioFile['name'], PATHINFO_EXTENSION));
    if (!in_array($audioExt, ['mp3', 'wav'])) {
        $_SESSION['error'] = 'Invalid audio file format. Please upload MP3 or WAV files only.';
        header('Location: ../views/upload.php');
        exit();
    }

    // Check file size (50MB limit)
    if ($audioFile['size'] > 50 * 1024 * 1024) {
        $_SESSION['error'] = 'Audio file is too large. Maximum size is 50MB.';
        header('Location: ../views/upload.php');
        exit();
    }

    $audioFileName = uniqid('audio_') . '.' . $audioExt;
    $audioPath = 'uploads/audio/' . $audioFileName;
    $fullAudioPath = $audioDir . $audioFileName;
    
    error_log("Attempting to move audio file to: " . $fullAudioPath);
    
    if (!move_uploaded_file($audioFile['tmp_name'], $fullAudioPath)) {
        $error = error_get_last();
        error_log("Failed to move uploaded audio file. PHP Error: " . ($error ? $error['message'] : 'Unknown error'));
        $_SESSION['error'] = 'Failed to upload audio file. Please try again.';
        header('Location: ../views/upload.php');
        exit();
    }
    
    error_log("Audio file uploaded successfully to: " . $fullAudioPath);
} else {
    $error = $audioFile ? $audioFile['error'] : 'No file uploaded';
    error_log("Audio file upload error: " . $error);
    $_SESSION['error'] = 'Please select an audio file to upload.';
    header('Location: ../views/upload.php');
    exit();
}

// Handle cover image
$coverFile = $_FILES['cover'] ?? null;
$coverPath = null;

if ($coverFile && $coverFile['error'] === UPLOAD_ERR_OK) {
    error_log("Processing cover image: " . $coverFile['name']);
    
    $coverExt = strtolower(pathinfo($coverFile['name'], PATHINFO_EXTENSION));
    if (!in_array($coverExt, ['jpg', 'jpeg', 'png', 'gif'])) {
        $_SESSION['error'] = 'Invalid image format. Please upload JPG, PNG, or GIF files only.';
        header('Location: ../views/upload.php');
        exit();
    }

    // Check file size (5MB limit)
    if ($coverFile['size'] > 5 * 1024 * 1024) {
        $_SESSION['error'] = 'Cover image is too large. Maximum size is 5MB.';
        header('Location: ../views/upload.php');
        exit();
    }

    $coverFileName = uniqid('cover_') . '.' . $coverExt;
    $coverPath = 'uploads/covers/' . $coverFileName;
    $fullCoverPath = $coverDir . $coverFileName;
    
    error_log("Attempting to move cover image to: " . $fullCoverPath);
    
    if (!move_uploaded_file($coverFile['tmp_name'], $fullCoverPath)) {
        $error = error_get_last();
        error_log("Failed to move uploaded cover image. PHP Error: " . ($error ? $error['message'] : 'Unknown error'));
        $_SESSION['error'] = 'Failed to upload cover image. Please try again.';
        header('Location: ../views/upload.php');
        exit();
    }
    
    error_log("Cover image uploaded successfully to: " . $fullCoverPath);
}

try {
    $db = DB::getInstance();
    $db->beginTransaction();

    // Log current session data
    error_log("Current session data: " . print_r($_SESSION, true));

    // Validate user exists and log user data
    $userData = $db->fetch("SELECT * FROM users WHERE id = :id", ['id' => $_SESSION['user_id']]);
    if (!$userData) {
        error_log("User not found in database: " . $_SESSION['user_id']);
        $_SESSION['error'] = 'User not found in database. Please log in again.';
        header('Location: ../views/upload.php');
        exit();
    }
    error_log("User data found: " . print_r($userData, true));

    // Validate genre exists and log genre data
    $genreData = $db->fetch("SELECT * FROM genres WHERE id = :id", ['id' => $genreId]);
    if (!$genreData) {
        error_log("Genre not found in database: " . $genreId);
        $_SESSION['error'] = 'Invalid genre selected. Please try again.';
        header('Location: ../views/upload.php');
        exit();
    }
    error_log("Genre data found: " . print_r($genreData, true));

    // Generate UUID first
    $uuidResult = $db->fetch("SELECT UUID() as uuid");
    if (!$uuidResult) {
        $_SESSION['error'] = 'Failed to generate track ID. Please try again.';
        header('Location: ../views/upload.php');
        exit();
    }
    $uuid = $uuidResult['uuid'];
    
    error_log("Generated UUID: " . $uuid);
    error_log("User ID: " . $_SESSION['user_id']);
    error_log("Title: " . $title);
    error_log("Audio Path: " . $audioPath);
    error_log("Genre ID: " . $genreId);
    
    // Check if content with this UUID already exists
    $existingContent = $db->fetch("SELECT id FROM content WHERE id = :id", ['id' => $uuid]);
    if ($existingContent) {
        error_log("Content with UUID already exists: " . $uuid);
        $_SESSION['error'] = 'A track with this ID already exists. Please try again.';
        header('Location: ../views/upload.php');
        exit();
    }
    


    try {
    
        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO content (
            id, 
            user_id, 
            genre_id, 
            title, 
            file_path, 
            fingerprint_path, 
            content_status, 
            created_at
        ) VALUES (
            :id, 
            :user_id, 
            :genre_id, 
            :title, 
            :file_path, 
            :fingerprint_path, 
            :content_status, 
            :created_at
        )");
    
        // Bind the parameters
        $stmt->bindParam(':id', $uuid, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindParam(':genre_id', $genreId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':file_path', $audioPath, PDO::PARAM_STR);
        $stmt->bindParam(':fingerprint_path', $fingerprintPath, PDO::PARAM_NULL);
        $stmt->bindValue(':content_status', 'pending', PDO::PARAM_STR);
        $stmt->bindParam(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
    
        // Execute the statement
        $stmt->execute();
    
        // Check if the insert was successful
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = 'Track uploaded successfully!';
                header('Location: ../views/my-tracks.php');
                exit();
        } else {
            throw new Exception('Failed to insert track into database');
        }
    
    } catch (PDOException $e) {
        // Log the error
        error_log("Database Error: " . $e->getMessage());
        $_SESSION['error'] = 'Failed to save track information. Please try again.';
        header('Location: ../views/upload.php');
        exit();
    } catch (Exception $e) {
        // Log the error
        error_log("Error: " . $e->getMessage());
        $_SESSION['error'] = 'An error occurred while uploading your track.';
        header('Location: ../views/upload.php');
        exit();
    }














    // Insert into content table
    // $sql = "INSERT INTO content (id, user_id, genre_id, title, file_path, fingerprint_path, content_status, created_at) 
    //         VALUES (:id, :user_id, :genre_id, :title, :file_path, :fingerprint_path, :content_status, :created_at)";
    
    // $params = [
    //     'id' => $uuid,
    //     'user_id' => $_SESSION['user_id'],
    //     'title' => $title,
    //     'file_path' => $audioPath,
    //     'genre_id' => $genreId,
    //     'fingerprint_path' => NULL,
    //     'content_status' => 'pending',
    //     'created_at' => date('Y-m-d H:i:s')
    // ];
    
    // error_log("Attempting to insert track with params: " . json_encode($params));
    // error_log("SQL Query: " . $sql);
    
    // try {
        // First, verify all foreign keys exist
        // $userCheck = $db->fetch("SELECT 1 FROM users WHERE id = :id", ['id' => $params['user_id']]);
        // if (!$userCheck) {
            
        //     $_SESSION['error'] = 'User account not found. Please log in again.';
        //     header('Location: ../views/upload.php');
        //     exit();
        // }
        
        // $genreCheck = $db->fetch("SELECT 1 FROM genres WHERE id = :id", ['id' => $params['genre_id']]);
        // if (!$genreCheck) {
        //     $_SESSION['error'] = 'Selected genre is not valid. Please try again.';
        //     header('Location: ../views/upload.php');
        //     exit();
        // }
        
    //     $result = $db->insert($sql, $params);
        
    //     if ($result) {
    //         $db->commit();
    //         // error_log("Track inserted successfully with ID: " . $uuid);
    //         $_SESSION['success'] = 'Track uploaded successfully!';
            // header('Location: ../views/my-tracks.php');
    //         exit();
    //     } else {
    //         $db->rollBack();
    //         $error = $db->getLastError();
    //         $errorInfo = $db->getLastErrorInfo();
    //         error_log("Database insert failed. Error: " . $error);
    //         error_log("Error info: " . print_r($errorInfo, true));
    //         error_log("Failed query: " . $sql);
    //         error_log("Failed params: " . json_encode($params));
    //         $_SESSION['error'] = 'Failed to save track information. Please try again.';
    //         // header('Location: ../views/upload.php');
    //         exit();
    //     }
    // } catch (PDOException $e) {
    //     $db->rollBack();
    //     error_log("PDO Exception: " . $e->getMessage());
    //     error_log("Error Code: " . $e->getCode());
    //     error_log("Stack trace: " . $e->getTraceAsString());
    //     $_SESSION['error'] = 'Database error occurred. Please try again.';
    //     header('Location: ../views/upload.php');
    //     exit();
    // }

} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    error_log("Upload error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    $_SESSION['error'] = 'An error occurred while uploading. Please try again.';
    header('Location: ../views/upload.php');
    exit();
}
exit(); 