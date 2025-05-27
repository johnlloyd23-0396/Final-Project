<?php
include 'db_connect.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['tenant_username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $landlord_name = trim($_POST['landlord_name']);

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif ($landlord_name !== 'Lerna Buan') {
        $error_message = "Invalid landlord name. Please enter valid requirement.";
    } else {
        $check_query = "SELECT * FROM users WHERE tenant_username = '$username'";
        $result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "Username already exists.";
        } else {
            $insert_query = "INSERT INTO users (tenant_username, password) VALUES ('$username', '$password')";
            if (mysqli_query($conn, $insert_query)) {
                echo "<script>
                        alert('Registration successful! Redirecting to login page...');
                        window.location.href = 'login.php';
                      </script>";
                exit();
            } else {
                $error_message = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Blissease Apartment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .register-section {
      display: flex;
      min-height: 100vh;
    }
    .register-image {
      flex: 1;
      background: url('bg.png') center center / cover no-repeat;
    }
    .register-form-container {
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
    .btn-register {
      background-color: #7C828B;
      color: white;
    }
    .btn-register:hover {
      background-color: #636a74;
    }
  </style>
</head>
<body>

<section class="register-section">
  <div class="register-image"></div>
  <div class="register-form-container">
    <div class="form-box">
      <div class="text-center mb-4">
        <h3>Create Tenant Account</h3>
        <p class="text-muted">Join BlissEase to manage your space easily</p>
      </div>

      <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="form-floating mb-3">
          <input type="text" name="tenant_username" class="form-control" id="tenant_username" placeholder="Username" required>
          <label for="tenant_username">Username</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
          <label for="password">Password</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password" required>
          <label for="confirm_password">Confirm Password</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" name="landlord_name" class="form-control" id="landlord_name" placeholder="Landlord Name" required>
          <label for="landlord_name">Landlord Name</label>
        </div>
        <button type="submit" class="btn btn-register w-100">Register</button>
        <div class="mt-3 text-center">
          <a href="login.php" class="text-decoration-none">Already have an account? Login</a>
        </div>
      </form>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
