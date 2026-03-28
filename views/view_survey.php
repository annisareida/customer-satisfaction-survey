<?php
session_start();
include 'config.php';

if (!isset($_SESSION['nama_admin'])) {
  header("Location: login.php");
  exit();
}

// Ambil data bulan yang tersedia untuk filter
$sql_bulan = "SELECT DISTINCT MONTH(waktu_submit) AS bulan FROM pelanggan";
$bulan_result = $conn->query($sql_bulan);

// Inisialisasi variabel untuk hasil filter
$bulan_selected = isset($_GET['bulan']) ? $_GET['bulan'] : null;

// Ambil data pelanggan
$sql_pelanggan = "SELECT * FROM pelanggan";
$pelanggan = $conn->query($sql_pelanggan);

// Ambil data pertanyaan
$sql_pertanyaan = "SELECT * FROM pertanyaan";
$pertanyaan = $conn->query($sql_pertanyaan);

// Array untuk menyimpan pertanyaan berdasarkan ID
$pertanyaan_arr = [];
while ($row = $pertanyaan->fetch_assoc()) {
  $pertanyaan_arr[$row['id_pertanyaan']] = [
    'pertanyaan' => $row['pertanyaan'],
    'tipe_pertanyaan' => $row['tipe_pertanyaan']
  ];
}

// Filter data berdasarkan bulan jika dipilih
$filter_sql = "";
if ($bulan_selected) {
  $filter_sql = "AND MONTH(pelanggan.waktu_submit) = " . $bulan_selected;
}

// Ambil data rating beserta waktu_submit (jika ada) berdasarkan bulan
$sql_rating = "SELECT rating.*, pelanggan.waktu_submit 
              FROM rating 
              LEFT JOIN pelanggan ON rating.id_pelanggan = pelanggan.id_pelanggan
              WHERE 1 " . $filter_sql . " ORDER BY pelanggan.waktu_submit ASC";
$rating_result = $conn->query($sql_rating);

// Array untuk menyimpan jawaban pelanggan berdasarkan ID pelanggan
$rating_arr = [];
$waktu_submit = []; // Array untuk menyimpan waktu_submit
while ($row = $rating_result->fetch_assoc()) {
  $id_pelanggan = $row['id_pelanggan'];
  $id_pertanyaan = $row['id_pertanyaan'];
  
  if ($row['rating'] !== null) {
    $rating_arr[$id_pelanggan][$id_pertanyaan] = $row['rating'];
  } else {
    $rating_arr[$id_pelanggan][$id_pertanyaan] = $row['isian'];
  }
  
  // Simpan waktu submit jika ada
  if (isset($row['waktu_submit'])) {
    $waktu_submit[$id_pelanggan] = $row['waktu_submit'];
  }
}

// Hapus rating jika tombol hapus diklik
if (isset($_GET['delete_rating'])) {
  $id_pelanggan = $_GET['delete_rating'];

  // Hapus data rating untuk pelanggan tersebut
  $sql_delete = "DELETE FROM rating WHERE id_pelanggan='$id_pelanggan'";
  $conn->query($sql_delete);

  // Hapus data pelanggan (opsional, jika data pelanggan juga perlu dihapus)
  $sql_delete_pelanggan = "DELETE FROM pelanggan WHERE id_pelanggan='$id_pelanggan'";
  $conn->query($sql_delete_pelanggan);

  // Redirect ke halaman yang sama untuk merefresh data
  header("Location: view_survey.php?bulan=" . $bulan_selected);
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Survey - Admin Dashboard</title>
  <link rel="stylesheet" href="view_survey.css?v=1.0">
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
  <h2>Hasil Survey</h2>
  <form method="get" action="">
    <label for="bulan">Pilih Bulan:</label>
    <select name="bulan" id="bulan">
      <option value="">Semua Bulan</option>
      <?php while ($bulan = $bulan_result->fetch_assoc()) : ?>
        <option value="<?php echo $bulan['bulan']; ?>" <?php echo ($bulan['bulan'] == $bulan_selected) ? 'selected' : ''; ?>>
          <?php echo date("F", mktime(0, 0, 0, $bulan['bulan'], 1)); ?>
        </option>
      <?php endwhile; ?>
    </select>
    <button type="submit">Filter</button>
  </form>
  
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Aksi</th>
          <th>ID Pelanggan</th>
          <th>Jenis Kelamin</th>
          <th>Pekerjaan</th>
          <th>Usia</th>
          <th>Tujuan Perjalanan</th>
          <th>Waktu Submit</th>
          <th>Frekuensi Penggunaan</th>
          <?php foreach ($pertanyaan_arr as $pertanyaan) : ?>
            <th><?php echo $pertanyaan['pertanyaan']; ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $pelanggan->fetch_assoc()) : ?>
          <tr>
            <td>
              <a href="?delete_rating=<?php echo $row['id_pelanggan']; ?>" class="delete-link" onclick="return confirm('Apakah Anda yakin untuk menghapus rating pelanggan ini?')">Delete</a>
            </td>
            <td><?php echo $row['id_pelanggan']; ?></td>
            <td><?php echo $row['jenis_kelamin']; ?></td>
            <td><?php echo $row['pekerjaan']; ?></td>
            <td><?php echo $row['usia']; ?></td>
            <td><?php echo $row['tujuan_perjalanan']; ?></td>
            <td><?php echo isset($waktu_submit[$row['id_pelanggan']]) ? $waktu_submit[$row['id_pelanggan']] : 'N/A'; ?></td>
            <td><?php echo $row['frekuensi_penggunaan']; ?></td>
            <?php foreach ($pertanyaan_arr as $id_pertanyaan => $pertanyaan) : ?>
              <td>
                <?php
                if (isset($rating_arr[$row['id_pelanggan']][$id_pertanyaan])) {
                  if ($pertanyaan['tipe_pertanyaan'] == 'isian singkat') {
                    echo $rating_arr[$row['id_pelanggan']][$id_pertanyaan];
                  } elseif ($pertanyaan['tipe_pertanyaan'] == 'multiple choice') {
                    echo $rating_arr[$row['id_pelanggan']][$id_pertanyaan];
                  }
                } else {
                  echo 'N/A';
                }
                ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="back-to-dashboard">
    <a href="landing.php">Kembali ke Dashboard</a>
  </div>

</body>

</html>

<?php
$conn->close();
?>
