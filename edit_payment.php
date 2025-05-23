<?php
include "db_connect.php";

$error = '';
$showForm = false;
$payment = null;

// Check if an edit request is submitted (via GET or POST)
$id = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} elseif (isset($_POST['id'])) {
    $id = intval($_POST['id']);
}

// If form submitted to update payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $room_number = $_POST['room_number'];
    $tenant_username = $_POST['tenant_username'];
    $amount_due = $_POST['amount_due'];
    $amount_paid = $_POST['amount_paid'];
    $due_date = $_POST['due_date'];

    // Determine status based on amount_paid vs amount_due
    if ($amount_paid >= $amount_due) {
        $status = 'PAID';
    } elseif ($amount_paid > 0) {
        $status = 'PARTIALLY PAID';
    } else {
        $status = 'UNPAID';
    }

    $update_sql = "UPDATE payments SET 
                    room_number = ?, 
                    tenant_username = ?, 
                    amount_due = ?, 
                    amount_paid = ?, 
                    due_date = ?, 
                    status = ?
                  WHERE payment_id = ?";

    $stmt = mysqli_prepare($conn, $update_sql);
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ssddssi", $room_number, $tenant_username, $amount_due, $amount_paid, $due_date, $status, $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: dashboard-admin.php?msg=Tenant payment updated successfully!");
        exit();
    } else {
        $error = "Update failed: " . mysqli_error($conn);
    }
}

// If id is set and method is GET, load payment data to fill form
if ($id && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM payments WHERE payment_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $payment = mysqli_fetch_assoc($result);
        $showForm = true;
    } else {
        $error = "Payment record not found.";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('bg.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 15px;
            backdrop-filter: blur(15px);
            padding: 2rem;
            margin-top: 3rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.37);
            color: #000;
        }
        .btn-primary, .btn-secondary {
            font-weight: 600;
        }
        .form-control {
            background-color: #ffffffcc;
            border: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="glass-card">

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($showForm && $payment): ?>
            <h3 class="mb-4 text-center">Edit Tenant Payment</h3>
            <form method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($payment['payment_id']) ?>">
                <div class="mb-3">
                    <label for="room_number" class="form-label">Room Number</label>
                    <input type="text" id="room_number" name="room_number" class="form-control" required value="<?= htmlspecialchars($payment['room_number']) ?>">
                </div>
                <div class="mb-3">
                    <label for="tenant_username" class="form-label">Tenant Username</label>
                    <input type="text" id="tenant_username" name="tenant_username" class="form-control" required value="<?= htmlspecialchars($payment['tenant_username']) ?>">
                </div>
                <div class="mb-3">
                    <label for="amount_due" class="form-label">Amount Due</label>
                    <input type="number" id="amount_due" name="amount_due" class="form-control" required min="0" step="0.01" value="<?= htmlspecialchars($payment['amount_due']) ?>">
                </div>
                <div class="mb-3">
                    <label for="amount_paid" class="form-label">Amount Paid</label>
                    <input type="number" id="amount_paid" name="amount_paid" class="form-control" required min="0" step="0.01" value="<?= htmlspecialchars($payment['amount_paid']) ?>">
                </div>
                <div class="mb-3">
                    <label for="due_date" class="form-label">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="form-control" required value="<?= htmlspecialchars($payment['due_date']) ?>">
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update Payment</button>
                    <a href="dashboard-admin.php" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <h3 class="mb-4 text-center">Select Tenant to Edit</h3>
            <?php
            $sql = "SELECT * FROM payments ORDER BY room_number ASC";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                echo "<div class='alert alert-danger'>Query failed: " . mysqli_error($conn) . "</div>";
            } elseif (mysqli_num_rows($result) === 0) {
                echo "<div class='alert alert-info'>No tenants available. Please add tenants first.</div>";
            } else {
                echo '<table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Tenant</th>
                            <th>Amount Due</th>
                            <th>Amount Paid</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    $statusClass = strtolower(str_replace(' ', '-', $row['status']));
                    echo "<tr>
                        <td>" . htmlspecialchars($row['room_number']) . "</td>
                        <td>" . htmlspecialchars($row['tenant_username']) . "</td>
                        <td>" . number_format($row['amount_due'], 2) . "</td>
                        <td>" . number_format($row['amount_paid'], 2) . "</td>
                        <td>" . htmlspecialchars($row['due_date']) . "</td>
                        <td class='status {$statusClass}'>" . htmlspecialchars($row['status']) . "</td>
                        <td>
                            <a href='edit_payment.php?id=" . $row['payment_id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                        </td>
                    </tr>";
                }
                echo '</tbody></table>';
            }
            ?>
            <div class="text-center mt-3">
                <a href="dashboard-admin.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
