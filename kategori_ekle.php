<?php
session_start();
include 'config.php';

// Giriş kontrolü (isteğe bağlı)
// if (!isset($_SESSION['kullanici_adi']) || $_SESSION['kullanici_adi'] != 'admin') {
//     header("Location: index.php");
//     exit();
// }

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori_adi = $conn->real_escape_string($_POST['kategori_adi']);
    if ($kategori_adi != '') {
        $conn->query("INSERT INTO categories (name) VALUES ('$kategori_adi')");
        $message = "Kategori başarıyla eklendi.";
    } else {
        $message = "Kategori adı boş olamaz.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Kategori Ekle - ForumArena</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<header class="navbar">
    <div class="logo">ForumArena</div>
    <div class="nav-links">
        <a href="index.php">Anasayfa</a>
        <a href="yeni_konu.php">Yeni Konu</a>
    </div>
</header>

<div class="main-container">
    <h2>Kategori Ekle</h2>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="kategori_adi" placeholder="Kategori Adı" required />
        <input type="submit" value="Ekle" />
    </form>
</div>

</body>
</html>
