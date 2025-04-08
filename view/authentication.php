<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login / Sign-Up</title>
  <link rel="stylesheet" href="../assets/css/auth.css">
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
  <div class="auth-container">
    <div class="form-container" id="login-form">
      <div class="form-header">
        <div class="icon-box"><i class="fas fa-sign-in-alt"></i></div>
        <h2>Login</h2>
        <p>Sign into your account to secure your music copyrights and royalties</p>
      </div>
      <form>
        <input type="email" placeholder="Email" required>
        <input type="password" placeholder="Password" required>
        <span class="forgot-password">Forgot password?</span>
        <button type="submit">Sign In</button>
      </form>
      <!-- <div class="divider">Or continue with</div>
      <div class="social-login">
        <button><i class="fab fa-google"></i></button>
        <button><i class="fab fa-facebook-f"></i></button>
        <button><i class="fab fa-apple"></i></button>
      </div> -->
      <div class="switch-form">
        New user? <span onclick="toggleForm()">Create account</span>
      </div>
    </div>

    <div class="form-container hidden" id="signup-form">
      <div class="form-header">
        <div class="icon-box"><i class="fas fa-user-plus"></i></div>
        <h2>Create an Account</h2>
        <p>Start managing your music rights efficiently</p>
      </div>
      <form>
        <input type="text" placeholder="Full Name" required>
        <input type="email" placeholder="Email" required>
        <input type="password" placeholder="Password" required>
        <input type="password" placeholder="Confirm Password" required>
            <div class="input-group">
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="Artist">Artist</option>
                    <option value="Songwriter">Songwriter</option>
                    <option value="Producer">Producer</option>
                    <option value="Publisher">Publisher</option>
                    <option value="Label">Label</option>
                </select>
            </div>
        <button type="submit">Sign Up</button>
      </form>
      <!-- <div class="divider">Or continue with</div> -->
      <!-- <div class="social-login">
        <button><i class="fab fa-google"></i></button>
        <button><i class="fab fa-facebook-f"></i></button>
        <button><i class="fab fa-apple"></i></button>
      </div> -->
      <div class="switch-form">
        Already registered? <span onclick="toggleForm()">Sign in</span>
      </div>
    </div>
  </div>

  <script>
    function toggleForm() {
      document.getElementById("login-form").classList.toggle("hidden");
      document.getElementById("signup-form").classList.toggle("hidden");
    }
  </script>
</body>
</html>
