<?php
session_start();
include "db_connect.php";

// Ensure ID is valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard-admin.php?msg=" . urlencode("Invalid tenant payment ID."));
    exit;
}

$payment_id = intval($_GET['id']);
$action = $_GET['action'] ?? null;

// Step 1: Fetch payment info
$query = "SELECT tenant_username FROM payments WHERE payment_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $payment_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$payment = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// If no such payment
if (!$payment) {
    header("Location: dashboard-admin.php?msg=" . urlencode("Payment record not found."));
    exit;
}

$tenant_username = $payment['tenant_username'];

// Step 2: Perform deletion if action is confirmed
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['confirm'] === 'payment') {
        // Delete payment only
        $stmt = mysqli_prepare($conn, "DELETE FROM payments WHERE payment_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $payment_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: dashboard-admin.php?msg=" . urlencode("✅ Payment deleted successfully."));
        exit;

    } elseif ($_POST['confirm'] === 'both') {
        // Delete payment
        $stmt = mysqli_prepare($conn, "DELETE FROM payments WHERE payment_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $payment_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Delete user
        $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE tenant_username = ?");
        mysqli_stmt_bind_param($stmt, "s", $tenant_username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: dashboard-admin.php?msg=" . urlencode("✅ Payment and user deleted."));
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Delete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('bg.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 15px;
            backdrop-filter: blur(15px);
            padding: 2rem;
            margin-top: 5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            color: #000;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="glass-card text-center">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete payment for tenant <strong><?= htmlspecialchars($tenant_username) ?></strong>?</p>

        <form method="post">
            <button type="submit" name="confirm" value="payment" class="btn btn-danger me-2">Delete Payment Only</button>
            <button type="submit" name="confirm" value="both" class="btn btn-warning me-2">Delete Payment and User</button>
            <a href="dashboard-admin.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
