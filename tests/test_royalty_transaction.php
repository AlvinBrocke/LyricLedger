<?php

require_once __DIR__ . '/../models/royalty_transaction.php';

class RoyaltyTransactionTest {
    private $model;

    public function __construct() {
        $this->model = new RoyaltyTransaction();
    }

    public function testRecordTransaction() {
        try {
            echo "Testing transaction recording...\n";
            
            $data = [
                'content_id' => 'test-content-' . uniqid(),
                'user_id' => 'test-user-' . uniqid(),
                'amount' => 150.00,
                'transaction_hash' => null,
                'blockchain_status' => 'pending',
                'payment_method' => 'crypto'
            ];

            $transaction_id = $this->model->recordTransaction($data);
            echo "✓ Transaction recorded successfully. ID: " . $transaction_id . "\n";

            // Verify transaction was recorded
            $transaction = $this->model->getTransactionDetails($transaction_id);
            if ($transaction) {
                echo "✓ Transaction details retrieved successfully\n";
                echo "  Amount: " . $transaction['amount'] . "\n";
                echo "  Status: " . $transaction['blockchain_status'] . "\n";
            }

            return true;
        } catch (Exception $e) {
            echo "✗ Test failed: " . $e->getMessage() . "\n";
            return false;
        }
    }

    public function testGetUserTransactions() {
        try {
            echo "\nTesting user transaction history...\n";
            
            $user_id = 'test-user-' . uniqid();
            
            // Record a test transaction
            $data = [
                'content_id' => 'test-content-' . uniqid(),
                'user_id' => $user_id,
                'amount' => 200.00,
                'transaction_hash' => null,
                'blockchain_status' => 'pending',
                'payment_method' => 'crypto'
            ];
            
            $this->model->recordTransaction($data);
            
            // Get user transactions
            $transactions = $this->model->getTransactionsByUser($user_id);
            if (count($transactions) > 0) {
                echo "✓ User transactions retrieved successfully\n";
                echo "  Number of transactions: " . count($transactions) . "\n";
            }

            return true;
        } catch (Exception $e) {
            echo "✗ Test failed: " . $e->getMessage() . "\n";
            return false;
        }
    }
}

// Run tests
$test = new RoyaltyTransactionTest();

echo "=== Royalty Transaction Tests ===\n";
$test->testRecordTransaction();
$test->testGetUserTransactions(); 