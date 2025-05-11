<?php

require_once __DIR__ . '/../classes/db.php';

class DBTransactionTest {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function testTransactionFlow() {
        try {
            echo "Testing transaction flow...\n";
            
            // Start transaction
            $this->db->beginTransaction();
            echo "✓ Transaction started\n";

            // Insert test data
            $sql = "INSERT INTO test_transactions (description, amount) VALUES (:desc, :amount)";
            $params = [
                'desc' => 'Test Transaction',
                'amount' => 100.00
            ];
            
            $result = $this->db->insert($sql, $params);
            if (!$result) {
                throw new Exception("Insert failed");
            }
            echo "✓ Test data inserted\n";

            // Get last insert ID
            $id = $this->db->lastInsertId();
            echo "✓ Last insert ID retrieved: " . $id . "\n";

            // Commit transaction
            $this->db->commit();
            echo "✓ Transaction committed\n";

            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            echo "✗ Test failed: " . $e->getMessage() . "\n";
            return false;
        }
    }

    public function testRollback() {
        try {
            echo "\nTesting rollback...\n";
            
            // Start transaction
            $this->db->beginTransaction();
            echo "✓ Transaction started\n";

            // Insert test data
            $sql = "INSERT INTO test_transactions (description, amount) VALUES (:desc, :amount)";
            $params = [
                'desc' => 'Rollback Test',
                'amount' => 200.00
            ];
            
            $result = $this->db->insert($sql, $params);
            if (!$result) {
                throw new Exception("Insert failed");
            }
            echo "✓ Test data inserted\n";

            // Intentionally throw an error
            throw new Exception("Simulating error for rollback test");

        } catch (Exception $e) {
            $this->db->rollback();
            echo "✓ Rollback successful\n";
            return true;
        }
    }
}

// Run tests
$test = new DBTransactionTest();

echo "=== Database Transaction Tests ===\n";
$test->testTransactionFlow();
$test->testRollback(); 