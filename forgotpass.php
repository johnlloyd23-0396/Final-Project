<?php
session_start();
include 'db_connect.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim and escape inputs
    $username = trim($_POST['tenant_username']);
    $room_number = trim($_POST['room_number']);
    $admin_name = trim($_POST['admin_name']);

    // Prepare statement for users table
    $stmt_user = $conn->prepare("SELECT * FROM users WHERE tenant_username = ?");
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();

    if ($user_result->num_rows > 0) {
        // Prepare statement for payments table
        $stmt_payment = $conn->prepare("SELECT * FROM payments WHERE tenant_username = ? AND room_number = ?");
        $stmt_payment->bind_param("ss", $username, $room_number);
        $stmt_payment->execute();
        $payment_result = $stmt_payment->get_result();

        if ($payment_result->num_rows > 0) {
            // Prepare statement for admin table
            $stmt_admin = $conn->prepare("SELECT * FROM admin WHERE admin_name = ?");
            $stmt_admin->bind_param("s", $admin_name);
            $stmt_admin->execute();
            $admin_result = $stmt_admin->get_result();

            if ($admin_result->num_rows > 0) {
                // Verified, redirect to reset password page with username
                header("Location: reset_pass.php?tenant_username=" . urlencode($username));
                exit();
            } else {
                $error_message = "Admin name not found.";
            }
            $stmt_admin->close();
        } else {
            $error_message = "Room number does not match our records.";
        }
        $stmt_payment->close();
    } else {
        $error_message = "Tenant username not found.";
    }
    $stmt_user->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Forgot Password | Blissease Apartment</title>
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
  <h4 class="text-center mb-3">Forgot Password</h4>
  <p class="text-muted text-center mb-4">Enter your tenant username, room number, and admin name to verify</p>

  <?php if (!empty($error_message)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="form-floating mb-3">
      <input
        type="text"
        name="tenant_username"
        class="form-control"
        id="tenant_username"
        placeholder="Tenant Username"
        required
        value="<?= isset($_POST['tenant_username']) ? htmlspecialchars($_POST['tenant_username']) : '' ?>"
      />
      <label for="tenant_username">Tenant Username</label>
    </div>
    <div class="form-floating mb-3">
      <input
        type="text"
        name="room_number"
        class="form-control"
        id="room_number"
        placeholder="Room Number"
        required
        value="<?= isset($_POST['room_number']) ? htmlspecialchars($_POST['room_number']) : '' ?>"
      />
      <label for="room_number">Room Number</label>
    </div>
    <div class="form-floating mb-3">
      <input
        type="text"
        name="admin_name"
        class="form-control"
        id="admin_name"
        placeholder="Admin Name"
        required
        value="<?= isset($_POST['admin_name']) ? htmlspecialchars($_POST['admin_name']) : '' ?>"
      />
      <label for="admin_name">Admin Name</label>
    </div>
    <button type="submit" class="btn btn-reset w-100">Verify</button>
    <div class="text-center mt-3">
      <a href="login.php" class="text-decoration-none">Back to Login</a>
    </div>
  </form>
</div>
</body>
</html>
