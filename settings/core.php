<?php
session_start();



try {
    // Correct case-sensitive file path
    // require_once __DIR__ . '/../controllers/AuthController.php';
    require_once '../controllers/auth_controller.php';

    $auth = new AuthController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

        switch ($_POST['action']) {

            case 'login':
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                // Input validation
                if (empty($email) || empty($password)) {
                    $_SESSION['login_error'] = 'Email and password are required.';
                    header('Location: ../views/auth/login.php');
                    exit();
                }

                // Controller logic wrapped in try-catch
                try {
                    $response = $auth->login([
                        'email' => $email,
                        'password' => $password
                    ]);

                    if (isset($response['success'])) {
                        $_SESSION['user_id'] = $response['user_id'] ?? null;
                        $_SESSION['user_role'] = $response['role'] ?? null;
                        $_SESSION['full_name'] = $response['full_name'] ?? null;
                        if ($_SESSION['user_role'] === 'admin') {
                            header('Location: ../views/dashboard/admin/home.php');
                        } else {
                            header('Location: ../views/dashboard/user/home.php');
                        }
                        exit();
                    } else {
                        $_SESSION['login_error'] = $response['error'] ?? 'Login failed.';
                        header('Location: ../views/auth/login.php');
                        exit();
                    }
                } catch (Exception $e) {
                    error_log('Login Exception: ' . $e->getMessage());
                    $_SESSION['login_error'] = 'An error occurred during login.';
                    header('Location: ../views/auth/login.php');
                    exit();
                }

                break;

            case 'register':
                try {
                    $response = $auth->register($_POST);

                    if (isset($response['success'])) {
                        $_SESSION['registration_success'] = $response['success'];
                        header('Location: ../views/auth/login.php');
                        exit();
                    } else {
                        $_SESSION['registration_error'] = $response['error'] ?? 'Registration failed.';
                        header('Location: ../views/auth/register.php');
                        exit();
                    }
                } catch (Exception $e) {
                    error_log('Registration Exception: ' . $e->getMessage());
                    $_SESSION['registration_error'] = 'An error occurred during registration.';
                    // header('Location: ../views/auth/register.php');
                    echo 'this is an excpetion' . $e->getMessage();
                    exit();
                }
                break;

            case 'logout':
                session_unset();
                session_destroy();
                header('Location: ../views/auth/login.php');
                exit();

            default:
                http_response_code(400);
                echo 'Invalid action provided.';
                break;
        }

    } else {
        http_response_code(403);
        echo 'Unauthorized or malformed request.';
    }

} catch (Throwable $e) {
    // Global fail-safe error handler
    error_log('Global Error: ' . $e->getMessage());
    echo('Global Error: ' . $e->getMessage());
    http_response_code(500);
    echo 'A server error occurred. Please try again later.';
}
