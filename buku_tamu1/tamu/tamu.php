<?php
// Memulai sesi untuk menyimpan data pengguna
session_start();

// Memeriksa apakah username ada dalam session, jika tidak, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: ../admin/index.php");
    exit(); // Menghentikan eksekusi script setelah redirect
}

// Menginclude file koneksi ke database
include '../koneksi.php';
$username = $_SESSION['username']; // Mengambil username dari session

// Ambil input filter tanggal dari query string
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : ''; // Tanggal awal filter
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : ''; // Tanggal akhir filter

// Pagination
$batas = 10; // Jumlah data yang ditampilkan per halaman
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1; // Mengambil nomor halaman dari query string, default ke 1
$mulai = ($halaman > 1) ? ($halaman * $batas) - $batas : 0; // Menghitung offset untuk query

// Filter query
$filter_sql = ""; // Inisialisasi query filter
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    // Jika tanggal awal dan akhir diisi, tambahkan kondisi filter ke query
    $filter_sql = " WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

// Query untuk mengambil data tamu dengan limit dan offset
$sql = "SELECT * FROM tamu $filter_sql LIMIT $mulai, $batas";
$data = mysqli_query($conn, $sql); // Menjalankan query dan menyimpan hasilnya

// Hitung total data tamu yang sesuai dengan filter
$total_data = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tamu $filter_sql")); // Menghitung jumlah total data
$total_halaman = ceil($total_data / $batas); // Menghitung total halaman berdasarkan batas data per halaman
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"> <!-- Set karakter encoding untuk halaman -->
    <title>Daftar Tamu</title> <!-- Judul halaman -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Mengatur viewport untuk responsivitas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Menginclude Font Awesome untuk ikon -->
   <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #e0e0e0ff;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            margin: 0;
        }

        .container {
            padding: 20px;
        }

        form.filter {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        form.filter input[type="date"] {
            padding: 8px;
        }

        form.filter button, .reset-btn {
            padding: 8px 12px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .reset-btn {
            background: #e74c3c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #2c3e50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 3px;
            background: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a.active {
            background: #1abc9c;
        }

        .pagination a:hover {
            background: #34495e;
        }

        .btn-back {
            margin-top: 30px;
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Daftar Tamu</h2> <!-- Judul untuk daftar tamu -->
    </div>

    <div class="container">
        <form class="filter" method="GET" action=""> <!-- Form untuk filter tanggal -->
            <label><i class="fas fa-calendar-alt"></i> Dari:</label>
            <input type="date" name="tanggal_awal" value="<?= htmlspecialchars($tanggal_awal) ?>"> <!-- Input untuk tanggal awal -->

            <label>Sampai:</label>
            <input type="date" name="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir) ?>"> <!-- Input untuk tanggal akhir -->

            <button type="submit"><i class="fas fa-search"></i> Tampilkan</button> <!-- Tombol untuk submit filter -->
            <a href="tamu.php" class="reset-btn">Reset</a> <!-- Link untuk mereset filter -->
        </form>
         <?php if (isset($_GET['message'])): ?>
         <div style="color: green;"><?= htmlspecialchars($_GET['message']) ?></div>
         <?php endif; ?>

        <table> <!-- Tabel untuk menampilkan data tamu -->
    <tr>
        <th>No</th> <!-- Kolom nomor -->
        <th>Nama</th> <!-- Kolom nama tamu -->
        <th>Instansi</th> <!-- Kolom instansi tamu -->
        <th>Keperluan</th> <!-- Kolom keperluan tamu -->
        <th>Tanggal</th> <!-- Kolom tanggal kunjungan -->
        <th>Waktu</th> <!-- Kolom waktu kunjungan -->
        <th>Aksi</th> <!-- Kolom aksi -->
    </tr>
    
    <?php 
    $no = $mulai + 1; // Menghitung nomor urut
    while ($row = mysqli_fetch_assoc($data)) { // Mengambil setiap baris data tamu
        echo "<tr>
            <td>$no</td>
            <td>{$row['nama']}</td>
            <td>{$row['instansi']}</td>
            <td>{$row['keperluan']}</td>
            <td>{$row['tanggal']}</td>
            <td>{$row['waktu']}</td>
            <td>
                <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus tamu ini?\");'>Hapus</a>
            </td>
        </tr>";
        $no++; // Increment nomor urut
    }
    
    // Jika tidak ada data tamu, tampilkan pesan
    if (mysqli_num_rows($data) == 0) {
        echo "<tr><td colspan='7' style='text-align:center;'>Tidak ada data</td></tr>"; // Pesan jika tidak ada data
    }
    ?>
</table>


        <div class="pagination"> <!-- Kontainer untuk pagination -->
            <?php for ($i = 1; $i <= $total_halaman; $i++) : ?> <!-- Loop untuk menampilkan nomor halaman -->
                <a class="<?= ($i == $halaman) ? 'active' : '' ?>" href="?halaman=<?= $i ?>&tanggal_awal=<?= urlencode($tanggal_awal) ?>&tanggal_akhir=<?= urlencode($tanggal_akhir) ?>">
                    <?= $i ?> <!-- Link untuk nomor halaman -->
                </a>
            <?php endfor; ?>
        </div>

        <a href="../admin/dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a> <!-- Tombol untuk kembali ke dashboard -->
    </div>
</body>
</html>
