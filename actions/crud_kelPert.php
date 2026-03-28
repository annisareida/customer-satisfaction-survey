<?php
include 'config.php'; // Menghubungkan dengan file konfigurasi database

// Tambah Kelompok Pertanyaan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_kelompok'])) {
    $kelompok = $_POST['kelompok'];
    $sql = "INSERT INTO kel_pertanyaan (kelompok) VALUES ('$kelompok')";
    $conn->query($sql);
    header('Location: crud_kelPert.php');
}

// Update Kelompok Pertanyaan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_kelompok'])) {
    $id_kel = $_POST['id_kel'];
    $kelompok = $_POST['kelompok'];
    $sql = "UPDATE kel_pertanyaan SET kelompok='$kelompok' WHERE id_kel='$id_kel'";
    $conn->query($sql);
    header('Location: crud_kelPert.php');
}

// Delete Kelompok Pertanyaan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_kelompok'])) {
    $id_kel = $_POST['id_kel'];
    $sql = "DELETE FROM kel_pertanyaan WHERE id_kel='$id_kel'";
    $conn->query($sql);
    header('Location: crud_kelPert.php');
}

// Ambil data kelompok pertanyaan untuk ditampilkan
$sql = "SELECT * FROM kel_pertanyaan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD Kelompok Pertanyaan</title>
    <link rel="stylesheet" type="text/css" href="style_kelPert.css">
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

<h2>Kelola Kelompok Pertanyaan</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label>Nama Kelompok:</label>
    <input type="text" name="kelompok" required>
    <input type="submit" name="add_kelompok" value="Tambah Kelompok" class="add-button">
</form>

<hr>

<h2>Daftar Kelompok Pertanyaan</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama Kelompok</th>
        <th>Aksi</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_kel']; ?></td>
                <td><?php echo $row['kelompok']; ?></td>
                <td>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display:inline-block;">
                        <input type="hidden" name="id_kel" value="<?php echo $row['id_kel']; ?>">
                        <input type="text" name="kelompok" value="<?php echo $row['kelompok']; ?>" required>
                        <input type="submit" name="update_kelompok" value="Update" class="update-button">
                    </form>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display:inline-block;">
                        <input type="hidden" name="id_kel" value="<?php echo $row['id_kel']; ?>">
                        <input type="submit" name="delete_kelompok" value="Delete" class="delete-link" onclick="return confirm('Anda yakin ingin menghapus kelompok pertanyaan ini?');">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">Belum ada kelompok pertanyaan.</td>
        </tr>
    <?php endif; ?>
</table>

<br><br>
<div class="back-to-dashboard">
    <a href="landing.php" class="dashboard-link">Kembali ke Dashboard</a>
</div>

</body>
</html>
