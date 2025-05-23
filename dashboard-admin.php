<?php
session_start();
include "db_connect.php";

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Count tenants/payments safely
$checkTenants = mysqli_query($conn, "SELECT COUNT(*) as total FROM payments");
$rowCount = 0;
if ($checkTenants) {
    $rowCount = mysqli_fetch_assoc($checkTenants)['total'] ?? 0;
}

// Notifications
$date = date('Y-m-d');

$notifCount = 0;
$notifCountResult = mysqli_query($conn, "SELECT COUNT(*) as unseen FROM users WHERE is_seen = 0");
if ($notifCountResult) {
    $notifCount = mysqli_fetch_assoc($notifCountResult)['unseen'] ?? 0;
}

$notifQuery = mysqli_query($conn, "SELECT tenant_username, created_at FROM users WHERE DATE(created_at) = '$date' ORDER BY created_at DESC");
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Blissease Apartment - Payment Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
            box-shadow: 0 8px 32px rgba(0,0,0,0.37);
            border: 1px solid rgba(255,255,255,0.18);
            color: #000;
        }
        .table {
            background-color: rgba(255, 255, 255, 0.65);
            color: #000;
        }
        .table th {
            background-color: rgba(255, 255, 255, 0.9);
            color: #000;
        }
        .navbar-custom {
            background-color: rgba(0,0,0,0.5);
            padding: 1rem 2rem;
            color: #fff;
            font-size: 1.5rem;
        }
        .status.paid { color: #28a745; font-weight: bold; }
        .status.partially-paid { color: #ffc107; font-weight: bold; }
        .status.unpaid { color: #dc3545; font-weight: bold; }
        a.btn-add {
            background-color: #ffffffdd;
            backdrop-filter: blur(8px);
            border: none;
            color: #000;
            font-weight: 500;
        }
        a.btn-add:hover, button.btn-add:hover {
            background-color: #ffffffee;
        }
        .alert {
            backdrop-filter: blur(5px);
            color: #000;
        }
        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }
        .notif-button {
            position: relative;
        }
        .notif-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 3px 6px;
            font-size: 0.75rem;
        }
        .btn-notif {
            background-color: #0d6efd;
            color: #fff;
            border: none;
            font-weight: 600;
            backdrop-filter: none;
        }
        .btn-notif:hover {
            background-color: #0b5ed7;
            color: #fff;
        }
    </style>
</head>
<body>

<?php if (!empty($msg)): ?>
<script>alert("<?= htmlspecialchars($msg, ENT_QUOTES) ?>");</script>
<?php endif; ?>

<script>
    function confirmLogout() {
        return confirm("Are you sure you want to log out?");
    }
</script>

<nav class="navbar navbar-custom">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <span><i class="fas fa-building"></i> BLISSEASE APARTMENT</span>
        <div class="d-flex align-items-center">
            <a href="add_payment.php" class="btn btn-add me-3">Add Tenant</a>
            <?php if ($rowCount > 0): ?>
                <a href="edit_payment.php" class="btn btn-add me-3">Edit Payment</a>
            <?php else: ?>
                <button onclick="alert('⚠️ No available tenants to edit.');" class="btn btn-add me-3">Edit Payment</button>
            <?php endif; ?>
            <a href="search.php" class="btn btn-add me-3">Search</a>

            <!-- Notification dropdown -->
            <div class="dropdown me-3 notif-button">
                <button class="btn btn-notif dropdown-toggle" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Notifications
                    <?php if ($notifCount > 0): ?>
                        <span class="notif-count"><?= (int)$notifCount ?></span>
                    <?php endif; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end p-2 shadow" aria-labelledby="notifDropdown">
                    <li><strong>New Registrations Today:</strong></li>
                    <hr class="my-1" />
                    <?php
                    if ($notifQuery && mysqli_num_rows($notifQuery) > 0) {
                        while ($n = mysqli_fetch_assoc($notifQuery)) {
                            echo "<li><small><i class='fa fa-user'></i> " . htmlspecialchars($n['tenant_username']) . " at " . date("h:i A", strtotime($n['created_at'])) . "</small></li>";
                        }
                    } else {
                        echo "<li><small>No new users today.</small></li>";
                    }
                    ?>
                    <hr class="my-1" />
                </ul>
            </div>

            <a href="logout.php" class="btn btn-add" onclick="return confirmLogout();">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="glass-card">
        <?php
        $sql = "SELECT * FROM payments ORDER BY room_number ASC";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo "<div class='alert alert-danger'>Query Failed: " . htmlspecialchars(mysqli_error($conn)) . "</div>";
        } elseif (mysqli_num_rows($result) === 0) {
            echo "<div class='alert alert-info'>No tenant records available. Click 'Add Payment' to get started.</div>";
        } else {
            echo '<table class="table text-center table-bordered mb-3">
                    <thead class="table-light">
                        <tr>
                            <th>Room</th>
                            <th>Tenant</th>
                            <th>Amount to Pay</th>
                            <th>Amount Paid</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';

            $total = 0;
            $paid = $unpaid = $half = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                $status = strtoupper($row['status']);
                $total += $row['amount_paid'];

                if ($status === 'PAID') $paid++;
                elseif ($status === 'UNPAID') $unpaid++;
                elseif ($status === 'PARTIALLY PAID') $half++;

                // Escape data to prevent XSS
                $room = htmlspecialchars($row['room_number']);
                $tenant = htmlspecialchars($row['tenant_username']);
                $amount_due = number_format($row['amount_due'], 0);
                $amount_paid = number_format($row['amount_paid'], 0);
                $due_date = htmlspecialchars($row['due_date']);
                $payment_id = (int)$row['payment_id'];
                $status_class = strtolower(str_replace(' ', '-', $status));

                echo "<tr>
                        <td>{$room}</td>
                        <td>{$tenant}</td>
                        <td>{$amount_due}</td>
                        <td>{$amount_paid}</td>
                        <td>{$due_date}</td>
                        <td class='status {$status_class}'>{$status}</td>
                        <td>
                            <a href='editdetails.php?id={$payment_id}' class='link-dark me-2' title='Edit'><i class='fas fa-edit'></i></a>
                            <a href='delete_payment.php?id={$payment_id}' class='link-dark' onclick='return confirm(\"Are you sure you want to delete this tenant?\");' title='Delete'><i class='fas fa-trash-alt'></i></a>
                        </td>
                    </tr>";
            }

            echo "</tbody></table>";
            echo "<div class='text-white mt-3 fw-semibold'>
                    SUMMARY: " . number_format($total, 0) . " COLLECTED / $paid PAID / $unpaid UNPAID / $half PARTIALLY PAID
                </div>";
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
