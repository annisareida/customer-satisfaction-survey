<?php
include 'config.php';

$sql = "SELECT * FROM galeri";
$galeri = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Galeri</title>
</head>

<body>
  <h2>Galeri</h2>
  <ul>
    <?php while ($row = $galeri->fetch_assoc()) : ?>
      <li>
        <h3><?php echo $row['nama_gambar']; ?></h3>
        <img src="<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_gambar']; ?>" />
      </li>
    <?php endwhile; ?>
  </ul>

  <br>
  <a href="index.html">Kembali ke home</a>
</body>

</html>