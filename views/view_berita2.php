<?php
include 'config.php';

$sql = "SELECT * FROM berita";
$berita = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Berita</title>
</head>

<body>
  <h2>Berita</h2>
  <ul>
    <?php while ($row = $berita->fetch_assoc()) : ?>
      <li>
        <h3><?php echo $row['judul']; ?></h3>
        <p><?php echo $row['isi']; ?></p>
      </li>
    <?php endwhile; ?>
  </ul>

  <br>
  <a href="index.html">Kembali ke home</a>

</body>

</html>