<?php
include "db_connect.php";

$searchTerm = '';
$results = [];
$error = '';
$searched = false;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $searchTerm = trim($_GET['q']);
    $searched = true;

    if ($searchTerm === '') {
        $error = "Please enter a search term.";
    } else {
        // Prepare SQL with LIKE for room_number or tenant_username
        $likeTerm = "%$searchTerm%";
        $sql = "SELECT * FROM payments WHERE room_number LIKE ? OR tenant_username LIKE ? ORDER BY room_number ASC";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $likeTerm, $likeTerm);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $results[] = $row;
            }
        } else {
            $error = "Query failed: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Search Tenants - Blissease Apartment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
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
        .form-control {
            background-color: #ffffffcc;
            border: none;
        }
        .btn-primary {
            font-weight: 600;
        }
        .status.paid { color: #28a745; font-weight: bold; }
        .status.partially-paid { color: #ffc107; font-weight: bold; }
        .status.unpaid { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="glass-card">
        <h3 class="mb-4 text-center">Search Tenants / Payments</h3>

        <form method="get" class="mb-4">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Enter Room Number or Tenant Username" value="<?= htmlspecialchars($searchTerm) ?>" autofocus />
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($searched): ?>
            <?php if (count($results) === 0 && !$error): ?>
                <div class="alert alert-info">No tenants found matching your search.</div>
            <?php elseif (count($results) > 0): ?>
                <table class="table table-bordered table-striped text-center">
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
                    <tbody>
                    <?php foreach ($results as $row): 
                        $statusClass = strtolower(str_replace(' ', '-', $row['status']));
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['room_number']) ?></td>
                            <td><?= htmlspecialchars($row['tenant_username']) ?></td>
                            <td><?= number_format($row['amount_due'], 2) ?></td>
                            <td><?= number_format($row['amount_paid'], 2) ?></td>
                            <td><?= htmlspecialchars($row['due_date']) ?></td>
                            <td class="status <?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <a href="edit_payment.php?id=<?= $row['payment_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>

        <div class="text-center mt-3">
            <a href="dashboard-admin.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
