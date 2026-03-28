<?php
session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['nama_admin'])) {
  header('Location: login.php');
  exit();
}

$adminName = $_SESSION['nama_admin'];
?>

<!DOCTYPE html>
<html>

<head>
  <title>Landing Page Admin</title>
  <link rel="stylesheet" type="text/css" href="style_pertanyaan.css?v=1.0">
</head>

<body>
<div class="navbar">
    <a href="crud_pertanyaan.php">Kelola Pertanyaan</a>
    <a href="crud_berita.php">Kelola Galeri</a>
    <a href="crud_tentang.php">Kelola Tentang</a>
    <a href="view_survey.php">Lihat Survey</a>
    <a href="crud_kelPert.php">Kelola Kel Pertanyaan</a>
    <a href="crud_admin.php">Kelola Admin</a>
    <a href="index.html">Logout</a>
  </div>
  <div style="padding: 20px;">
    <div class="welcome-message">Selamat datang, <?php echo $adminName; ?></div>
    <!-- Konten kosong di sini -->
  </div>
</body>

</html>