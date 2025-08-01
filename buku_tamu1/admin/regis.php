<?php
include '../koneksi.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah email sudah terdaftar
    $cek = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $message = "<div class= 'alert alert-danger'>Email sudah terdaftar!</div>";
    } else {
        $query = "INSERT INTO admin (email, username, password, level) VALUES ('$email', '$username', '$password', 'off')";
        if (mysqli_query($conn, $query)) {
            // Jika berhasil, redirect ke halaman t_admin.php
            header("location: t_admin.php");
            exit();
        } else {
            $message = "<div class= 'alert alert-danger'>Gagal menambahkan admin!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #3a7eddff, #7bc0f9ff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        h3 {
            color: #444;
        }
    </style>
</head>
<body>
    <div class= "card col-md-6 bg-light">
        <h3 class= "text-center mb-4">Taambah Admin Baru</h3>
        <?= $message ?>
        <form method="POST" action="">
            <div class= "mb-3">
                <label for="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class= "mb-3">
                <label for="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class= "mb-3">
                <label for="form-label">Password</label>
                <input type="password" name="password" class="form-control" maxlength="8" required>
                <div class="form-text">Maksimal 8 karakter sesuai struktur tabel.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Tambah Admin</button>
            <a href="t_admin.php" class="btn btn-secondary w-100 mt-2">Batal</a>
        </form>
    </div>
</body>
</html>