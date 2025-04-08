<?php
// controllers/user_controller.php
require_once __DIR__ . '/../models/user.php';

class UserController {
    private $user;
    public function __construct() { $this->user = new User(); }

    public function profile($id) {
        return $this->user->getUserById($id);
    }
}