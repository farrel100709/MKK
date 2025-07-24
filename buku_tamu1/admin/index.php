<?php
session_start();
include '../koneksi.php'; //Pastikan file koneksi ke DataBase tersedia

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
-
    // Cek user di DataBase
    $query = "SELECT * FROM admin WHERE email = $email AND password = $password";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit();
    }

    else {
        echo "<script>alert('Email atau Password salah'); window.location.href='index.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            background: linear-gradient(to right, #a64ac9, #ff9a9e);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            width: 400px;
            background: #9b59b6;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: white;
        }
        .login-form input {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="mb-4 text-center"><strong>Login</strong></h2>
        <p class="text-center">Lupa Sandi klik <a href="lupa_pass.php" class="text-white">disini</a></p>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Login</button>
        </form>
    </div>   
</body>
</html>