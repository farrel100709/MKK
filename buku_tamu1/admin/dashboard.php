<?php
session_start();
include '../koneksi.php'; // Pastikan path koneksi database benar

// Periksa session admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Ambil data admin dari database
$query = mysqli_query($conn, "SELECT username FROM admin WHERE id = '$admin_id'");
$admin = mysqli_fetch_assoc($query);
$username = $admin['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Resepsionis</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #ba7474ff;
    }

    .sidebar {
      position: fixed;
      left: -250px;
      top: 0;
      width: 250px;
      height: 100%;
      background: #2c3e50;
      color: white;
      transition: left 0.3s ease;
      z-index: 1000;
    }

    .sidebar.active {
      left: 0;
    }

    .sidebar h2 {
      text-align: center;
      padding: 1rem;
      background: #1a252f;
      margin: 0;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li {
      padding: 15px 20px;
      border-bottom: 1px solid #34495e;
    }

    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
      margin-right: 10px;
      width: 20px;
    }

    .sidebar ul li:hover {
      background: #34495e;
      cursor: pointer;
    }

    .menu-toggle {
      position: fixed;
      top: 15px;
      left: 15px;
      font-size: 24px;
      color: #73945bff;
      cursor: pointer;
      z-index: 1100;
    }

    .content {
      padding: 2rem;
      margin-left: 0;
      transition: margin-left 0.3s ease;
    }

    .sidebar.active ~ .content {
      margin-left: 250px;
    }

    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 2px 10px 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>

  <div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </div>

  <div class="sidebar" id="sidebar">
    <h2>Menu</h2>
    <ul>
      <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
      <li><a href="t_admin.php"><i class="fas fa-user-shield"></i> Admin</a></li>
      <li><a href="#"><i class="fas fa-users"></i> Tamu</a></li>
      <li><a href="#"><i class="fas fa-user-check"></i> Kehadiran</a></li>
      <li><a href="#"><i class="fas fa-file-alt"></i> Laporan</a></li>
      <li><a href="index.php" onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

  <div class="content">
    <div class="card">
      <h1>Selamat Datang, <a href="profile.php" style="text-decoration: none; color: #007bff;"><?= htmlspecialchars($username) ?></a>!</h1>
      <h2>SMKN 71 JAKARTA</h2>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
    }

    function confirmLogout() {
      if (confirm("Apakah Anda yakin ingin logout?")) {
        window.location.href = "index.php"; // Ganti dengan path logout Anda
      }
    }
  </script>

</body>
</html>
