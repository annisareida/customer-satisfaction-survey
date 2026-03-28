<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

// Handle Tambah Pertanyaan
if (isset($_POST['submit_pertanyaan'])) {
  $tipe_pertanyaan = $_POST['tipe_pertanyaan'];
  $pertanyaan = $_POST['pertanyaan'];
  $opsi = isset($_POST['opsi']) ? $_POST['opsi'] : ''; // Sesuaikan dengan kolom opsi
  $id_admin = $_SESSION['admin_id'];

  // Insert query
  $sql = "INSERT INTO pertanyaan (id_admin, tipe_pertanyaan, pertanyaan, opsi) VALUES ('$id_admin', '$tipe_pertanyaan', '$pertanyaan', '$opsi')";

  if ($conn->query($sql) === TRUE) {
    echo "Pertanyaan berhasil ditambahkan.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Handle Update Pertanyaan
if (isset($_POST['update_pertanyaan'])) {
  $id_pertanyaan = $_POST['id_pertanyaan'];
  $tipe_pertanyaan = $_POST['tipe_pertanyaan'];
  $pertanyaan = $_POST['pertanyaan'];
  $opsi = isset($_POST['opsi']) ? $_POST['opsi'] : ''; // Sesuaikan dengan kolom opsi

  // Update query
  $sql = "UPDATE pertanyaan SET tipe_pertanyaan='$tipe_pertanyaan', pertanyaan='$pertanyaan', opsi='$opsi' WHERE id_pertanyaan='$id_pertanyaan'";

  if ($conn->query($sql) === TRUE) {
    echo "Pertanyaan berhasil diperbarui.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Handle Delete Pertanyaan
if (isset($_GET['delete_pertanyaan'])) {
  $id_pertanyaan = $_GET['delete_pertanyaan'];

  // Delete related ratings first
  $sql = "DELETE FROM rating WHERE id_pertanyaan='$id_pertanyaan'";
  $conn->query($sql);

  // Delete the question
  $sql = "DELETE FROM pertanyaan WHERE id_pertanyaan='$id_pertanyaan'";

  if ($conn->query($sql) === TRUE) {
    echo "Pertanyaan berhasil dihapus.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Ambil semua pertanyaan dari database
$sql = "SELECT * FROM pertanyaan";
$pertanyaan_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
  <title>CRUD Pertanyaan</title>
</head>

<body>
  <h2>Tambah Pertanyaan</h2>
  <form method="post" action="">
    Tipe Pertanyaan:
    <select name="tipe_pertanyaan" required>
      <option value="isian singkat">Isian Singkat</option>
      <option value="multiple choice">Multiple Choice</option>
      <option value="dropdown">Dropdown</option>
    </select><br>
    Pertanyaan: <input type="text" name="pertanyaan" required><br>
    Opsi (jika ada, pisahkan dengan koma): <input type="text" name="opsi"><br>
    <input type="submit" name="submit_pertanyaan" value="Tambah Pertanyaan">
  </form>

  <h2>Daftar Pertanyaan</h2>
  <table border="1">
    <tr>
      <th>ID Pertanyaan</th>
      <th>Tipe Pertanyaan</th>
      <th>Pertanyaan</th>
      <th>Opsi</th>
      <th>Aksi</th>
    </tr>
    <?php while ($row = $pertanyaan_result->fetch_assoc()) : ?>
      <tr>
        <td><?php echo $row['id_pertanyaan']; ?></td>
        <td><?php echo $row['tipe_pertanyaan']; ?></td>
        <td><?php echo $row['pertanyaan']; ?></td>
        <td><?php echo $row['opsi']; ?></td>
        <td>
          <form method="post" action="">
            <input type="hidden" name="id_pertanyaan" value="<?php echo $row['id_pertanyaan']; ?>">
            <input type="hidden" name="tipe_pertanyaan" value="<?php echo $row['tipe_pertanyaan']; ?>">
            <input type="hidden" name="pertanyaan" value="<?php echo $row['pertanyaan']; ?>">
            <input type="hidden" name="opsi" value="<?php echo $row['opsi']; ?>">
            <input type="submit" name="edit_pertanyaan" value="Edit">
          </form>
          <a href="?delete_pertanyaan=<?php echo $row['id_pertanyaan']; ?>" onclick="return confirm('Apakah Anda yakin untuk menghapus pertanyaan ini?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <?php
  if (isset($_POST['edit_pertanyaan'])) :
    $id_pertanyaan = $_POST['id_pertanyaan'];
    $tipe_pertanyaan = $_POST['tipe_pertanyaan'];
    $pertanyaan = $_POST['pertanyaan'];
    $opsi = $_POST['opsi'];
  ?>
    <h2>Edit Pertanyaan</h2>
    <form method="post" action="">
      <input type="hidden" name="id_pertanyaan" value="<?php echo $id_pertanyaan; ?>">
      Tipe Pertanyaan:
      <select name="tipe_pertanyaan" required>
        <option value="isian singkat" <?php if ($tipe_pertanyaan == 'isian singkat') echo 'selected'; ?>>Isian Singkat</option>
        <option value="multiple choice" <?php if ($tipe_pertanyaan == 'multiple choice') echo 'selected'; ?>>Multiple Choice</option>
        <option value="dropdown" <?php if ($tipe_pertanyaan == 'dropdown') echo 'selected'; ?>>Dropdown</option>
      </select><br>
      Pertanyaan: <input type="text" name="pertanyaan" value="<?php echo $pertanyaan; ?>" required><br>
      Opsi (jika ada, pisahkan dengan koma): <input type="text" name="opsi" value="<?php echo $opsi; ?>"><br>
      <input type="submit" name="update_pertanyaan" value="Update Pertanyaan">
    </form>
  <?php endif; ?>


  <br>
  <a href="dashboard.php">Kembali ke Dashboard</a>
</body>

</html>