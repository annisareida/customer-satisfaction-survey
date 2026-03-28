<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $pekerjaan = $_POST['pekerjaan'];
  $usia = $_POST['usia'];
  $tujuan_perjalanan = $_POST['tujuan_perjalanan'];
  $frekuensi_penggunaan = $_POST['frekuensi_penggunaan'];

  $sql = "INSERT INTO pelanggan (jenis_kelamin, pekerjaan, usia, tujuan_perjalanan, frekuensi_penggunaan) 
            VALUES ('$jenis_kelamin', '$pekerjaan', $usia, '$tujuan_perjalanan', '$frekuensi_penggunaan')";

  if ($conn->query($sql) === TRUE) {
    $id_pelanggan = $conn->insert_id;

    foreach ($_POST['pertanyaan'] as $id_pertanyaan => $rating) {
      $sql = "INSERT INTO rating (id_pelanggan, id_pertanyaan, rating) VALUES ($id_pelanggan, $id_pertanyaan, $rating)";
      $conn->query($sql);
    }

    echo "Terima kasih atas partisipasi Anda!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$sql = "SELECT * FROM pertanyaan";
$pertanyaan = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Survey Kepuasan Pelanggan</title>
</head>

<body>
  <h2>Survey Kepuasan Pelanggan</h2>
  <form method="post" action="">
    Jenis Kelamin:
    <select name="jenis_kelamin" required>
      <option value="laki-laki">Laki-laki</option>
      <option value="perempuan">Perempuan</option>
    </select><br>
    Pekerjaan:
    <select name="pekerjaan" required>
      <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
      <option value="Wiraswasta">Wiraswasta</option>
      <option value="PNS">PNS</option>
      <option value="lainnya">Lainnya</option>
    </select><br>
    Usia: <input type="number" name="usia" required><br>
    Tujuan Perjalanan:
    <select name="tujuan_perjalanan" required>
      <option value="sekolah/kampus">Sekolah/Kampus</option>
      <option value="bekerja">Bekerja</option>
      <option value="jalan-jalan">Jalan-jalan</option>
      <option value="transit ke bandara">Transit ke Bandara</option>
    </select><br>
    Frekuensi Penggunaan:
    <select name="frekuensi_penggunaan" required>
      <option value="1 kali">1 kali</option>
      <option value="2-3 kali">2-3 kali</option>
      <option value=">3 kali">>3 kali</option>
    </select><br>

    <?php if ($pertanyaan->num_rows > 0) : ?>
      <?php while ($row = $pertanyaan->fetch_assoc()) : ?>
        <label><?php echo $row['pertanyaan']; ?></label><br>
        <input type="number" name="pertanyaan[<?php echo $row['id_pertanyaan']; ?>]" min="1" max="5" required><br>
      <?php endwhile; ?>
    <?php endif; ?>

    <input type="submit" value="Kirim">
  </form>
</body>

</html>