<?php
session_start();
include 'config.php';

// Tambah pertanyaan
if (isset($_POST['add'])) {
    $id_admin = $_SESSION['admin_id'];
    $tipe_pertanyaan = $_POST['tipe_pertanyaan'];
    $pertanyaan = $_POST['pertanyaan'];
    $bobot = isset($_POST['bobot']) ? $_POST['bobot'] : 0;
    $opsi = isset($_POST['opsi']) ? implode(',', $_POST['opsi']) : '';
    $id_kel = $_POST['id_kel'];

    $sql = "SELECT COALESCE(MAX(urutan_pertanyaan), 0) + 1 AS new_urutan FROM pertanyaan";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $new_urutan = $row['new_urutan'];

    $sql = "INSERT INTO pertanyaan (id_admin, tipe_pertanyaan, pertanyaan, opsi, bobot, id_kel, urutan_pertanyaan) VALUES ('$id_admin', '$tipe_pertanyaan', '$pertanyaan', '$opsi', '$bobot', '$id_kel', $new_urutan)";
    $conn->query($sql);
    header('Location: crud_pertanyaan.php');
}

// Update pertanyaan
if (isset($_POST['update'])) {
    $id_pertanyaan = $_POST['id_pertanyaan'];
    $tipe_pertanyaan = $_POST['tipe_pertanyaan'];
    $pertanyaan = $_POST['pertanyaan'];
    $id_kel = $_POST['id_kel'];

    if ($tipe_pertanyaan === 'isian singkat') {
        $bobot = 0;
        $opsi = '';
    } else {
        $bobot = isset($_POST['bobot']) ? $_POST['bobot'] : 0;
        $opsi = isset($_POST['opsi']) ? implode(',', $_POST['opsi']) : '';
    }

    $sql = "UPDATE pertanyaan SET tipe_pertanyaan='$tipe_pertanyaan', pertanyaan='$pertanyaan', opsi='$opsi', bobot='$bobot', id_kel='$id_kel' WHERE id_pertanyaan='$id_pertanyaan'";
    $conn->query($sql);
    header('Location: crud_pertanyaan.php');
}

// Hapus pertanyaan
if (isset($_GET['delete'])) {
    $id_pertanyaan = $_GET['delete'];
    $sql = "DELETE FROM pertanyaan WHERE id_pertanyaan='$id_pertanyaan'";
    $conn->query($sql);
    reorder_urutan('pertanyaan', $conn);
    header('Location: crud_pertanyaan.php');
}

function reorder_urutan($table, $conn) {
    $sql = "SET @row_number = 0";
    $conn->query($sql);

    $sql = "UPDATE $table SET urutan_pertanyaan = (@row_number := @row_number + 1) ORDER BY urutan_pertanyaan";
    $conn->query($sql);
}

// Ambil data kelompok pertanyaan untuk dropdown
$sql_kelompok = "SELECT * FROM kel_pertanyaan";
$result_kelompok = $conn->query($sql_kelompok);
?>

<!DOCTYPE html>
<html>

