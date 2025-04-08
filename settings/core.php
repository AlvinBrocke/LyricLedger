<?php
session_start();

include __DIR__ . '/../controllers/auth_controller.php';

$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    switch ($_POST['action']) {

        case 'login':
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $response = $auth->login([
                'email' => $email,
                'password' => $password
            ]);

            if (isset($response['success'])) {
                $_SESSION['user_id'] = $response['user_id'] ?? null;
                $_SESSION['user_role'] = $response['role'] ?? null;
                header('Location: ../views/dashboard/home.php');
                exit();
            } else {
                $_SESSION['login_error'] = $response['error'];
                header('Location: ../views/auth/login.php');
                exit();
            }
            break;

        // Other actions can go here like registration, logout, etc.
        case 'register':
            $response = $auth->register($_POST);
            if (isset($response['success'])) {
                $_SESSION['registration_success'] = $response['success'];
                header('Location: ../views/auth/login.php');
                exit();
            } else {
                $_SESSION['registration_error'] = $response['error'];
                header('Location: ../views/auth/register.php');
                exit();
            }
            break;

        case 'logout':
            session_destroy();
            header('Location: ../views/auth/login.php');
            exit();
            break;

        default:
            echo "Invalid action.";
            break;
    }

} else {
    echo "Unauthorized request.";
}
