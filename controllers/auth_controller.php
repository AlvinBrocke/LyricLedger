<?php
// controllers/auth_controller.php
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../classes/helper.php';

class AuthController {
    private $user_model;

    public function __construct() {
        $this->user_model = new User();
    }

    public function register($data) {
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $role = $data['role'] ?? '';
        $wallet = $data['wallet_address'] ?? null;
        $full_name = $data['full_name'] ?? null;

        if (!$email || !$password || !$role) {
            return ['error' => 'Required fields are missing.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Invalid email address.'];
        }

        $existing = $this->user_model->getUserByEmail($email);
        if ($existing) {
            return ['error' => 'Email is already registered.'];
        }

        $hashed_password = Helper::hashPassword($password);

        $created = $this->user_model->createUser([
            'email' => $email,
            'password_hash' => $hashed_password,
            'role' => $role,
            'wallet_address' => $wallet,
            'full_name' => $full_name
        ]);

        if ($created) {
            return ['success' => 'Account created successfully.'];
        } else {
            return ['error' => 'Registration failed. Please try again.'];
        }
    }

    public function login($data) {
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            return ['error' => 'Both email and password are required.'];
        }

        $user = $this->user_model->getUserByEmail($email);

        if (!$user || !Helper::verifyPassword($password, $user['password_hash'])) {
            return ['error' => 'Invalid email or password.'];
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        return [
            'success' => 'Login successful.',
            'user_id' => $user['id'],
            'role' => $user['role']
        ];
    }
}
