<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in and is a tenant
if (!isset($_SESSION['tenant_username']) || $_SESSION['role'] !== 'tenant') {
    header("Location: login.php");
    exit();
}

$tenant_username = $_SESSION['tenant_username'];

// Fetch tenant payment info from database
$query = "SELECT room_number, tenant_username, amount_due, amount_paid, due_date, status 
          FROM payments 
          WHERE tenant_username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $tenant_username);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Tenant Home | Blissease Apartment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    nav {
      background-color: rgba(0, 0, 0, 0.6);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 10;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar-logo {
      color: white;
      font-weight: bold;
      font-size: 1.5rem;
    }

    .navbar-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .navbar-right a, .navbar-right button {
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      transition: 0.3s ease;
      background: transparent;
      border: none;
      cursor: pointer;
    }

    .navbar-right a:hover, .navbar-right button:hover {
      color: #f0c040;
    }

    .hero-content {
      padding-top: 80px;
      text-align: center;
    }

    h2 {
      margin-top: 2rem;
    }

    table {
      width: 90%;
      margin: 2rem auto;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 1rem;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    section p {
      text-align: center;
      font-weight: 500;
      margin-bottom: 2rem;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav>
    <div class="navbar-logo">Blissease Apartment</div>
    <div class="navbar-right">
      <a href="Home Page.php">Home</a>
      <a href="#">My Account</a>
      <form action="logout.php" method="POST" style="display: inline;" onsubmit="return confirmLogout();">
        <button type="submit">Logout</button>
      </form>
    </div>
  </nav>

  <!-- Tenant Dashboard -->
  <section class="hero-content">
    <h2>Welcome, <?= htmlspecialchars($tenant_username) ?>!</h2>
    <h2>Your Payment Status</h2>

    <table>
      <tr>
        <th>Room Number</th>
        <th>Tenant</th>
        <th>Amount To Pay</th>
        <th>You Paid</th>
        <th>Next Due/Due Date</th>
        <th>Status Of Payment</th>
      </tr>

      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['room_number']) ?></td>
            <td><?= htmlspecialchars($row['tenant_username']) ?></td>
            <td><?= number_format($row['amount_due'], 2) ?></td>
            <td><?= number_format($row['amount_paid'], 2) ?></td>
            <td><?= date('F d, Y', strtotime($row['due_date'])) ?></td>
            <td><?= strtoupper(htmlspecialchars($row['status'])) ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="6">No payment data found.</td>
        </tr>
      <?php endif; ?>

    </table>

    <p>Thank you for staying with Blissease!</p>
  </section>

  <script>
    function confirmLogout() {
      return confirm("Are you sure you want to log out?");
    }
  </script>
</body>
</html>
