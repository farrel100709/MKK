<?php
// Menginclude file koneksi ke database
include '../koneksi.php';

// AKTIVASI ADMIN LANGSUNG DI FILE INI
// Memeriksa apakah ada parameter 'aktifkan_id' dalam URL
if (isset($_GET['aktifkan_id'])) {
    $id = $_GET['aktifkan_id']; // Mengambil id dari parameter URL
    // Mengupdate status level admin menjadi 'on' di database
    mysqli_query($conn, "UPDATE admin SET level = 'on' WHERE id = '$id'");
    // Redirect ke halaman t_admin.php setelah aktivasi
    header("location: t_admin.php");
    exit(); // Menghentikan eksekusi script setelah redirect
}

// DEAKTIVASI ADMIN LANGSUNG DI FILE INI
// Memeriksa apakah ada parameter 'deactivate_id' dalam POST request
if (isset($_POST['deactivate_id'])) {
    $id = $_POST['deactivate_id']; // Mengambil id dari POST request
    // Mengupdate status level admin menjadi 'off' di database
    mysqli_query($conn, "UPDATE admin SET level = 'off' WHERE id = '$id'");
    // Redirect ke halaman t_admin.php setelah deaktivas
    header("location: t_admin.php");
    exit(); // Menghentikan eksekusi script setelah redirect
}

// Setup pagination
$limit = 5; // Maksimal 5 data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mengambil nomor halaman dari URL, default ke 1
$start = ($page - 1) * $limit; // Menghitung offset untuk query

// Mengambil data admin dari database dengan limit dan offset
$result = mysqli_query($conn, "SELECT * FROM admin ORDER BY id DESC LIMIT $start, $limit");
// Menghitung total data admin di database
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM admin");
$total_row = mysqli_fetch_assoc($total_result); // Mengambil hasil sebagai array asosiatif
$total_data = $total_row['total']; // Menyimpan total data ke variabel
$total_page = ceil($total_data / $limit); // Menghitung total halaman

// Mendapatkan id admin yang sedang login dari session
session_start();
$current_admin_id = $_SESSION['admin_id']; // Mengambil id admin yang sedang login
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"> <!-- Set karakter encoding untuk halaman -->
  <title>Data Admin</title> <!-- Judul halaman -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Menginclude CSS Bootstrap -->
  <style>
    /* Style untuk body halaman */
    body {
      background: linear-gradient(to right, #83a4d4, #b6fbff); /* Latar belakang gradien */
      padding: 30px; /* Padding untuk body */
      font-family: Arial, sans-serif; /* Mengatur font untuk halaman */
    }
    /* Style untuk kontainer tabel */
    .table-container {
      background: #ffffff; /* Latar belakang putih untuk kontainer */
      padding: 30px; /* Padding untuk kontainer */
      border-radius: 10px; /* Sudut melengkung untuk kontainer */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Bayangan untuk kontainer */
    }
    /* Style untuk header tabel */
    table thead {
      background: #007bff; /* Latar belakang biru untuk header tabel */
      color: white; /* Warna teks putih untuk header tabel */
    }
    /* Style untuk pagination saat aktif */
    .pagination .page-item.active .page-link {
      background-color: #007bff; /* Warna latar belakang biru untuk item aktif */
      border-color: #007bff; /* Warna border biru untuk item aktif */
    }
  </style>
</head>
<body>

<div class="container table-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Data Admin</h4> <!-- Judul halaman data admin -->
    <a href="dashboard.php" class="btn btn-secondary">Kembali</a> <!-- Tombol untuk kembali ke dashboard -->
    <a href="regis.php" class="btn btn-success">+ Tambah Admin</a> <!-- Tombol untuk menambah admin baru -->
  </div>

  <table class="table table-striped table-hover text-center"> <!-- Tabel untuk menampilkan data admin -->
    <thead>
      <tr>
        <th>No</th> <!-- Kolom nomor -->
        <th>Email</th> <!-- Kolom email -->
        <th>Username</th> <!-- Kolom username -->
        <th>Password</th> <!-- Kolom password -->
        <th>Status Level</th> <!-- Kolom status level -->
        <th>Aksi</th> <!-- Kolom aksi -->
      </tr>
    </thead>
    <tbody>
      <?php
      $no = $start + 1; // Menghitung nomor urut
      // Mengambil setiap baris data admin dari hasil query
      while ($row = mysqli_fetch_assoc($result)) {
        // Menyembunyikan akun yang sedang digunakan dari tabel
        if ($row['id'] == $current_admin_id) {
            continue; // Lewati iterasi jika id admin sama dengan id yang sedang login
        }

        // Menentukan badge dan tombol aksi berdasarkan status level
        if ($row['level'] == 'on') {
            $badge = "<span class='badge bg-success'>Aktif</span>"; // Badge untuk status aktif
            $actionButton = "
            <form action='t_admin.php' method='POST' style='display:inline;'> <!-- Form untuk menonaktifkan admin -->
                <input type='hidden' name='deactivate_id' value='{$row['id']}'> <!-- Menyimpan id admin yang dinonaktifkan -->
                <button type='submit' class='btn btn-sm btn-danger'>Nonaktif</button> <!-- Tombol untuk menonaktifkan -->
            </form>";
        } else {
            $badge = "<span class='badge bg-danger'>Nonaktif</span>"; // Badge untuk status nonaktif
            $actionButton = "
            <a href='?aktifkan_id={$row['id']}' class='btn btn-sm btn-warning'>Aktifkan</a>"; // Link untuk mengaktifkan admin
        }

        // Menampilkan data admin dalam tabel
        echo "<tr>
                <td>$no</td>
                <td>{$row['email']}</td>
                <td>{$row['username']}</td>
                <td>{$row['password']}</td>
                <td>{$badge}</td>
                <td>{$actionButton}</td>
              </tr>";
        $no++; // Increment nomor urut
      }

      // Jika tidak ada data admin, tampilkan pesan
      if (mysqli_num_rows($result) == 0) {
        echo "<tr><td colspan='6'>Tidak ada data admin.</td></tr>"; // Pesan jika tidak ada data
      }
      ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <nav>
    <ul class="pagination justify-content-center"> <!-- Kontainer untuk pagination -->
      <?php if ($page > 1): ?> <!-- Jika bukan halaman pertama, tampilkan tombol sebelumnya -->
        <li class="page-item">
          <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo;</a> <!-- Tombol untuk halaman sebelumnya -->
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_page; $i++): ?> <!-- Loop untuk menampilkan nomor halaman -->
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"> <!-- Menandai halaman aktif -->
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a> <!-- Link untuk nomor halaman -->
        </li>
      <?php endfor; ?>

      <?php if ($page < $total_page): ?> <!-- Jika bukan halaman terakhir, tampilkan tombol berikutnya -->
        <li class="page-item">
          <a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a> <!-- Tombol untuk halaman berikutnya -->
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

<script>
  // Menjalankan auto-delete admin level off setelah 10 detik
  setTimeout(() => {
    console.log("Menjalankan auto-delete admin level off...");

    // Mengirim permintaan ke delete.php untuk menghapus admin yang dinonaktifkan
    fetch('delete.php')
      .then(response => response.text()) // Mengambil respon dari server
      .then(data => {
        console.log("Respon dari server:", data);
        // Reload halaman hanya jika ada respon "berhasil"
        if (data.includes("berhasil")) {
          location.reload(); // Memuat ulang halaman
        }
      })
      .catch(error => console.error("Gagal menjalankan auto-delete:", error)); // Menangani error
  }, 10 * 1000); // 10 detik
</script>

</body>
</html>
