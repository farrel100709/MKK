<?php
// Memulai session
session_start();

// Menghapus semua variabel session
$_SESSION = array();

// Menghapus session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Menghancurkan session
session_destroy();

// Redirect ke halaman login setelah logout
header("Location: ../admin/index.php");
exit();
