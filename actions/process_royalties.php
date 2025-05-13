<?php
require_once __DIR__ . '/../classes/db.php';
require_once __DIR__ . '/../classes/db_connection.php';
session_start();

// Enable error reporting for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class RoyaltyProcessor {
    private $db;
    private $playcountthreshold = 10000;
    private $royaltyRate = 0.18;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function processPayments() {
        try {
            $this->db->beginTransaction();

            // Get eligible tracks
            $accountsToPay = $this->getEligibleTracks();
            
            if (empty($accountsToPay)) {
                error_log("No tracks eligible for payment");
                $this->db->commit();
                return true;
            }

            foreach ($accountsToPay as $account) {
                $this->processTrackPayment($account);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error processing payments: " . $e->getMessage());
            return false;
        }
    }

    private function getEligibleTracks() {
        return $this->db->fetchAll(
            "SELECT c.id AS content_id, c.user_id, c.current_play_count, u.email, u.full_name
             FROM content c
             JOIN users u ON c.user_id = u.id
             WHERE c.current_play_count > :playcountthreshold",
            ['playcountthreshold' => $this->playcountthreshold]
        );
    }

    private function processTrackPayment($account) {
        try {
            // Validate account data
            if (!isset($account['content_id']) || !isset($account['user_id']) || !isset($account['current_play_count'])) {
                throw new Exception("Invalid account data for payment processing");
            }

            $paymentAmount = $account['current_play_count'] * $this->royaltyRate;
            $transactionHash = bin2hex(random_bytes(32));

            // Update user's account balance
            $this->db->update(
                "UPDATE accounts SET balance = balance + ? WHERE user_id = ?",
                [$paymentAmount, $account['user_id']]
            );

            // Record the transaction
            $this->db->insert(
                'INSERT INTO royalty_transactions (
                    content_id, user_id, amount, transaction_hash, 
                    blockchain_status, payment_method, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, NOW())',
                [
                    $account['content_id'],
                    $account['user_id'],
                    $paymentAmount,
                    $transactionHash,
                    'pending',
                    'bank_transfer'
                ]
            );

            // Reset play count
            $this->db->update(
                'UPDATE content SET current_play_count = 0 WHERE id = ?',
                [$account['content_id']]
            );

            // Send notification email
            $this->sendPaymentNotification($account, $paymentAmount);

            return true;
        } catch (Exception $e) {
            error_log("Error processing payment for track {$account['content_id']}: " . $e->getMessage());
            throw $e;
        }
    }

    private function sendPaymentNotification($account, $amount) {
        $to = $account['email'];
        $subject = "Royalty Payment Processed";
        $message = "
            Dear {$account['full_name']},

            A royalty payment of $" . number_format($amount, 2) . " has been processed for your track.
            The payment has been added to your account balance.

            Payment Details:
            - Amount: $" . number_format($amount, 2) . "
            - Date: " . date('Y-m-d H:i:s') . "
            - Payment Method: Bank Transfer

            Thank you for being part of our platform!

            Best regards,
            LyricLedger Team
        ";

        $headers = "From: noreply@lyricledger.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        mail($to, $subject, $message, $headers);
    }
}

// Create a cron job entry point
if (php_sapi_name() === 'cli') {
    $processor = new RoyaltyProcessor();
    $processor->processPayments();
}
?>