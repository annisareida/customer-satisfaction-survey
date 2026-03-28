<?php
session_start();
include 'config.php';

// Fungsi untuk mengunggah gambar
function uploadImage($imageName) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES[$imageName]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Periksa apakah file adalah gambar
    $check = getimagesize($_FILES[$imageName]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File bukan gambar.');</script>";
        $uploadOk = 0;
    }

    // Periksa apakah file sudah ada
    if (file_exists($target_file)) {
        echo "<script>alert('Maaf, file sudah ada.');</script>";
        $uploadOk = 0;
    }

    // Batasi ukuran file
    if ($_FILES[$imageName]["size"] > 500000) {
        echo "<script>alert('Maaf, ukuran file terlalu besar.');</script>";
        $uploadOk = 0;
    }

    // Batasi tipe file
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>alert('Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.');</script>";
        $uploadOk = 0;
    }

    // Periksa apakah $uploadOk adalah 0
    if ($uploadOk == 0) {
        echo "<script>alert('Maaf, file Anda tidak diunggah.');</script>";
        return null;
    // jika semuanya baik-baik saja, coba unggah file
    } else {
        if (move_uploaded_file($_FILES[$imageName]["tmp_name"], $target_file)) {
            return $target_file;
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file Anda.');</script>";
            return null;
        }
    }
}

// Tambah atau update data
if (isset($_POST['submit'])) {
    $pilihan = $_POST['pilihan'];
    $sejarah = $pilihan == 'sejarah' ? $_POST['sejarah'] : null;
    $visimisi = null;
    $struktur1 = null;
    $struktur2 = null;
    $struktur3 = null;

    if ($pilihan == 'visi dan misi') {
        $visimisi = uploadImage('visimisi');
    } elseif ($pilihan == 'struktur') {
        $struktur1 = uploadImage('struktur1');
        $struktur2 = uploadImage('struktur2');
        $struktur3 = uploadImage('struktur3');
    }

    $sql = "INSERT INTO tentang (pilihan, sejarah, visimisi, struktur1, struktur2, struktur3) VALUES ('$pilihan', '$sejarah', '$visimisi', '$struktur1', '$struktur2', '$struktur3')";
    $conn->query($sql);
    echo "<script>alert('Data berhasil disimpan.');</script>";
    echo "<script>window.location.href='crud_tentang.php';</script>";
}

// Hapus data
if (isset($_GET['delete'])) {
    $id_tentang = $_GET['delete'];
    $sql = "DELETE FROM tentang WHERE id_tentang='$id_tentang'";
    $conn->query($sql);
    echo "<script>alert('Data berhasil dihapus.');</script>";
    echo "<script>window.location.href='crud_tentang.php';</script>";
}

// Ambil data untuk ditampilkan
$sql = "SELECT * FROM tentang";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kelola Tentang - Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style_crudtentang.css?v=1.1">
    <script>
        function showInputFields() {
            var pilihan = document.getElementById("pilihan").value;
            document.getElementById("input-sejarah").style.display = "none";
            document.getElementById("input-visimisi").style.display = "none";
            document.getElementById("input-struktur").style.display = "none";

            if (pilihan == "sejarah") {
                document.getElementById("input-sejarah").style.display = "block";
            } else if (pilihan == "visi dan misi") {
                document.getElementById("input-visimisi").style.display = "block";
            } else if (pilihan == "struktur") {
                document.getElementById("input-struktur").style.display = "block";
            }
        }
    </script>
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

    <h2>Kelola Tentang</h2>

    <form method="post" enctype="multipart/form-data">
        <label for="pilihan">Pilih Kategori:</label>
        <select name="pilihan" id="pilihan" onchange="showInputFields()">
            <option value="sejarah">Sejarah</option>
            <option value="visi dan misi">Visi dan Misi</option>
            <option value="struktur">Struktur</option>
        </select>
        
        <div id="input-sejarah" style="display:none;">
            <label for="sejarah">Sejarah:</label>
            <textarea name="sejarah" id="sejarah"></textarea>
        </div>

        <div id="input-visimisi" style="display:none;">
            <label for="visimisi">Visi dan Misi:</label>
            <input type="file" name="visimisi" id="visimisi">
        </div>

        <div id="input-struktur" style="display:none;">
            <label for="struktur1">Struktur 1:</label>
            <input type="file" name="struktur1" id="struktur1"><br>
            <label for="struktur2">Struktur 2:</label>
            <input type="file" name="struktur2" id="struktur2"><br>
            <label for="struktur3">Struktur 3:</label>
            <input type="file" name="struktur3" id="struktur3"><br>
        </div>

        <button type="submit" name="submit" class="add-button">Simpan</button>
    </form>

    <h2>Daftar Tentang</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Pilihan</th>
            <th>Sejarah</th>
            <th>Visi dan Misi</th>
            <th>Struktur 1</th>
            <th>Struktur 2</th>
            <th>Struktur 3</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id_tentang']; ?></td>
            <td><?php echo $row['pilihan']; ?></td>
            <td><?php echo $row['sejarah']; ?></td>
            <td><?php echo $row['visimisi']; ?></td>
            <td><?php echo $row['struktur1']; ?></td>
            <td><?php echo $row['struktur2']; ?></td>
            <td><?php echo $row['struktur3']; ?></td>
            <td>
                <a href="crud_tentang.php?delete=<?php echo $row['id_tentang']; ?>" class="delete-link" onclick="return confirm('Apakah Anda yakin untuk menghapus data ini?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br><br>
    <div class="back-to-dashboard">
        <a href="landing.php" class="dashboard-link">Kembali ke Dashboard</a>
    </div>
</body>
</html>

