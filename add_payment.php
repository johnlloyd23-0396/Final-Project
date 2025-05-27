<?php
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $room = $_POST['room_number'];
    $tenant = $_POST['tenant_username'];
    $amount_due = floatval($_POST['amount_due']);
    $amount_paid = floatval($_POST['amount_paid']);
    $due_date = $_POST['due_date'];

    // Auto-determine status
    if ($amount_paid <= 0) {
        $status = "UNPAID";
    } elseif ($amount_paid < $amount_due) {
        $status = "PARTIALLY PAID";
    } else {
        $status = "PAID";
    }

    $sql = "INSERT INTO payments (room_number, tenant_username, amount_due, amount_paid, due_date, status)
            VALUES ('$room', '$tenant', '$amount_due', '$amount_paid', '$due_date', '$status')";

    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard-admin.php?msg=Tenant added successfully!");
        exit;
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Payment</title>
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
            margin-top: 50px;
            color: #000;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="glass-card">
        <h2 class="mb-4">Add Tenant Payment</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Room Number</label>
                <input type="text" name="room_number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tenant Username</label>
                <input type="text" name="tenant_username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Amount Due</label>
                <input type="number" step="0.01" name="amount_due" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Amount Paid</label>
                <input type="number" step="0.01" name="amount_paid" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>

            <!-- Optional: show status that will be auto-computed -->
            <!-- <div class="mb-3">
                <label class="form-label">Status (auto-calculated)</label>
                <input type="text" class="form-control" value="Will be set based on payment" readonly>
            </div> -->

            <button type="submit" class="btn btn-primary">Add Payment</button>
            <a href="dashboard-admin.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
</body>
</html>
