<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

include '../koneksi.php'; // pastikan path ini benar

$admin_id = (int) $_SESSION['admin_id'];
$success = '';
$error = '';

// Ambil data awal
$stmt = $conn->prepare("SELECT email, username, password, level FROM admin WHERE id = ?");
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$stmt->bind_result($email, $username, $password_db, $level);
$stmt->fetch();
$stmt->close();

// Process update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = $_POST['email'] ?? '';
    $new_username = $_POST['username'] ?? '';
    $new_password = $_POST['password'] ?? '';

    if ($new_email === '' || $new_username === '') {
        $error = "Email dan Username tidak boleh kosong!";
    } else {
        if ($new_password === '') {
            $up = $conn->prepare("UPDATE admin SET email = ?, username = ? WHERE id = ?");
            $up->bind_param('ssi', $new_email, $new_username, $admin_id);
        } else {
            $up = $conn->prepare("UPDATE admin SET email = ?, username = ?, password = ? WHERE id = ?");
            $up->bind_param('sssi', $new_email, $new_username, $new_password, $admin_id);
        }

        if ($up->execute()) {
            $success = "Profil berhasil diperbarui.";
            $_SESSION['username'] = $new_username;
            $email = $new_email;
            $username = $new_username;
            $password_db = $new_password;
        } else {
            $error = "Gagal memperbarui profil. " . $conn->error;
        }

        $up->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(115deg, #a5c0ff 0%, #f2f9d6 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      font-family: 'Segoe UI', sans-serif;
    }
    .profile-card {
      width: 100%;
      max-width: 820px;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .profile-cover {
      background: linear-gradient(135deg, #4fa4fc 0%, #09c6f9 100%);
      height: 140px;
    }
    .avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background: #fff;
      margin-top: -55px;
      display: grid;
      place-items: center;
      font-size: 42px;
      font-weight: 700;
      color: #3f2596;
      box-shadow: 0 12px 24px rgba(0,0,0,0.15);
      border: 6px solid #fff;
    }
    .level-badge {
      font-size: .8rem;
    }
    .form-control:focus {
      box-shadow: 0 0 0 2px rgba(79, 172, 254, 0.25);
      border-color: #4fa4fc;
    }
  </style>
</head>
<body>

<div class="card profile-card">
  <div class="profile-cover"></div>
  <div class="card-body">
    <div class="text-center">
      <div class="avatar" aria-label="Avatar">
        <?php
          $inisial = strtoupper(substr($username, 0, 1));
          echo htmlspecialchars($inisial);
        ?>
      </div>
      <div class="mt-3">
        <h3 class="mb-0">
          <?= htmlspecialchars($username) ?>
          <?php if (!empty($level)): ?>
            <span class="badge bg-<?= $level == 'on' ? 'success' : 'secondary' ?> level-badge">
              Level: <?= htmlspecialchars($level) ?>
            </span>
          <?php endif; ?>
        </h3>
        <div class="text-muted">ID: <?= $admin_id ?></div>
      </div>
    </div>

    <hr>

    <?php if ($success): ?>
      <div class="alert alert-success mt-4" role="alert"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger mt-4" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form class="mt-4" method="POST">
      <div class="row g-3">
        <div class="col-md-6">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div class="col-md-6">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
        </div>
        <div class="col-md-12">
          <label for="password" class="form-label">Password Baru (opsional)</label>
          <div class="input-group">
            <input type="password" name="password" class="form-control" id="password" placeholder="Kosongkan jika tidak ingin mengganti">
            <button class="btn btn-outline-secondary" type="button" id="togglePass">👁</button>
          </div>
          <div class="form-text">Biarkan kosong jika tidak ingin mengubah password.</div>
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="dashboard.php" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
      </div>
    </form>
  </div>
</div>

<script>
const toggleBtn = document.getElementById('togglePass');
const passInput = document.getElementById('password');
toggleBtn.addEventListener('click', () => {
  const type = passInput.type === 'password' ? 'text' : 'password';
  passInput.type = type;
  toggleBtn.textContent = type === 'password' ? '👁' : '🙈';
});
</script>

</body>
</html>
