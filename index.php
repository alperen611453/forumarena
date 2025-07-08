<?php
session_start();
include 'config.php';

// Kategorileri çek
$kategori_sorgu = $conn->query("SELECT * FROM categories ORDER BY name ASC");

// Kategori filtresi varsa filtrele
$where_kategori = '';
if (isset($_GET['kategori_id']) && is_numeric($_GET['kategori_id'])) {
    $kategori_id = intval($_GET['kategori_id']);
    $where_kategori = "WHERE t.category_id = $kategori_id";
}

// Konuları çekelim
$sql = "SELECT t.id, t.title, t.created_at, u.username FROM threads t LEFT JOIN users u ON t.user_id = u.id $where_kategori ORDER BY t.created_at DESC";
$konu_sorgu = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>ForumArena - Anasayfa</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<header class="navbar">
    <div class="logo">ForumArena</div>
    <div class="nav-links">
        <a href="index.php">Anasayfa</a>
        <?php if (isset($_SESSION['kullanici_adi'])): ?>
            <span>Hoşgeldin, <?= htmlspecialchars($_SESSION['kullanici_adi']) ?></span>
            <a href="logout.php">Çıkış</a>
        <?php else: ?>
            <a href="login.php">Giriş</a>
        <?php endif; ?>
        <a href="yeni_konu.php" class="btn">+ Yeni Konu Aç</a>
    </div>
</header>

<div class="main-container">

    <!-- Kategori Menüsü -->
    <div class="kategori-menu">
        <?php while ($kategori = $kategori_sorgu->fetch_assoc()): 
            $active = (isset($_GET['kategori_id']) && intval($_GET['kategori_id']) === intval($kategori['id'])) ? 'active' : '';
        ?>
            <a href="index.php?kategori_id=<?= $kategori['id'] ?>" class="btn kategori-btn <?= $active ?>">
                <?= htmlspecialchars($kategori['name']) ?>
            </a>
        <?php endwhile; ?>
    </div>

    <h1>Forum Konuları</h1>
    
    <?php if ($konu_sorgu->num_rows > 0): ?>
        <ul class="thread-list">
            <?php while ($konu = $konu_sorgu->fetch_assoc()): ?>
                <li class="thread-card">
                    <a href="konu.php?id=<?= $konu['id'] ?>"><?= htmlspecialchars($konu['title']) ?></a>
                    <br>
                    <small>Yazar: <?= htmlspecialchars($konu['username'] ?? 'Bilinmiyor') ?> | <?= $konu['created_at'] ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Henüz konu yok.</p>
    <?php endif; ?>

</div>
</body>
</html>
