<?php
// Menginclude file koneksi ke database
include '../koneksi.php';

// Memeriksa apakah metode request adalah POST dan parameter 'id' ada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Mengambil id dari POST request dan mengkonversinya menjadi integer

    // Ambil nilai level saat ini dari admin berdasarkan id
    $query = mysqli_query($conn, "SELECT level FROM admin WHERE id = $id");
    $row = mysqli_fetch_assoc($query); // Mengambil hasil query sebagai array asosiatif

    // Memeriksa apakah data admin ditemukan
    if ($row) {
        $current = $row['level']; // Menyimpan level saat ini
        // Menentukan level baru berdasarkan level saat ini
        $new_level = ($current === 'admin') ? 'nonaktif' : 'admin'; // Jika level saat ini 'admin', ubah menjadi 'nonaktif', sebaliknya ubah menjadi 'admin'

        // Melakukan update level admin di database
        mysqli_query($conn, "UPDATE admin SET level = '$new_level' WHERE id = $id");
    }
}

// Redirect ke halaman t_admin.php setelah melakukan update
header("Location: t_admin.php");
exit; // Menghentikan eksekusi script setelah redirect
?>
