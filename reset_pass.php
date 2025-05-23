<?php
session_start();
include 'db_connect.php';

$error_message = "";
$success_message = "";

// Get username from URL query string
if (!isset($_GET['tenant_username']) || empty($_GET['tenant_username'])) {
    die("Invalid access.");
}
$username = $_GET['tenant_username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($new_password) || empty($confirm_password)) {
        $error_message = "Please fill in both password fields.";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Update password in users table WITHOUT hashing
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE tenant_username = ?");
        $stmt->bind_param("ss", $new_password, $username);

        if ($stmt->execute()) {
            $success_message = "Password updated successfully. Redirecting to login...";
            // Redirect after 3 seconds
            header("refresh:3;url=login.php");
        } else {
            $error_message = "Failed to update password. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Reset Password | Blissease Apartment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    .form-box {
      width: 100%;
      max-width: 400px;
      background: white;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .btn-reset {
      background-color: #7C828B;
      color: white;
    }
    .btn-reset:hover {
      background-color: #636a74;
    }
  </style>
</head>
<body>
<div class="form-box">
  <h4 class="text-center mb-3">Reset Password</h4>
  <p class="text-muted text-center mb-4">Set your new password for username: <strong><?= htmlspecialchars($username) ?></strong></p>

  <?php if (!empty($error_message)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
  <?php elseif (!empty($success_message)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
  <?php endif; ?>

  <?php if (empty($success_message)): ?>
  <form method="POST">
    <div class="form-floating mb-3">
      <input
        type="password"
        name="new_password"
        class="form-control"
        id="new_password"
        placeholder="New Password"
        required
      />
      <label for="new_password">New Password</label>
    </div>
    <div class="form-floating mb-3">
      <input
        type="password"
        name="confirm_password"
        class="form-control"
        id="confirm_password"
        placeholder="Confirm Password"
        required
      />
      <label for="confirm_password">Confirm Password</label>
    </div>
    <button type="submit" class="btn btn-reset w-100">Set New Password</button>
  </form>
  <?php endif; ?>
</div>
</body>
</html>
