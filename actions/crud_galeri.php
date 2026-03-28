<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit();
}

// Handle Tambah Gambar
if (isset($_POST['submit_gambar'])) {
  $nama_gambar = $_POST['nama_gambar'];

  // Proses upload gambar
  $target_dir = "uploads/"; // direktori tempat menyimpan gambar
  $target_file = $target_dir . basename($_FILES["gambar"]["name"]); // nama file yang diupload
  $uploadOk = 1; // status upload

  // Cek apakah file gambar benar-benar gambar atau bukan
  $check = getimagesize($_FILES["gambar"]["tmp_name"]);
  if ($check !== false) {
    $uploadOk = 1;
  } else {
    echo "File bukan gambar.";
    $uploadOk = 0;
  }

  // Cek jika file sudah ada
  if (file_exists($target_file)) {
    echo "Maaf, file sudah ada.";
    $uploadOk = 0;
  }

  // Batasi ukuran file
  if ($_FILES["gambar"]["size"] > 500000) {
    echo "Maaf, file terlalu besar.";
    $uploadOk = 0;
  }

  // Izinkan hanya format gambar tertentu
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
  ) {
    echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
    $uploadOk = 0;
  }

  // Cek status upload
  if ($uploadOk == 0) {
    echo "Maaf, file tidak terupload.";
    // Jika semua kondisi terpenuhi, coba upload file
  } else {
    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
      // Insert query
      $sql = "INSERT INTO galeri (nama_gambar, gambar) VALUES ('$nama_gambar', '$target_file')";

      if ($conn->query($sql) === TRUE) {
        echo "Gambar berhasil ditambahkan.";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    } else {
      echo "Maaf, terjadi kesalahan saat mengupload file.";
    }
  }
}

// Handle Update Gambar
if (isset($_POST['update_gambar'])) {
  $id_gambar = $_POST['id_gambar'];
  $nama_gambar = $_POST['nama_gambar'];

  // Proses upload gambar jika ada perubahan
  if (!empty($_FILES['gambar']['name'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);

    // Hapus file gambar lama jika ada
    $sql_select = "SELECT gambar FROM galeri WHERE id_gambar='$id_gambar'";
    $result = $conn->query($sql_select);
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $old_file = $row['gambar'];
      if (file_exists($old_file)) {
        unlink($old_file); // hapus file lama
      }
    }

    // Upload file gambar baru
    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
      $gambar = $target_file;
    } else {
      echo "Maaf, terjadi kesalahan saat mengupload file.";
    }
  }

  // Update query
  $sql = "UPDATE galeri SET nama_gambar='$nama_gambar', gambar='$gambar' WHERE id_gambar='$id_gambar'";

  if ($conn->query($sql) === TRUE) {
    echo "Gambar berhasil diperbarui.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Handle Delete Gambar
if (isset($_GET['delete_gambar'])) {
  $id_gambar = $_GET['delete_gambar'];

  // Select gambar yang akan dihapus
  $sql_select = "SELECT gambar FROM galeri WHERE id_gambar='$id_gambar'";
  $result = $conn->query($sql_select);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $gambar = $row['gambar'];

    // Hapus file gambar dari server
    if (file_exists($gambar)) {
      unlink($gambar); // hapus file dari direktori
    }
  }

  // Delete query
  $sql = "DELETE FROM galeri WHERE id_gambar='$id_gambar'";

  if ($conn->query($sql) === TRUE) {
    echo "Gambar berhasil dihapus.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Ambil semua gambar dari database
$sql = "SELECT * FROM galeri";
$galeri_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
  <title>CRUD Galeri</title>
</head>

<body>
  <h2>Tambah Gambar</h2>
  <form method="post" action="" enctype="multipart/form-data">
    Nama Gambar: <input type="text" name="nama_gambar" required><br>
    Gambar: <input type="file" name="gambar" required><br>
    <input type="submit" name="submit_gambar" value="Tambah Gambar">
  </form>

  <h2>Daftar Galeri</h2>
  <table border="1">
    <tr>
      <th>ID Gambar</th>
      <th>Nama Gambar</th>
      <th>Gambar</th>
      <th>Aksi</th>
    </tr>
    <?php while ($row = $galeri_result->fetch_assoc()) : ?>
      <tr>
        <td><?php echo $row['id_gambar']; ?></td>
        <td><?php echo $row['nama_gambar']; ?></td>
        <td><img src="<?php echo $row['gambar']; ?>" style="max-width: 200px; max-height: 200px;"></td>
        <td>
          <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="id_gambar" value="<?php echo $row['id_gambar']; ?>">
            <input type="hidden" name="nama_gambar" value="<?php echo $row['nama_gambar']; ?>">
            <input type="file" name="gambar" accept="image/*"><br> <!-- Input file untuk update -->
            <input type="submit" name="edit_gambar" value="Edit">
          </form>
          <a href="?delete_gambar=<?php echo $row['id_gambar']; ?>" onclick="return confirm('Apakah Anda yakin untuk menghapus gambar ini?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <?php
  if (isset($_POST['edit_gambar'])) :
    $id_gambar = $_POST['id_gambar'];
    $nama_gambar = $_POST['nama_gambar'];
  ?>
    <h2>Edit Gambar</h2>
    <form method="post" action="" enctype="multipart/form-data">
      <input type="hidden" name="id_gambar" value="<?php echo $id_gambar; ?>">
      Nama Gambar: <input type="text" name="nama_gambar" value="<?php echo $nama_gambar; ?>" required><br>
      Gambar: <input type="file" name="gambar" accept="image/*"><br> <!-- Input file untuk update -->
      <input type="submit" name="update_gambar" value="Update Gambar">
    </form>
  <?php endif; ?>

  <br>
  <a href="dashboard.php">Kembali ke Dashboard</a>
</body>

</html>