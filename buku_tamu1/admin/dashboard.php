<?php
// Memulai sesi untuk menyimpan data pengguna
session_start();

// Menginclude file koneksi ke database
include '../koneksi.php'; // Pastikan path koneksi database benar

// Memeriksa apakah session admin_id ada, jika tidak, redirect ke halaman index
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit(); // Menghentikan eksekusi script setelah redirect
}

// Mengambil id admin dari session
$admin_id = $_SESSION['admin_id'];

// Mengambil data admin dari database berdasarkan id
$query = mysqli_query($conn, "SELECT username FROM admin WHERE id = '$admin_id'");
$admin = mysqli_fetch_assoc($query); // Mengambil hasil query sebagai array asosiatif
$username = $admin['username']; // Menyimpan username admin ke variabel
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"> <!-- Set karakter encoding untuk halaman -->
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Set viewport untuk responsivitas -->
  <title>Resepsionis</title> <!-- Judul halaman -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> <!-- Menginclude Font Awesome untuk ikon -->
  <style>
    /* Style untuk body halaman */
    body {
      margin: 0; /* Menghilangkan margin default */
      font-family: 'Segoe UI', sans-serif; /* Mengatur font untuk halaman */
      background: #ba7474ff; /* Mengatur warna latar belakang */
    }

    /* Style untuk sidebar */
    .sidebar {
      position: fixed; /* Menempatkan sidebar secara tetap */
      left: -250px; /* Menyembunyikan sidebar di luar layar */
      top: 0; /* Menempatkan sidebar di bagian atas */
      width: 250px; /* Lebar sidebar */
      height: 100%; /* Tinggi sidebar penuh */
      background: #2c3e50; /* Warna latar belakang sidebar */
      color: white; /* Warna teks sidebar */
      transition: left 0.3s ease; /* Animasi transisi saat sidebar muncul */
      z-index: 1000; /* Menempatkan sidebar di atas elemen lain */
    }

    /* Style untuk sidebar saat aktif */
    .sidebar.active {
      left: 0; /* Menampilkan sidebar */
    }

    /* Style untuk judul di sidebar */
    .sidebar h2 {
      text-align: center; /* Mengatur teks agar berada di tengah */
      padding: 1rem; /* Memberikan padding pada judul */
      background: #1a252f; /* Warna latar belakang judul */
      margin: 0; /* Menghilangkan margin */
    }

    /* Style untuk daftar menu di sidebar */
    .sidebar ul {
      list-style: none; /* Menghilangkan bullet pada list */
      padding: 0; /* Menghilangkan padding */
    }

    /* Style untuk setiap item di sidebar */
    .sidebar ul li {
      padding: 15px 20px; /* Memberikan padding pada item */
      border-bottom: 1px solid #34495e; /* Garis bawah item */
    }

    /* Style untuk link di sidebar */
    .sidebar ul li a {
      color: white; /* Warna teks link */
      text-decoration: none; /* Menghilangkan garis bawah pada link */
      display: flex; /* Mengatur link sebagai flex container */
      align-items: center; /* Mengatur item di dalam link agar sejajar secara vertikal */
      margin-right: 10px; /* Memberikan margin kanan */
      width: 100%; /* Memastikan link mengisi lebar penuh */
    }

    /* Style untuk hover pada item sidebar */
    .sidebar ul li:hover {
      background: #34495e; /* Mengubah warna latar belakang saat hover */
      cursor: pointer; /* Mengubah kursor saat hover */
    }

    /* Style untuk tombol toggle menu */
    .menu-toggle {
      position: fixed; /* Menempatkan tombol secara tetap */
      top: 20px; /* Jarak dari atas */
      left: 20px; /* Jarak dari kiri */
      font-size: 24px; /* Ukuran font untuk ikon */
      color: #ffffffff; /* Warna ikon */
      cursor: pointer; /* Mengubah kursor saat hover */
      z-index: 1100; /* Menempatkan tombol di atas elemen lain */
    }

    /* Style untuk konten utama */
    .content {
      padding: 2rem; /* Memberikan padding pada konten */
      margin-left: 0; /* Mengatur margin kiri */
      transition: margin-left 0.3s ease; /* Animasi transisi saat sidebar aktif */
    }

    /* Mengatur margin konten saat sidebar aktif */
    .sidebar.active ~ .content {
      margin-left: 250px; /* Memberikan margin kiri saat sidebar aktif */
    }

    /* Style untuk card di konten */
    .card {
      background: white; /* Warna latar belakang card */
      padding: 20px; /* Memberikan padding pada card */
      border-radius: 10px; /* Mengatur sudut card agar melengkung */
      box-shadow: 2px 10px 10px rgba(0, 0, 0, 0.1); /* Memberikan bayangan pada card */
      margin: 0px 30px 40px 35px; /* Margin untuk card */
      text-align: center; /* Mengatur teks di dalam card agar berada di tengah */
    }
  </style>
</head>
<body>

  <!-- Tombol untuk toggle sidebar -->
  <div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i> <!-- Ikon hamburger untuk menu -->
  </div>

  <!-- Sidebar menu -->
  <div class="sidebar" id="sidebar">
    <h2>Menu</h2> <!-- Judul sidebar -->
    <ul>
      <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li> <!-- Link ke dashboard -->
      <li><a href="t_admin.php"><i class="fas fa-user-shield"></i> Admin</a></li> <!-- Link ke halaman admin -->
      <li><a href="../tamu/tamu.php"><i class="fas fa-users"></i> Tamu</a></li> <!-- Link ke halaman tamu -->
      <li><a href="../tamu/kehadiran.php"><i class="fas fa-user-check"></i> Kehadiran</a></li> <!-- Link ke halaman kehadiran -->
      <li><a href="#"><i class="fas fa-file-alt"></i> Laporan</a></li> <!-- Link ke halaman laporan -->
      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li> <!-- Link untuk logout -->
    </ul>
  </div>

  <!-- Konten utama -->
  <div class="content">
    <div class="card">
      <center><h1>Selamat Datang, <a href="profile.php" style="text-decoration: none; color: #007bff;"><?= htmlspecialchars($username) ?></a>!</h1></center> <!-- Menampilkan pesan selamat datang dengan username -->
      <center><h2>SMKN 71 JAKARTA</h2></center> <!-- Menampilkan nama sekolah -->
    </div>
  </div>

  <script>
    // Fungsi untuk toggle sidebar
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active"); // Menambahkan atau menghapus kelas 'active' pada sidebar
    }
  </script>

</body>
</html>
 