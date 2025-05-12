<?php
require_once __DIR__ . '/../../../classes/db.php';
session_start();

// Check if user is logged in and is an admin


// Get database connection
$db = DB::getInstance();

// Handle user deletion
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $userId = $_GET['delete'];
    try {
        // First check if the user exists
        $user = $db->fetch("SELECT id FROM users WHERE id = :id", ['id' => $userId]);
        if ($user) {
            // Delete the user
            $result = $db->delete("DELETE FROM users WHERE id = :id", ['id' => $userId]);
            if ($result) {
                $_SESSION['success'] = "User successfully removed from the system.";
            } else {
                $_SESSION['error'] = "Failed to remove user. Please try again.";
            }
        } else {
            $_SESSION['error'] = "User not found.";
        }
    } catch (Exception $e) {
        error_log("Error deleting user: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred while removing the user.";
    }
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all users
try {
    $users = $db->fetchAll("SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC");
} catch (Exception $e) {
    error_log("Error fetching users: " . $e->getMessage());
    $users = [];
    $error = "Failed to load users. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - LyricLedger</title>
    <link rel="stylesheet" href="../../../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/users.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php require_once '../../includes/sidebar.php'; sidebar(); ?>

        <div class="main-content">
            <div class="header">
                <h1>User Management</h1>
                <div class="search-date">
                    <div class="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search users...">
                    </div>
                    <div class="date"><?= date('l, F j') ?></div>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (empty($users)): ?>
                <div class="empty-state">
                    <i class="fas fa-users fa-3x"></i>
                    <p>No users found in the system.</p>
                </div>
            <?php else: ?>
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Registered Users</div>
                    </div>
                    <div class="registration-list">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <i class="fas fa-user-circle"></i>
                                                <?= htmlspecialchars($user['full_name']) ?>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="badge role-<?= strtolower($user['role']) ?>">
                                                <i class="fas fa-<?= strtolower($user['role']) === 'admin' ? 'shield-alt' : (strtolower($user['role']) === 'artist' ? 'music' : 'user') ?>"></i>
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td class="action-column">
                                            <a href="?delete=<?= htmlspecialchars($user['id']) ?>" 
                                               class="btn revoke"
                                               onclick="return confirm('Are you sure you want to remove this user? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i> Revoke
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>