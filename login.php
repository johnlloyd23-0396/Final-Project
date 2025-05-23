<?php
session_start();
include 'db_connect.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = mysqli_real_escape_string($conn, $_POST['tenant_username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $admin_query = "SELECT * FROM admin WHERE username = '$input_username'";
    $admin_result = mysqli_query($conn, $admin_query);

    if ($admin_result && mysqli_num_rows($admin_result) == 1) {
        $admin = mysqli_fetch_assoc($admin_result);
        if ($password === $admin['password']) {
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header('Location: dashboard-admin.php');
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $user_query = "SELECT * FROM users WHERE tenant_username = '$input_username'";
        $user_result = mysqli_query($conn, $user_query);

        if ($user_result && mysqli_num_rows($user_result) == 1) {
            $user = mysqli_fetch_assoc($user_result);
            if ($password === $user['password']) {
                $_SESSION['tenant_username'] = $user['tenant_username'];
                $_SESSION['role'] = 'tenant';
                header('Location: Home Page.php');
                exit();
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Blissease Apartment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .login-section {
      display: flex;
      min-height: 100vh;
    }
    .login-image {
      flex: 1;
      background: url('bg.png') center center / cover no-repeat;
    }
    .login-form-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    .form-box {
      width: 100%;
      max-width: 400px;
    }
    .btn-login {
      background-color: #7C828B;
      color: white;
    }
    .btn-login:hover {
      background-color: #636a74;
    }
    .password-toggle {
      position: absolute;
      right: 15px;
      top: 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<section class="login-section">
  <div class="login-image"></div>
  <div class="login-form-container">
    <div class="form-box">
      <div class="text-center mb-4">
        <h3>Login to your Account</h3>
        <p class="text-muted">See What's Going On With BlissEase</p>
      </div>

      <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="form-floating mb-3 position-relative">
          <input type="text" class="form-control" name="tenant_username" id="tenant_username" placeholder="Username" required>
          <label for="tenant_username">Username</label>
        </div>
        <div class="form-floating mb-3 position-relative">
          <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
          <label for="password">Password</label>
          <span class="password-toggle" onclick="togglePassword()">👁️</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember_me">
            <label class="form-check-label" for="remember_me">Remember Me</label>
          </div>
          <a href="forgotpass.php" class="text-muted small">Forgot Password?</a>
        </div>
        <button type="submit" class="btn btn-login w-100">Login</button>

        <div class="mt-3 text-center">
          <span class="text-muted">Don't have an account?</span>
          <a href="register.php" class="btn btn-outline-secondary mt-2 w-100">Register</a>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
  function togglePassword() {
    const passwordInput = document.getElementById("password");
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
