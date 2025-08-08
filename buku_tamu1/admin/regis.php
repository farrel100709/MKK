<?php
// Menginclude file koneksi ke database
include '../koneksi.php';

// Variabel untuk menyimpan pesan
$message = '';

// Memeriksa apakah metode request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah email sudah terdaftar di database
    $cek = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        // Jika email sudah terdaftar, tampilkan pesan error
        $message = "<div class= 'alert alert-danger'>Email sudah terdaftar!</div>";
    } else {
        // Query untuk menambahkan admin baru ke database
        $query = "INSERT INTO admin (email, username, password, level) VALUES ('$email', '$username', '$password', 'off')";
        // Menjalankan query dan memeriksa apakah berhasil
        if (mysqli_query($conn, $query)) {
            // Jika berhasil, redirect ke halaman t_admin.php
            header("location: t_admin.php");
            exit(); // Menghentikan eksekusi script setelah redirect
        } else {
            // Jika gagal, tampilkan pesan error
            $message = "<div class= 'alert alert-danger'>Gagal menambahkan admin!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set karakter encoding untuk halaman -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set viewport untuk responsivitas -->
    <title>Tambah Admin</title> <!-- Judul halaman -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> <!-- Menginclude CSS Bootstrap -->
    <style>
        /* Style untuk body halaman */
        body {
            background: linear-gradient(to right, #3a7eddff, #7bc0f9ff); /* Latar belakang gradien */
            height: 100vh; /* Tinggi halaman penuh */
            display: flex; /* Menggunakan flexbox untuk penataan */
            justify-content: center; /* Mengatur konten agar berada di tengah secara horizontal */
            align-items: center; /* Mengatur konten agar berada di tengah secara vertikal */
        }
        /* Style untuk card */
        .card {
            padding: 2rem; /* Padding untuk card */
            border-radius: 1rem; /* Sudut melengkung untuk card */
            box-shadow: 0 10px 20px rgba(0,0,0,0.2); /* Bayangan untuk card */
        }
        /* Style untuk judul */
        h3 {
            color: #444; /* Warna teks untuk judul */
        }
    </style>
</head>
<body>
    <div class= "card col-md-6 bg-light"> <!-- Kontainer card untuk form -->
        <h3 class= "text-center mb-4">Tambah Admin Baru</h3> <!-- Judul form -->
        <?= $message ?> <!-- Menampilkan pesan jika ada -->
        <form method="POST" action=""> <!-- Form untuk menambah admin -->
            <div class= "mb-3">
                <label for="form-label">Email</label> <!-- Label untuk input email -->
                <input type="email" name="email" class="form-control" required> <!-- Input untuk email -->
            </div>
            <div class= "mb-3">
                <label for="form-label">Username</label> <!-- Label untuk input username -->
                <input type="text" name="username" class="form-control" required> <!-- Input untuk username -->
            </div>
            <div class= "mb-3">
                <label for="form-label">Password</label> <!-- Label untuk input password -->
                <input type="password" name="password" class="form-control" maxlength="8" required> <!-- Input untuk password dengan batasan karakter -->
                <div class="form-text">Maksimal 8 karakter sesuai struktur tabel.</div> <!-- Petunjuk untuk input password -->
            </div>
            <button type="submit" class="btn btn-primary w-100">Tambah Admin</button> <!-- Tombol untuk submit form -->
            <a href="t_admin.php" class="btn btn-secondary w-100 mt-2">Batal</a> <!-- Tombol untuk membatalkan dan kembali ke halaman t_admin.php -->
        </form>
    </div>
</body>
</html>
