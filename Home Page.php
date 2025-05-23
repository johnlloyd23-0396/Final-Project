<?php
session_start();

// Redirect non-tenants away from this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tenant') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Blissease Apartment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      font-family: 'Poppins', sans-serif;
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

    .navbar-right a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      transition: 0.3s ease;
    }

    .navbar-right a:hover {
      color: #f0c040;
    }

    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      background-color: #333;
      min-width: 160px;
      z-index: 1;
    }

    .dropdown-content a {
      color: white;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      font-weight: 600;
    }

    .dropdown-content a:hover {
      background-color: #f0c040;
      color: black;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .hero {
      background: url('homepage.png') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      text-align: center;
    }

    .overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
      max-width: 90%;
    }

    .hero-content img {
      width: 320px;
      max-width: 90%;
    }

    .hero-content p {
      font-size: 1.2rem;
      background: rgba(255, 255, 255, 0.2);
      padding: 10px 20px;
      border-radius: 5px;
      font-weight: 500;
    }

    .hero-buttons {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .hero-button {
      padding: 10px 25px;
      border-radius: 20px;
      background-color: rgba(255, 255, 255, 0.2);
      color: white;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero-button:hover {
      background-color: #f0c040;
      color: black;
      border-color: #f0c040;
    }
  </style>

  <script>
    function confirmLogout() {
      return confirm("Are you sure you want to log out?");
    }
  </script>
</head>
<body>
  <nav>
    <div class="navbar-logo">Blissease Apartment</div>
    <div class="navbar-right">
      <a href="#">Home</a>
      <a href="myaccount.php">My Account</a>
      <div class="dropdown">
        <a href="#">Options</a>
        <div class="dropdown-content">
          <a href="logout.php" onclick="return confirmLogout();">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <header class="hero">
    <div class="overlay"></div>
    <div class="hero-content">
      <img src="logo.png" alt="Blissease Apartment Logo" />
      <p>LIVE AT EASE AND HAVE A BLISSFUL LIFE</p>
      <div class="hero-buttons">
        <a href="design.php" class="hero-button">INTERIOR DESIGN</a>
        <a href="aboutus.php" class="hero-button">ABOUT US</a>
      </div>
    </div>
  </header>
</body>
</html>
