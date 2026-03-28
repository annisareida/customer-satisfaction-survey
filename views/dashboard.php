<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit();
}

$adminName = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html>

<head>
  <title>Dashboard Admin</title>
  <link rel="stylesheet" type="text/css" href="style_dashboard.css">
</head>

<body>
  <div class="navbar" id="navbar">
    <button class="close-btn" onclick="toggleNavbar()">×</button>
    <h2>Dashboard Admin</h2>
    <div class="welcome-message">Selamat datang, <?php echo $adminName; ?></div>
    <ul>
      <li><a href="crud_pertanyaan.php">Kelola Pertanyaan</a></li>
      <li><a href="crud_berita.php">Kelola Galeri</a></li>
      <li><a href="view_survey.php">Lihat Hasil Survey</a></li>
    </ul>
  </div>

  <button class="open-btn" onclick="toggleNavbar()">☰</button>

  <div class="container">
    <h2>Dashboard Admin</h2>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Menu</th>
          <th>Deskripsi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><a href="crud_pertanyaan.php">Kelola Pertanyaan</a></td>
          <td>Tambahkan, ubah, atau hapus pertanyaan yang akan ditampilkan.</td>
        </tr>
        <tr>
          <td><a href="crud_berita.php">Kelola Galeri</a></td>
          <td>Kelola gambar dan berita yang akan ditampilkan di galeri.</td>
        </tr>
        <tr>
          <td><a href="view_survey.php">Lihat Hasil Survey</a></td>
          <td>Lihat hasil survey yang telah diisi oleh pengguna.</td>
        </tr>
      </tbody>
    </table>
    <br>
    <a href="index.html">Kembali ke home</a>
  </div>

  <script src="script.js"></script>
</body>

</html>
