<?php
session_start();
include 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Insert personal information into pelanggan table
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $pekerjaan = $_POST['pekerjaan'];
  $usia = $_POST['usia'];
  $tujuan_perjalanan = $_POST['tujuan_perjalanan'];
  $frekuensi_penggunaan = $_POST['frekuensi_penggunaan'];

  $sql = "INSERT INTO pelanggan (jenis_kelamin, pekerjaan, usia, tujuan_perjalanan, frekuensi_penggunaan) VALUES ('$jenis_kelamin', '$pekerjaan', '$usia', '$tujuan_perjalanan', '$frekuensi_penggunaan')";
  $conn->query($sql);
  $id_pelanggan = $conn->insert_id;

  // Insert survey answers into rating table
  foreach ($_POST['jawaban'] as $id_pertanyaan => $jawaban) {
    // Fetch question type
    $sql = "SELECT tipe_pertanyaan, opsi FROM pertanyaan WHERE id_pertanyaan = '$id_pertanyaan'";
    $result = $conn->query($sql);
    $question = $result->fetch_assoc();

    if ($question['tipe_pertanyaan'] == 'multiple choice' && isset($question['opsi'])) {
      // Find the option number
      $options = explode(',', $question['opsi']);
      $rating_value = array_search($jawaban, $options) + 1;
      $sql = "INSERT INTO rating (id_pelanggan, id_pertanyaan, rating) VALUES ('$id_pelanggan', '$id_pertanyaan', '$rating_value')";
    } elseif ($question['tipe_pertanyaan'] == 'isian singkat') {
      $sql = "INSERT INTO rating (id_pelanggan, id_pertanyaan, isian) VALUES ('$id_pelanggan', '$id_pertanyaan', '$jawaban')";
    }
    $conn->query($sql);
  }

  echo "<script>alert('Survey berhasil dikirim!');</script>";
  echo "<script>window.location.href = 'index.html';</script>";
  exit;
}

// Fetch survey questions
$sql = "SELECT * FROM pertanyaan";
$result = $conn->query($sql);
$questions = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Survey</title>
  <link rel="stylesheet" href="style_survey.css?v=1.0">
</head>

<body>
  <div style="font-size: 25px;">
    <h2 style="text-align: center;">Survey</h2>
  </div>

  <form method="post" action="">
    <h2>Data Pribadi</h2>
    <label>Jenis Kelamin:</label>
    <select name="jenis_kelamin" required>
      <option value="laki-laki">Laki-laki</option>
      <option value="perempuan">Perempuan</option>
    </select><br>

    <label>Pekerjaan:</label>
    <select name="pekerjaan" required>
      <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
      <option value="Wiraswasta">Wiraswasta</option>
      <option value="PNS">PNS</option>
      <option value="lainnya">Lainnya</option>
    </select><br>

    <label>Usia:</label>
    <input type="number" name="usia" required><br>

    <label>Tujuan Perjalanan:</label>
    <select name="tujuan_perjalanan" required>
      <option value="sekolah/kampus">Sekolah/Kampus</option>
      <option value="bekerja">Bekerja</option>
      <option value="jalan-jalan">Jalan-jalan</option>
      <option value="transit ke bandara">Transit ke Bandara</option>
    </select><br>

    <label>Frekuensi Penggunaan:</label>
    <select name="frekuensi_penggunaan" required>
      <option value="1 kali">1 kali</option>
      <option value="2-3 kali">2-3 kali</option>
      <option value=">3 kali">>3 kali</option>
    </select><br>

    <h2>Pertanyaan Survey</h2>
    <?php foreach ($questions as $question) : ?>
      <label><?php echo $question['pertanyaan']; ?></label><br>
      <?php if ($question['tipe_pertanyaan'] == 'isian singkat') : ?>
        <input type="text" name="jawaban[<?php echo $question['id_pertanyaan']; ?>]" required><br>
      <?php elseif ($question['tipe_pertanyaan'] == 'multiple choice' && isset($question['opsi'])) : ?>
        <?php $options = explode(',', $question['opsi']); ?>
        <?php foreach ($options as $index => $option) : ?>
          <div class="option">
            <input type="radio" name="jawaban[<?php echo $question['id_pertanyaan']; ?>]" value="<?php echo $option; ?>" required> <?php echo $option; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
      <br>
    <?php endforeach; ?>


    <input type="submit" value="Submit">
  </form>

  <br>
  <div class="back-to-dashboard">
    <a href="index.html">Kembali ke home</a>
  </div>
</body>

</html>

