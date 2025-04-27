<?php
session_start();
$success = $_SESSION['register_success'] ?? null;
$error = $_SESSION['register_error'] ?? null;
unset($_SESSION['register_success'], $_SESSION['register_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create an Account</title>
  <link rel="stylesheet" href="../../assets/css/auth.css">
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="auth-container">
    <div class="form-container" id="signup-form">
      <div class="form-header">
        <div class="icon-box"><i class="fas fa-user-plus"></i></div>
        <h2>Create an Account</h2>
        <p>Start managing your music rights efficiently</p>
      </div>

      <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form id="registerForm" action="../../settings/core.php" method="POST">
        <input type="hidden" name="action" value="register">
        <input type="text" name="full_name" id="full_name" placeholder="Full Name" required>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <input type="password" id="confirm_password" placeholder="Confirm Password" required>

        <div class="input-group">
          <select id="role" name="role" required>
            <option value="">Select Role</option>
            <option value="artist">Artist</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
          </select>
        </div>

        <button type="submit">Sign Up</button>
      </form>

      <div class="switch-form">
        Already registered? <span onclick="loginForm()">Sign in</span>
      </div>
    </div>
  </div>

  <script>
    // Simple client-side validation
    document.getElementById("registerForm").addEventListener("submit", function (e) {
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm_password").value;

      if (password !== confirmPassword) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Passwords do not match!',
          text: 'Please make sure both passwords match.',
          background: '#1e1e1e',
          color: '#fff',
        });
      }
    });

    function loginForm() {
      window.location.href = 'login.php';
    }

    // Show success alert if registered
    <?php if ($success): ?>
    Swal.fire({
      icon: 'success',
      title: 'Account created!',
      text: 'You can now log in to your account.',
      background: '#1e1e1e',
      color: '#fff',
    });
    <?php endif; ?>
  </script>
</body>
</html>
