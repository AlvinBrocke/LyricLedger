<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Sign-Up</title>
    <link rel="stylesheet" href="../../assets/css/auth.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
    <div class="auth-container">
        <!-- Login Form -->
        <div class="form-container" id="login-form">
            <div class="form-header">
                <div class="icon-box"><i class="fas fa-sign-in-alt"></i></div>
                <h2>Login</h2>
                <p>Sign into your account to secure your music copyrights and royalties</p>
            </div>
            <form action="../../settings/core.php" method="POST">
                <input type="hidden" name="action" value="login">

                <?php if (isset($_SESSION['login_error'])): ?>
                    <p class="error"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
                <?php endif; ?>

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <span class="forgot-password">Forgot password?</span>
                <button type="submit">Sign In</button>
            </form>
        </div>
        
        <div class="switch-form">
            Already registered? <span onclick="registerForm()">Sign in</span>
        </div>
    </div>
    </div>

    <script>
        function registerForm() {
            window.location.href = 'register.php';
        }
    </script>
</body>

</html>