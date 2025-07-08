<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['kullanici_adi'] !== 'admin') {
    echo "Bu sayfaya erişim yetkiniz yok.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="navbar">
    <div class="logo">ForumArena</div>
    <div class="nav-links">
        <a href="index.php">Siteye Dön</a>
        <span>Admin Girişi: <?= htmlspecialchars($_SESSION['kullanici_adi']) ?></span>
    </div>
</header>

<div class="main-container">
    <h1>Admin Panel</h1>

    <h2>Kategoriler</h2>
    <ul>
        <?php
        $result = $conn->query("SELECT * FROM categories");
        while ($cat = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($cat['name']) . 
                 " <a href='delete_category.php?id=" . $cat['id'] . "' onclick='return confirm(\"Silmek istediğine emin misin?\")' class='btn'>Sil</a></li>";
        }
        ?>
    </ul>

    <h3>Yeni Kategori Ekle</h3>
    <form method="post" action="add_category.php">
        <input type="text" name="name" required placeholder="Yeni kategori adı">
        <button type="submit" class="btn">Ekle</button>
    </form>

    <hr>

    <h2>Kullanıcılar</h2>
    <table border="1" cellpadding="8">
        <tr><th>ID</th><th>Kullanıcı Adı</th><th>Kayıt Tarihi</th></tr>
        <?php
        $users = $conn->query("SELECT * FROM users");
        while ($u = $users->fetch_assoc()) {
            echo "<tr><td>{$u['id']}</td><td>{$u['username']}</td><td>{$u['created_at']}</td></tr>";
        }
        ?>
    </table>

    <hr>

    <h2>Konular</h2>
    <table border="1" cellpadding="8">
        <tr><th>ID</th><th>Başlık</th><th>Yayınlanma</th></tr>
        <?php
        $topics = $conn->query("SELECT id, title, created_at FROM threads");
        while ($t = $topics->fetch_assoc()) {
            echo "<tr><td>{$t['id']}</td><td>" . htmlspecialchars($t['title']) . "</td><td>{$t['created_at']}</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
