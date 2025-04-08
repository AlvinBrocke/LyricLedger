<?php

require_once __DIR__ . '/../classes/db.php';

class RoyaltyTransaction {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function recordTransaction($data) {
        $sql = "INSERT INTO royalty_transactions (id, content_id, user_id, amount, transaction_hash, blockchain_status, payment_method) VALUES (UUID(), :content_id, :user_id, :amount, :transaction_hash, :blockchain_status, :payment_method)";
        return $this->db->insert($sql, $data);
    }

    public function getTransactionsByUser($userId) {
        $sql = "SELECT * FROM royalty_transactions WHERE user_id = :user_id";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
}

