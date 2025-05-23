<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tenant') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Interior Design - Blissease Apartment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
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

    header {
      padding-top: 80px;
      text-align: center;
      padding-bottom: 20px;
    }

    header h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    header p {
      max-width: 700px;
      margin: 0 auto;
      font-size: 1rem;
      color: #333;
      line-height: 1.6;
    }

    .cards-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding: 40px 20px;
    }

    .card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 300px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .card-content {
      padding: 20px;
    }

    .card-content h4 {
      font-size: 1rem;
      color: #888;
      margin-bottom: 5px;
    }

    .card-content h3 {
      font-size: 1.3rem;
      margin-bottom: 10px;
    }

    .card-content p {
      font-size: 0.95rem;
      line-height: 1.4;
      color: #555;
    }

    .card-content .guests {
      font-size: 0.85rem;
      margin-bottom: 10px;
      color: #555;
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

  <header>
    <h1>INTERIOR DESIGN</h1>
    <p>
      BlissEase offers a refreshing escape from the noise of city life. This cozy apartment combines rustic charm with modern comforts,
      surrounded by rolling hills, fresh air, and wide-open skies. BlissEase is your haven of calm where nature and comfort live in perfect harmony.
    </p>
  </header>

  <section class="cards-container">
    <div class="card">
      <img src="bedroom.jpg" alt="Bedroom" />
      <div class="card-content">
        <h4>Sapang Bato</h4>
        <h3>Bedroom</h3>
        <div class="guests">👥 3 guests</div>
        <p>This bedroom that can fit a whole family. It also has storage for books and other items with unique designs that don't take up space.</p>
      </div>
    </div>

    <div class="card">
      <img src="livingroom.jpg" alt="Living Room" />
      <div class="card-content">
        <h4>Sapang Bato</h4>
        <h3>Living room</h3>
        <div class="guests">👥 5–6 guests</div>
        <p>A living room that's used for entertaining friends, talking, reading or watching television.</p>
      </div>
    </div>

    <div class="card">
      <img src="kitchen.jpg" alt="Kitchen" />
      <div class="card-content">
        <h4>Sapang Bato</h4>
        <h3>Kitchen</h3>
        <div class="guests">👥 4 guests</div>
        <p>The kitchen has a stove, fridge, and storage, all designed to be simple and easy to use.</p>
      </div>
    </div>
  </section>
</body>
</html>
