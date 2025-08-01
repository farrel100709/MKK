<?php
session_start();
include "../koneksi.php"; // Pastikan file koneksi ke database tersedia

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek user di database
    $query = "SELECT * FROM admin WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('email atau password salah!'); window.location.href='index.php';</script>";
    }
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
    height: 100vh;
    background: linear-gradient(to right, #516979ff, #e2ebf0); /* abu muda ke biru muda */
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-container {
    width: 400px;
    background: #ffffffff; /* putih bersih */
    border-radius: 10px;
    padding: 40px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    color: #333; /* teks abu gelap */
}



</style>
</head>
<body>
<div class="login-container">
    <h2 class="mb-4 text-center"><strong>Login</strong></h2>
    <p class="text-center">Lupa Sandi? <a href="lupa_pass.php" style="color: #0d6efd; text-decoration: underline;">Klik di sini</a></p>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-dark w-100">Login</button>
    </form>
</div>
</body>
</html>