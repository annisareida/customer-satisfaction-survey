<?php
session_start();
include 'config.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['nama_admin'])) {
  header("Location: login.php");
  exit();
}

// Periksa peran admin
$sql_peran = "SELECT peran FROM admin WHERE nama_admin = ?";
$stmt = $conn->prepare($sql_peran);
$stmt->bind_param("s", $_SESSION['nama_admin']);
$stmt->execute();
$stmt->bind_result($peran);
$stmt->fetch();
$stmt->close();
if ($peran != 'admin') {
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini'); window.location.href='landing.php';</script>";
    exit();
  }

// Tambah admin baru jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin'])) {
  $nama_admin = $_POST['nama_admin'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $peran_admin = $_POST['peran_admin'];
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  
  // Tambahkan admin baru ke database
  $sql_tambah = "INSERT INTO admin (nama_admin, jenis_kelamin, peran, password) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql_tambah);
  $stmt->bind_param("ssss", $nama_admin, $jenis_kelamin, $peran_admin, $password);
  $stmt->execute();
  $stmt->close();
  
  header('Location: crud_admin.php');
}

// Hapus admin jika tombol hapus diklik
if (isset($_GET['delete_admin'])) {
  $id_admin = $_GET['delete_admin'];

  // Hapus admin dari database
  $sql_delete = "DELETE FROM admin WHERE id_admin = ?";
  $stmt = $conn->prepare($sql_delete);
  $stmt->bind_param("i", $id_admin);
  $stmt->execute();
  $stmt->close();

  // Redirect ke halaman yang sama untuk merefresh data
  header("Location: crud_admin.php");
  exit();
}

// Ambil data admin untuk ditampilkan di tabel
$sql_admin = "SELECT * FROM admin";
$admin_result = $conn->query($sql_admin);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Kelola Admin - Admin Dashboard</title>
  <link rel="stylesheet" type="text/css" href="style_crudadmin.css?v=1.0">
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
  <h2>Kelola Admin</h2>
  <form method="post" action="">
    <input type="hidden" name="add_admin" value="1">
    <label for="nama_admin">Nama Admin:</label>
    <input type="text" name="nama_admin" id="nama_admin" required>
    <br>
    <label for="jenis_kelamin">Jenis Kelamin:</label>
    <select name="jenis_kelamin" id="jenis_kelamin" required>
      <option value="Laki-laki">Laki-laki</option>
      <option value="Perempuan">Perempuan</option>
    </select>
    <br>
    <label for="peran_admin">Peran Admin:</label>
    <select name="peran_admin" id="peran_admin" required>
      <option value="admin">Admin</option>
      <option value="operator">Operator</option>
    </select>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit">Tambah Admin</button>
  </form>
  
  <h3>Daftar Admin</h3>
  <table border="1">
    <tr>
      <th>Aksi</th>
      <th>ID Admin</th>
      <th>Nama Admin</th>
      <th>Jenis Kelamin</th>
      <th>Peran</th>
    </tr>
    <?php while ($row = $admin_result->fetch_assoc()) : ?>
      <tr>
        <td>
          <a href="?delete_admin=<?php echo $row['id_admin']; ?>" class="delete-link" onclick="return confirm('Apakah Anda yakin untuk menghapus admin ini?')">Delete</a>
        </td>
        <td><?php echo $row['id_admin']; ?></td>
        <td><?php echo $row['nama_admin']; ?></td>
        <td><?php echo $row['jenis_kelamin']; ?></td>
        <td><?php echo $row['peran']; ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <br>

  <div class="back-to-dashboard">
        <a href="landing.php">Kembali ke Dashboard</a>
    </div>
    
</body>

</html>

<?php
$conn->close();
?>
