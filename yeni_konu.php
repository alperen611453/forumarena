<?php
session_start();
include 'config.php';

if (!isset($_SESSION['kullanici_adi'])) {
    header("Location: login.php");
    exit();
}

// Kategorileri çek
$categories = $conn->query("SELECT * FROM categories ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category_id = intval($_POST['category']);
    $kullanici = $_SESSION['kullanici_adi'];

    $userResult = $conn->query("SELECT id FROM users WHERE username = '$kullanici'");
    $userRow = $userResult->fetch_assoc();
    $user_id = $userRow['id'];

    $conn->query("INSERT INTO threads (title, content, created_at, user_id, category_id) VALUES ('$title', '$content', NOW(), $user_id, $category_id)");
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Yeni Konu Aç - ForumArena</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<header class="navbar">
    <div class="logo">ForumArena</div>
    <div class="nav-links">
        <a href="index.php">Anasayfa</a>
        <span>Hoşgeldin, <?= htmlspecialchars($_SESSION['kullanici_adi']) ?></span>
        <a href="logout.php">Çıkış</a>
    </div>
</header>

<div class="main-container">
    <h2>Yeni Konu Aç</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Konu Başlığı" required />
        <textarea name="content" placeholder="Konu içeriğini yazın..." rows="6" required></textarea>
        
        <select name="category" required>
            <option value="">Kategori seçin</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <input type="submit" value="Yayınla" />
    </form>
</div>

</body>
</html>
