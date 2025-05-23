<?php
include "db_connect.php";

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$payment_id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM payments WHERE payment_id = $payment_id");

if (!$result || mysqli_num_rows($result) === 0) {
    die("Payment record not found.");
}

$data = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenant = $_POST['tenant'];
    $amount_due = $_POST['amount_due'];
    $amount_paid = $_POST['amount_paid'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $update = "UPDATE payments SET 
                tenant_username = '$tenant', 
                amount_due = $amount_due,
                amount_paid = $amount_paid,
                due_date = '$due_date',
                status = '$status'
              WHERE payment_id = $payment_id";

    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Payment updated successfully'); window.location.href = 'dashboard-admin.php';</script>";
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script>
        function cancelUpdate() {
            window.location.href = "dashboard-admin.php";
        }
    </script>
</head>
<body class="bg-light p-4">
<div class="container bg-white p-5 rounded shadow">
    <h2>Edit Payment - Room <?= htmlspecialchars($data['room_number']) ?></h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Tenant Name</label>
            <input type="text" name="tenant" class="form-control" value="<?= htmlspecialchars($data['tenant_username']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Amount to Pay</label>
            <input type="number" name="amount_due" class="form-control" value="<?= $data['amount_due'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Amount Paid</label>
            <input type="number" name="amount_paid" class="form-control" value="<?= $data['amount_paid'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="<?= $data['due_date'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="UNPAID" <?= $data['status'] == 'UNPAID' ? 'selected' : '' ?>>UNPAID</option>
                <option value="PAID" <?= $data['status'] == 'PAID' ? 'selected' : '' ?>>PAID</option>
                <option value="PARTIALLY PAID" <?= $data['status'] == 'PARTIALLY PAID' ? 'selected' : '' ?>>PARTIALLY PAID</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Payment</button>
        <button type="button" class="btn btn-secondary" onclick="cancelUpdate()">Cancel</button>
    </form>
</div>
</body>
</html>
