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
  <title>About Us - Blissease Apartment</title>
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
      background-color: #f9f9f9;
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

    .about-section {
      padding: 100px 40px 40px;
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      gap: 40px;
    }

    .about-text {
      flex: 1 1 400px;
    }

    .about-text h1 {
      font-size: 2.5rem;
      margin-bottom: 20px;
    }

    .about-text p {
      font-size: 1rem;
      line-height: 1.7;
      margin-bottom: 30px;
      color: #333;
    }

    .about-text .highlight {
      background-color: #e0e6ec;
      padding: 15px 20px;
      border-left: 4px solid #333;
      font-style: italic;
      font-size: 0.95rem;
    }

    .about-image {
      flex: 1 1 400px;
    }

    .about-image img {
      width: 100%;
      max-width: 500px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
      .about-section {
        flex-direction: column;
        text-align: center;
      }

      .about-text {
        padding: 0 10px;
      }
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
      <a href="Home page.php">Home</a>
      <a href="myaccount.php">My Account</a>
      <div class="dropdown">
        <a href="#">Options</a>
        <div class="dropdown-content">
          <a href="logout.php" onclick="return confirmLogout();">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <section class="about-section">
    <div class="about-text">
      <h1>About Us</h1>
      <p>
        We offer comfortable and affordable apartments, designed to make you feel at home.
        With modern amenities and a great location, we provide a place where you can relax
        and enjoy life. Come join us and find your perfect home!
      </p>
      <div class="highlight">We strive to offer you best possible homes to stay.</div>
    </div>
    <div class="about-image">
      <img src="aboutus.jpg" alt="Blissease Apartment Exterior">
    </div>
  </section>
</body>
</html>