<head>
    <title>CRUD Pertanyaan</title>
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

    <h2>Tambah Pertanyaan</h2>
    <form method="post" action="">
        <label>Tipe Pertanyaan:</label>
        <select name="tipe_pertanyaan" id="tipe_pertanyaan" onchange="toggleOpsi()">
            <option value="isian singkat">Isian Singkat</option>
            <option value="multiple choice">Multiple Choice</option>
        </select><br>

        <label>Kelompok Pertanyaan:</label>
        <select name="id_kel">
            <?php while ($row_kelompok = $result_kelompok->fetch_assoc()) : ?>
                <option value="<?php echo $row_kelompok['id_kel']; ?>"><?php echo $row_kelompok['kelompok']; ?></option>
            <?php endwhile; ?>
        </select><br>

        <label>Pertanyaan:</label>
        <input type="text" name="pertanyaan" required><br>

        <div id="bobot_container" style="display:none;">
            <label>Bobot:</label>
            <input type="number" step="0.0001" name="bobot"><br>
        </div>

        <div id="opsi_container" style="display:none;">
            <label>Opsi:</label>
            <div id="opsi_fields">
                <input type="text" name="opsi[]" placeholder="Opsi 1"><br>
            </div>
            <button type="button" onclick="addOpsi()">Tambah Opsi</button><br>
        </div>

        <button type="submit" name="add">Tambah Pertanyaan</button>
    </form>

    <h2>Daftar Pertanyaan</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Urutan</th>
            <th>Tipe Pertanyaan</th>
            <th>Pertanyaan</th>
            <th>Opsi</th>
            <th>Bobot</th>
            <th>Kelompok</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT p.*, k.kelompok FROM pertanyaan p LEFT JOIN kel_pertanyaan k ON p.id_kel = k.id_kel";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) :
        ?>
            <tr>
                <td><?php echo $row['id_pertanyaan']; ?></td>
                <td><?php echo $row['urutan_pertanyaan']; ?></td>
                <td><?php echo $row['tipe_pertanyaan']; ?></td>
                <td><?php echo $row['pertanyaan']; ?></td>
                <td><?php echo $row['opsi']; ?></td>
                <td><?php echo $row['bobot']; ?></td>
                <td><?php echo $row['kelompok']; ?></td>
                <td>
                    <a href="crud_pertanyaan.php?edit=<?php echo $row['id_pertanyaan']; ?>" class="edit">Edit</a>
                    <a href="crud_pertanyaan.php?delete=<?php echo $row['id_pertanyaan']; ?>" onclick="return confirm('Yakin ingin menghapus pertanyaan ini?')" class="delete">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php if (isset($_GET['edit'])) :
        $id_pertanyaan = $_GET['edit'];
        $sql = "SELECT * FROM pertanyaan WHERE id_pertanyaan='$id_pertanyaan'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $result_kelompok_edit = $conn->query($sql_kelompok);
    ?>
        <h2>Edit Pertanyaan</h2>
        <form method="post" action="">
            <input type="hidden" name="id_pertanyaan" value="<?php echo $row['id_pertanyaan']; ?>">
            <label>Tipe Pertanyaan:</label>
            <select name="tipe_pertanyaan" id="edit_tipe_pertanyaan" onchange="toggleEditOpsi()">
                <option value="isian singkat" <?php if ($row['tipe_pertanyaan'] == 'isian singkat') echo 'selected'; ?>>Isian Singkat</option>
                <option value="multiple choice" <?php if ($row['tipe_pertanyaan'] == 'multiple choice') echo 'selected'; ?>>Multiple Choice</option>
            </select><br>

            <label>Kelompok Pertanyaan:</label>
            <select name="id_kel">
                <?php while ($row_kelompok = $result_kelompok_edit->fetch_assoc()) : ?>
                    <option value="<?php echo $row_kelompok['id_kel']; ?>" <?php if ($row_kelompok['id_kel'] == $row['id_kel']) echo 'selected'; ?>><?php echo $row_kelompok['kelompok']; ?></option>
                <?php endwhile; ?>
            </select><br>

            <label>Pertanyaan:</label>
            <input type="text" name="pertanyaan" value="<?php echo $row['pertanyaan']; ?>" required><br>

            <div id="edit_bobot_container" <?php if ($row['tipe_pertanyaan'] == 'isian singkat') echo 'style="display:none;"'; ?>>
                <label>Bobot:</label>
                <input type="number" step="0.0001" name="bobot" value="<?php echo $row['bobot']; ?>"><br>
            </div>

            <div id="edit_opsi_container" <?php if ($row['tipe_pertanyaan'] == 'isian singkat') echo 'style="display:none;"'; ?>>
                <label>Opsi:</label>
                <div id="edit_opsi_fields">
                    <?php
                    $opsi = explode(',', $row['opsi']);
                    foreach ($opsi as $index => $opsi_item) {
                        echo '<input type="text" name="opsi[]" value="' . $opsi_item . '" placeholder="Opsi ' . ($index + 1) . '"><br>';
                    }
                    ?>
                </div>
                <button type="button" onclick="addEditOpsi()">Tambah Opsi</button><br>
            </div>

            <button type="submit" name="update">Update Pertanyaan</button>
        </form>
    <?php endif; ?>

    <div class="back-to-dashboard">
        <a href="landing.php">Kembali ke Dashboard</a>
    </div>

    <script>
        function toggleOpsi() {
            var tipePertanyaan = document.getElementById('tipe_pertanyaan').value;
            var bobotContainer = document.getElementById('bobot_container');
            var opsiContainer = document.getElementById('opsi_container');

            if (tipePertanyaan === 'multiple choice') {
                bobotContainer.style.display = 'block';
                opsiContainer.style.display = 'block';
            } else {
                bobotContainer.style.display = 'none';
                opsiContainer.style.display = 'none';
            }
        }

        function addOpsi() {
            var opsiFields = document.getElementById('opsi_fields');
            var newOpsi = document.createElement('input');
            newOpsi.type = 'text';
            newOpsi.name = 'opsi[]';
            newOpsi.placeholder = 'Opsi ' + (opsiFields.children.length / 2 + 1);
            opsiFields.appendChild(newOpsi);
            opsiFields.appendChild(document.createElement('br'));
        }

        function toggleEditOpsi() {
            var tipePertanyaan = document.getElementById('edit_tipe_pertanyaan').value;
            var bobotContainer = document.getElementById('edit_bobot_container');
            var opsiContainer = document.getElementById('edit_opsi_container');

            if (tipePertanyaan === 'multiple choice') {
                bobotContainer.style.display = 'block';
                opsiContainer.style.display = 'block';
            } else {
                bobotContainer.style.display = 'none';
                opsiContainer.style.display = 'none';
            }
        }

        function addEditOpsi() {
            var opsiFields = document.getElementById('edit_opsi_fields');
            var newOpsi = document.createElement('input');
            newOpsi.type = 'text';
            newOpsi.name = 'opsi[]';
            newOpsi.placeholder = 'Opsi ' + (opsiFields.children.length / 2 + 1);
            opsiFields.appendChild(newOpsi);
            opsiFields.appendChild(document.createElement('br'));
        }
    </script>
</body>

</html>
