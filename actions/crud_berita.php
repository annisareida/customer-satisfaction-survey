<?php
session_start();
include 'config.php';

if (!isset($_SESSION['nama_admin'])) {
    header("Location: login.php");
    exit();
}

// Handle Tambah Berita
if (isset($_POST['submit_berita'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $id_admin = $_SESSION['admin_id'];
    $timestamp = $_POST['timestamp'];

    // Handle image upload
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($gambar);
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file);

    $sql = "SELECT COALESCE(MAX(urutan), 0) + 1 AS new_urutan FROM berita";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $new_urutan = $row['new_urutan'];

    // Insert query
    $sql = "INSERT INTO berita (id_admin, judul, isi, gambar, timestamp, urutan) VALUES ('$id_admin', '$judul', '$isi', '$gambar', '$timestamp', '$new_urutan')";

    if ($conn->query($sql) === TRUE) {
        echo "Berita berhasil ditambahkan.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    header('Location: crud_berita.php');
}

// Handle Update Berita
if (isset($_POST['update_berita'])) {
    $id_berita = $_POST['id_berita'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $timestamp = $_POST['timestamp'];

    // Handle image upload
    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($gambar);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file);

        // Update query with image
        $sql = "UPDATE berita SET judul='$judul', isi='$isi', gambar='$gambar', timestamp='$timestamp' WHERE id_berita='$id_berita'";
    } else {
        // Update query without image
        $sql = "UPDATE berita SET judul='$judul', isi='$isi', timestamp='$timestamp' WHERE id_berita='$id_berita'";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Berita berhasil diperbarui.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    header('Location: crud_berita.php');
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

    reorder_urutan('berita', $conn);
    header('Location: crud_berita.php');
}

function reorder_urutan($table, $conn) {
    $sql = "SET @row_number = 0";
    $conn->query($sql);

    $sql = "UPDATE $table SET urutan = (@row_number := @row_number + 1) ORDER BY urutan";
    $conn->query($sql);
}

// Ambil semua berita dari database
$sql = "SELECT * FROM berita";
$berita_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>CRUD Berita</title>
    <link rel="stylesheet" type="text/css" href="style_crudberita.css">
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

    <h2>Tambah Gambar</h2>
    <div class="form-container">
        <form method="post" action="" enctype="multipart/form-data">
            <label for="judul">Judul:</label>
            <input type="text" name="judul" required><br>
            <label for="isi">Isi:</label>
            <textarea name="isi" required></textarea><br>
            <label for="gambar">Gambar:</label>
            <input type="file" name="gambar" accept="image/*"><br>
            <label for="timestamp">Tanggal:</label>
            <input type="datetime-local" name="timestamp" required><br>
            <input type="submit" name="submit_berita" value="Tambah Berita">
        </form>
    </div>

    <h2>Daftar Gambar</h2>
    <div class="table-container">
        <table border="1">
            <tr>
                <th>ID Berita</th>
                <th>Judul</th>
                <th>Isi</th>
                <th>Gambar</th>
                <th>Waktu</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $berita_result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id_berita']; ?></td>
                    <td><?php echo $row['judul']; ?></td>
                    <td><?php echo $row['isi']; ?></td>
                    <td>
                        <?php if ($row['gambar']) : ?>
                            <img src="uploads/<?php echo $row['gambar']; ?>" alt="<?php echo $row['judul']; ?>" class="gallery-image">
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['timestamp']; ?></td>
                    <td>
                        <div class="actions">
                            <form method="post" action="" enctype="multipart/form-data">
                                <input type="hidden" name="id_berita" value="<?php echo $row['id_berita']; ?>">
                                <input type="hidden" name="judul" value="<?php echo $row['judul']; ?>">
                                <input type="hidden" name="isi" value="<?php echo $row['isi']; ?>">
                                <input type="hidden" name="timestamp" value="<?php echo $row['timestamp']; ?>">
                                <input type="submit" name="edit_berita" value="Edit">
                            </form>
                            <a href="?delete_berita=<?php echo $row['id_berita']; ?>" class="delete-link" onclick="return confirm('Apakah Anda yakin untuk menghapus berita ini?')">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <?php
    if (isset($_POST['edit_berita'])) :
        $id_berita = $_POST['id_berita'];
        $judul = $_POST['judul'];
        $isi = $_POST['isi'];
        $timestamp = $_POST['timestamp'];
    ?>
        <h2>Edit Gambar</h2>
        <div class="form-container">
            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="id_berita" value="<?php echo $id_berita; ?>">
                <label for="judul">Judul:</label>
                <input type="text" name="judul" value="<?php echo $judul; ?>" required><br>
                <label for="isi">Isi:</label>
                <textarea name="isi" required><?php echo $isi; ?></textarea><br>
                <label for="gambar">Gambar:</label>
                <input type="file" name="gambar" accept="image/*"><br>
                <label for="timestamp">Tanggal:</label>
                <input type="datetime-local" name="timestamp" value="<?php echo $timestamp; ?>" required><br>
                <input type="submit" name="update_berita" value="Update Berita">
            </form>
        </div>
    <?php endif; ?>

    <div class="back-to-dashboard">
        <a href="landing.php">Kembali ke Dashboard</a>
    </div>
</body>

</html>
