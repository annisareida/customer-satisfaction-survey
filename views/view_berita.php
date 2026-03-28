<?php
include 'config.php';

// Mengurutkan berita berdasarkan timestamp dalam urutan menurun
$sql = "SELECT * FROM berita ORDER BY timestamp DESC";
$berita = $conn->query($sql);
$all_berita = [];
while ($row = $berita->fetch_assoc()) {
    $all_berita[] = $row;
}
$total_berita = count($all_berita);
$items_per_page = 4;
$total_pages = ceil($total_berita / $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri & Berita</title>
    <link rel="stylesheet" href="style_berita.css?v=1.0">
</head>

<body>
    <h2>Galeri & Berita</h2>
    <div class="gallery-container">
        <div class="gallery-wrapper">
            <?php for ($i = 0; $i < $total_pages; $i++) : ?>
                <div class="gallery-page">
                    <?php for ($j = $i * $items_per_page; $j < ($i + 1) * $items_per_page && $j < $total_berita; $j++) : ?>
                        <div class="gallery-item">
                            <?php if (!empty($all_berita[$j]['gambar'])) : ?>
                                <img src="uploads/<?php echo $all_berita[$j]['gambar']; ?>" alt="<?php echo htmlspecialchars($all_berita[$j]['judul']); ?>" class="gallery-image">
                            <?php endif; ?>
                            <div class="gallery-content">
                                <h3><?php echo htmlspecialchars($all_berita[$j]['judul']); ?></h3>
                                <p class="timestamp">Waktu: <?php echo htmlspecialchars($all_berita[$j]['timestamp']); ?></p>
                                <div class="zz">
                                    <p class="short-text">
                                        <?php
                                        echo htmlspecialchars(substr($all_berita[$j]['isi'], 0, 100));
                                        if (strlen($all_berita[$j]['isi']) > 100) {
                                            echo '...';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php if (strlen($all_berita[$j]['isi']) > 100) : ?>
                                    <p class="full-text" style="display: none;"><?php echo htmlspecialchars($all_berita[$j]['isi']); ?></p>
                                    <a href="#" class="toggle-text">Baca selanjutnya</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        </div>
        <button class="prev">&lt;</button>
        <button class="next">&gt;</button>
    </div>

    <div class="button-container">
        <a href="index.html">Kembali ke Home</a>
    </div>

    <script src="script.js"></script>
</body>

</html>
