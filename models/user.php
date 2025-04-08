<?php
require_once __DIR__ . '/../classes/DB.php';
class User {
    
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function createUser($data) {
        $sql = "INSERT INTO users (id, email, password_hash, role, wallet_address, full_name) VALUES (UUID(), :email, :password_hash, :role, :wallet_address, :full_name)";
        return $this->db->insert($sql, $data);
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->db->fetch($sql, ['email' => $email]);
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
}

?>