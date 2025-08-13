<?php
// Menginclude file koneksi ke database
include '../koneksi.php';

// Menjalankan query untuk menghapus semua admin yang memiliki level 'off' dari tabel admin
mysqli_query($conn, "DELETE FROM admin WHERE level = 'off'");

// Menampilkan pesan bahwa admin dengan level 'off' telah dihapus
echo "Admin level 'off' dihapus.";
?>
