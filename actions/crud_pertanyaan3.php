<?php
session_start();
include 'config.php';

// Tambah pertanyaan
if (isset($_POST['add'])) {
  $id_admin = $_SESSION['admin_id'];
  $tipe_pertanyaan = $_POST['tipe_pertanyaan'];
  $pertanyaan = $_POST['pertanyaan'];
  $opsi = isset($_POST['opsi']) ? implode(',', $_POST['opsi']) : '';

  $sql = "INSERT INTO pertanyaan (id_admin, tipe_pertanyaan, pertanyaan, opsi) VALUES ('$id_admin', '$tipe_pertanyaan', '$pertanyaan', '$opsi')";
  $conn->query($sql);
  header('Location: crud_pertanyaan.php');
}

// Update pertanyaan
if (isset($_POST['update'])) {
  $id_pertanyaan = $_POST['id_pertanyaan'];
  $tipe_pertanyaan = $_POST['tipe_pertanyaan'];
  $pertanyaan = $_POST['pertanyaan'];
  $opsi = isset($_POST['opsi']) ? implode(',', $_POST['opsi']) : '';

  $sql = "UPDATE pertanyaan SET tipe_pertanyaan='$tipe_pertanyaan', pertanyaan='$pertanyaan', opsi='$opsi' WHERE id_pertanyaan='$id_pertanyaan'";
  $conn->query($sql);
  header('Location: crud_pertanyaan.php');
}

// Hapus pertanyaan
if (isset($_GET['delete'])) {
  $id_pertanyaan = $_GET['delete'];
  $sql = "DELETE FROM pertanyaan WHERE id_pertanyaan='$id_pertanyaan'";
  $conn->query($sql);
  header('Location: crud_pertanyaan.php');
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>CRUD Pertanyaan</title>
</head>

<body>
  <h2>Tambah Pertanyaan</h2>
  <form method="post" action="">
    <label>Tipe Pertanyaan:</label>
    <select name="tipe_pertanyaan" id="tipe_pertanyaan" onchange="toggleOpsi()">
      <option value="isian singkat">Isian Singkat</option>
      <option value="multiple choice">Multiple Choice</option>
    </select><br>

    <label>Pertanyaan:</label>
    <input type="text" name="pertanyaan" required><br>

    <div id="opsi_container" style="display:none;">
      <label>Opsi:</label>
      <div id="opsi_fields">
        <input type="text" name="opsi[]" placeholder="Opsi 1"><br>
      </div>
      <button type="button" onclick="addOpsi()">Tambah Opsi</button><br>
    </div>

    <input type="submit" name="add" value="Tambah Pertanyaan">
  </form>

  <h2>Daftar Pertanyaan</h2>
  <table border="1">
    <tr>
      <th>ID</th>
      <th>Tipe Pertanyaan</th>
      <th>Pertanyaan</th>
      <th>Opsi</th>
      <th>Aksi</th>
    </tr>
    <?php
    $sql = "SELECT * FROM pertanyaan";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) :
    ?>
      <tr>
        <td><?php echo $row['id_pertanyaan']; ?></td>
        <td><?php echo $row['tipe_pertanyaan']; ?></td>
        <td><?php echo $row['pertanyaan']; ?></td>
        <td><?php echo $row['opsi']; ?></td>
        <td>
          <a href="crud_pertanyaan.php?edit=<?php echo $row['id_pertanyaan']; ?>">Edit</a>
          <a href="crud_pertanyaan.php?delete=<?php echo $row['id_pertanyaan']; ?>">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <?php if (isset($_GET['edit'])) :
    $id_pertanyaan = $_GET['edit'];
    $sql = "SELECT * FROM pertanyaan WHERE id_pertanyaan='$id_pertanyaan'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
  ?>
    <h2>Edit Pertanyaan</h2>
    <form method="post" action="">
      <input type="hidden" name="id_pertanyaan" value="<?php echo $row['id_pertanyaan']; ?>">
      <label>Tipe Pertanyaan:</label>
      <select name="tipe_pertanyaan" id="edit_tipe_pertanyaan" onchange="toggleEditOpsi()">
        <option value="isian singkat" <?php if ($row['tipe_pertanyaan'] == 'isian singkat') echo 'selected'; ?>>Isian Singkat</option>
        <option value="multiple choice" <?php if ($row['tipe_pertanyaan'] == 'multiple choice') echo 'selected'; ?>>Multiple Choice</option>
      </select><br>

      <label>Pertanyaan:</label>
      <input type="text" name="pertanyaan" value="<?php echo $row['pertanyaan']; ?>" required><br>

      <div id="edit_opsi_container" style="<?php if ($row['tipe_pertanyaan'] == 'isian singkat') echo 'display:none;'; ?>">
        <label>Opsi:</label>
        <div id="edit_opsi_fields">
          <?php
          $opsi = explode(',', $row['opsi']);
          foreach ($opsi as $index => $value) :
          ?>
            <input type="text" name="opsi[]" value="<?php echo $value; ?>" placeholder="Opsi <?php echo $index + 1; ?>"><br>
          <?php endforeach; ?>
        </div>
        <button type="button" onclick="addEditOpsi()">Tambah Opsi</button><br>
      </div>

      <input type="submit" name="update" value="Update Pertanyaan">
    </form>
  <?php endif; ?>


  

  <br>
  <a href="dashboard.php">Kembali ke Dashboard</a>

  

</body>

</html>