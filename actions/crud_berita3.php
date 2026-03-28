<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit();
}

// Handle Tambah Berita
if (isset($_POST['submit_berita'])) {
  $judul = $_POST['judul'];
  $isi = $_POST['isi'];
  $id_admin = $_SESSION['admin_id'];

  // Handle image upload
  $gambar = $_FILES['gambar']['name'];
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($gambar);
  move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file);

  // Insert query
  $sql = "INSERT INTO berita (id_admin, judul, isi, gambar) VALUES ('$id_admin', '$judul', '$isi', '$gambar')";

  if ($conn->query($sql) === TRUE) {
    echo "Berita berhasil ditambahkan.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Handle Update Berita
if (isset($_POST['update_berita'])) {
  $id_berita = $_POST['id_berita'];
  $judul = $_POST['judul'];
  $isi = $_POST['isi'];

  // Handle image upload
  if ($_FILES['gambar']['name']) {
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($gambar);
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file);

    // Update query with image
    $sql = "UPDATE berita SET judul='$judul', isi='$isi', gambar='$gambar' WHERE id_berita='$id_berita'";
  } else {
    // Update query without image
    $sql = "UPDATE berita SET judul='$judul', isi='$isi' WHERE id_berita='$id_berita'";
  }

  if ($conn->query($sql) === TRUE) {
    echo "Berita berhasil diperbarui.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Handle Delete Berita
if (isset($_GET['delete_berita'])) {
  $id_berita = $_GET['delete_berita'];

  // Delete query
  $sql = "DELETE FROM berita WHERE id_berita='$id_berita'";

  if ($conn->query($sql) === TRUE) {
    echo "Berita berhasil dihapus.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Ambil semua berita dari database
$sql = "SELECT * FROM berita";
$berita_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
  <title>CRUD Berita</title>
</head>

<body>
  <h2>Tambah Gambar</h2>
  <form method="post" action="" enctype="multipart/form-data">
    Judul: <input type="text" name="judul" required><br>
    Isi: <textarea name="isi" required></textarea><br>
    Gambar: <input type="file" name="gambar" accept="image/*"><br>
    <input type="submit" name="submit_berita" value="Tambah Berita">
  </form>

  <h2>Daftar Gambar</h2>
  <table border="1">
    <tr>
      <th>ID Berita</th>
      <th>Judul</th>
      <th>Isi</th>
      <th>Gambar</th>
      <th>Aksi</th>
    </tr>
    <?php while ($row = $berita_result->fetch_assoc()) : ?>
      <tr>
        <td><?php echo $row['id_berita']; ?></td>
        <td><?php echo $row['judul']; ?></td>
        <td><?php echo $row['isi']; ?></td>
        <td>
          <?php if ($row['gambar']) : ?>
            <img src="uploads/<?php echo $row['gambar']; ?>" alt="<?php echo $row['judul']; ?>" style="width:100px;">
          <?php endif; ?>
        </td>
        <td>
          <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="id_berita" value="<?php echo $row['id_berita']; ?>">
            <input type="hidden" name="judul" value="<?php echo $row['judul']; ?>">
            <input type="hidden" name="isi" value="<?php echo $row['isi']; ?>">
            <input type="submit" name="edit_berita" value="Edit">
          </form>
          <a href="?delete_berita=<?php echo $row['id_berita']; ?>" onclick="return confirm('Apakah Anda yakin untuk menghapus berita ini?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <?php
  if (isset($_POST['edit_berita'])) :
    $id_berita = $_POST['id_berita'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
  ?>
    <h2>Edit Gambar</h2>
    <form method="post" action="" enctype="multipart/form-data">
      <input type="hidden" name="id_berita" value="<?php echo $id_berita; ?>">
      Judul: <input type="text" name="judul" value="<?php echo $judul; ?>" required><br>
      Isi: <textarea name="isi" required><?php echo $isi; ?></textarea><br>
      Gambar: <input type="file" name="gambar" accept="image/*"><br>
      <input type="submit" name="update_berita" value="Update Berita">
    </form>
  <?php endif; ?>

  <br>
  <a href="dashboard.php">Kembali ke Dashboard</a>
</body>

</html>