<?php

require_once __DIR__ . '/../classes/db.php';

class RoyaltyTransaction {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function recordTransaction($data) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // Insert into MySQL database
            $sql = "INSERT INTO royalty_transactions (id, content_id, user_id, amount, transaction_hash, blockchain_status, payment_method) VALUES (UUID(), :content_id, :user_id, :amount, :transaction_hash, :blockchain_status, :payment_method)";
            $result = $this->db->insert($sql, $data);

            if (!$result) {
                throw new Exception("Failed to insert transaction into database");
            }

            // Get the inserted transaction ID
            $transaction_id = $this->db->lastInsertId();

            // Prepare data for blockchain
            $blockchain_data = [
                'transaction_id' => $transaction_id,
                'content_id' => $data['content_id'],
                'user_id' => $data['user_id'],
                'amount' => $data['amount'],
                'timestamp' => date('c') // ISO 8601 format
            ];

            // Call Python script to record transaction on blockchain
            $command = sprintf(
                'python3 %s/blockchain/fabric_service.py record_transaction %s',
                __DIR__ . '/..',
                escapeshellarg(json_encode($blockchain_data))
            );

            $output = [];
            $return_var = 0;
            exec($command, $output, $return_var);

            if ($return_var !== 0) {
                throw new Exception("Failed to record transaction on blockchain");
            }

            // Parse blockchain response
            $blockchain_response = json_decode($output[0], true);

            if (!$blockchain_response['success']) {
                throw new Exception("Blockchain transaction failed: " . $blockchain_response['error']);
            }

            // Update transaction with blockchain details
            $update_sql = "UPDATE royalty_transactions SET transaction_hash = :transaction_hash, blockchain_status = :blockchain_status WHERE id = :id";
            $this->db->update($update_sql, [
                'id' => $transaction_id,
                'transaction_hash' => $blockchain_response['transaction_id'],
                'blockchain_status' => 'confirmed'
            ]);

            // Commit transaction
            $this->db->commit();

            return $transaction_id;

        } catch (Exception $e) {
            // Rollback transaction
            $this->db->rollback();
            throw $e;
        }
    }

    public function getTransactionsByUser($userId) {
        try {
            // Get transactions from database
            $sql = "SELECT * FROM royalty_transactions WHERE user_id = :user_id ORDER BY created_at DESC";
            $transactions = $this->db->fetchAll($sql, ['user_id' => $userId]);

            // Get blockchain details for each transaction
            foreach ($transactions as &$transaction) {
                if ($transaction['transaction_hash']) {
                    $command = sprintf(
                        'python3 %s/blockchain/fabric_service.py get_transaction %s',
                        __DIR__ . '/..',
                        escapeshellarg($transaction['transaction_hash'])
                    );

                    $output = [];
                    $return_var = 0;
                    exec($command, $output, $return_var);

                    if ($return_var === 0) {
                        $blockchain_data = json_decode($output[0], true);
                        if ($blockchain_data['success']) {
                            $transaction['blockchain_details'] = $blockchain_data['transaction'];
                        }
                    }
                }
            }

            return $transactions;

        } catch (Exception $e) {
            throw new Exception("Failed to get transactions: " . $e->getMessage());
        }
    }

    public function getTransactionDetails($transactionId) {
        try {
            // Get transaction from database
            $sql = "SELECT * FROM royalty_transactions WHERE id = :id";
            $transaction = $this->db->fetch($sql, ['id' => $transactionId]);

            if (!$transaction) {
                throw new Exception("Transaction not found");
            }

            // Get blockchain details if available
            if ($transaction['transaction_hash']) {
                $command = sprintf(
                    'python3 %s/blockchain/fabric_service.py get_transaction %s',
                    __DIR__ . '/..',
                    escapeshellarg($transaction['transaction_hash'])
                );

                $output = [];
                $return_var = 0;
                exec($command, $output, $return_var);

                if ($return_var === 0) {
                    $blockchain_data = json_decode($output[0], true);
                    if ($blockchain_data['success']) {
                        $transaction['blockchain_details'] = $blockchain_data['transaction'];
                    }
                }
            }

            return $transaction;

        } catch (Exception $e) {
            throw new Exception("Failed to get transaction details: " . $e->getMessage());
        }
    }
}

