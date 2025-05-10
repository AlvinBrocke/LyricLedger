<?php
require_once __DIR__ . '/../classes/db.php';

class User {
    private $db;
    private $email;
    private $password;
    private $role;
    private $walletAddress;
    private $fullName;
    private $lastError;

    public function __construct($data = []) {
        $this->db = DB::getInstance();
        if (!empty($data)) {
            $this->setEmail($data['email'] ?? '');
            $this->setPassword($data['password'] ?? '');
            $this->setRole($data['role'] ?? 'user');
            $this->setWalletAddress($data['wallet_address'] ?? '');
            $this->setFullName($data['full_name'] ?? '');
        }
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
        $this->email = $email;
    }

    public function setPassword($password) {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters long');
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setRole($role) {
        $allowedRoles = ['user', 'admin', 'artist'];
        if (!in_array($role, $allowedRoles)) {
            throw new InvalidArgumentException('Invalid role. Must be one of: ' . implode(', ', $allowedRoles));
        }
        $this->role = $role;
    }

    public function setWalletAddress($address) {
        if (!empty($address) && !preg_match('/^0x[a-fA-F0-9]{40}$/', $address)) {
            throw new InvalidArgumentException('Invalid Ethereum wallet address format');
        }
        $this->walletAddress = $address;
    }

    public function setFullName($name) {
        if (strlen($name) < 2) {
            throw new InvalidArgumentException('Full name must be at least 2 characters long');
        }
        $this->fullName = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function getWalletAddress() {
        return $this->walletAddress;
    }

    public function getFullName() {
        return $this->fullName;
    }

    public function toArray() {
        return [
            'email' => $this->email,
            'role' => $this->role,
            'wallet_address' => $this->walletAddress,
            'full_name' => $this->fullName
        ];
    }

    public function createUser($data) {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO users (id, email, password_hash, role, wallet_address, full_name) 
                   VALUES (UUID(), :email, :password_hash, :role, :wallet_address, :full_name)";
            
            $result = $this->db->insert($sql, [
                'email' => $data['email'],
                'password_hash' => $data['password_hash'],
                'role' => $data['role'],
                'wallet_address' => $data['wallet_address'],
                'full_name' => $data['full_name']
            ]);

            if ($result) {
                $this->db->commit();
                return $result;
            } else {
                $this->db->rollBack();
                $this->lastError = $this->db->getLastError();
                return false;
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $result = $this->db->fetch($sql, ['email' => $email]);
        
        if ($result === false) {
            $this->lastError = $this->db->getLastError();
        }
        
        return $result;
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $result = $this->db->fetch($sql, ['id' => $id]);
        
        if ($result === false) {
            $this->lastError = $this->db->getLastError();
        }
        
        return $result;
    }

    public function getLastError() {
        return $this->lastError;
    }
}

?>