<?php
// Mulai sesi untuk menyimpan data pengguna
session_start();

// Include file koneksi ke database
include "../koneksi.php"; // Pastikan file koneksi ke database tersedia

// Periksa jika metode request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai email dan password dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Buat query untuk mencari pengguna di database
    $query = "SELECT * FROM admin WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    // Periksa jika pengguna ditemukan
    if (mysqli_num_rows($result) == 1) {
        // Ambil data pengguna
        $user = mysqli_fetch_assoc($result);
        // Simpan id dan username pengguna di sesi
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        // Redirect ke dashboard
        header("location: dashboard.php");
        exit();
    } else {
        // Jika pengguna tidak ditemukan, tampilkan pesan error
        echo "<script>alert('email atau password salah!'); window.location.href='index.php';</script>";
    }
}
?>

<!-- Dokumen HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Set karakter encoding -->
    <meta charset="UTF-8">
    <!-- Set ukuran viewport -->
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <!-- Set judul halaman -->
    <title>Login Admin</title>
    <!-- Include CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Style CSS -->
    <style>
    /* Set tinggi body menjadi 100vh */
    body {
    height: 100vh;
    /* Set background menjadi gradien */
    background: linear-gradient(to right, #516979ff, #e2ebf0); /* abu muda ke biru muda */
    /* Set posisi konten menjadi tengah */
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Style kontainer login */
.login-container {
    /* Set lebar kontainer */
    width: 400px;
    /* Set background menjadi putih */
    background: #ffffffff; /* putih bersih */
    /* Set radius sudut */
    border-radius: 10px;
    /* Set padding */
    padding: 40px;
    /* Set bayangan */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    /* Set warna teks */
    color: #333; /* teks abu gelap */
}

</style>
</head>
<body>
<!-- Kontainer login -->
<div class="login-container">
    <!-- Judul login -->
    <h2 class="mb-4 text-center"><strong>Login</strong></h2>
    <!-- Link lupa password -->
    <p class="text-center">Lupa Sandi? <a href="lupa_pass.php" style="color: #0d6efd; text-decoration: underline;">Klik di sini</a></p>

    <!-- Form login -->
    <form method="POST" action="">
        <!-- Input email -->
        <div class="mb-3">
            <label for="email" class="form-label">email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <!-- Input password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <!-- Tombol login -->
        <button type="submit" class="btn btn-dark w-100">Login</button>
    </form>
</div>
</body>
</html>
