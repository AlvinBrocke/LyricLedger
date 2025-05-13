<?php
require_once __DIR__ . '/../classes/db.php';
require_once __DIR__ . '/../classes/db_connection.php';
session_start();

// Enable error reporting for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class StreamManager {
    private $db;
    private $streamThreshold = 1000; // Pay every 1000 streams
    private $royaltyRate = 0.18; // $0.18 per stream

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function updateStream($contentId) {
        try {
            $this->db->beginTransaction();

            // Update stream counts
            $this->db->update(
                "UPDATE content 
                SET current_play_count = current_play_count + 1,
                    total_play_count = total_play_count + 1
                WHERE id = ?",
                [$contentId]
            );

            // Check if payment threshold is reached
            $content = $this->db->fetchOne(
                "SELECT c.*, u.email, u.full_name 
                FROM content c
                JOIN users u ON c.user_id = u.id
                WHERE c.id = ?",
                [$contentId]
            );

            if ($content && $content['current_play_count'] >= $this->streamThreshold) {
                $this->processPayment($content);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error updating stream: " . $e->getMessage());
            return false;
        }
    }

    private function processPayment($content) {
        try {
            $paymentAmount = $content['current_play_count'] * $this->royaltyRate;
            $transactionHash = bin2hex(random_bytes(32));

            // Update user's account balance
            $this->db->update(
                "UPDATE accounts SET balance = balance + ? WHERE user_id = ?",
                [$paymentAmount, $content['user_id']]
            );

            // Record the transaction
            $this->db->insert(
                'INSERT INTO royalty_transactions (
                    content_id, user_id, amount, transaction_hash, 
                    blockchain_status, payment_method, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, NOW())',
                [
                    $content['id'],
                    $content['user_id'],
                    $paymentAmount,
                    $transactionHash,
                    'pending',
                    'bank_transfer'
                ]
            );

            // Reset current play count
            $this->db->update(
                'UPDATE content SET current_play_count = 0 WHERE id = ?',
                [$content['id']]
            );

            // Send notification email
            $this->sendPaymentNotification($content, $paymentAmount);

            return true;
        } catch (Exception $e) {
            error_log("Error processing payment: " . $e->getMessage());
            throw $e;
        }
    }

    private function sendPaymentNotification($content, $amount) {
        $to = $content['email'];
        $subject = "Royalty Payment Processed";
        $message = "
            Dear {$content['full_name']},

            A royalty payment of $" . number_format($amount, 2) . " has been processed for your track.
            The payment has been added to your account balance.

            Payment Details:
            - Amount: $" . number_format($amount, 2) . "
            - Date: " . date('Y-m-d H:i:s') . "
            - Payment Method: Bank Transfer
            - Streams Processed: {$content['current_play_count']}

            Thank you for being part of our platform!

            Best regards,
            LyricLedger Team
        ";

        $headers = "From: noreply@lyricledger.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        mail($to, $subject, $message, $headers);
    }
}

// Handle stream update request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentId = $_POST['content_id'] ?? null;
    
    if (!$contentId) {
        http_response_code(400);
        echo json_encode(['error' => 'Content ID is required']);
        exit;
    }

    $streamManager = new StreamManager();
    $result = $streamManager->updateStream($contentId);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update stream']);
    }
}
?> 