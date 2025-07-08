<?php
session_start();
include 'config.php';

if (!isset($_GET['id'])) {
    die("Konu ID belirtilmedi.");
}

$id = intval($_GET['id']);

// Konu bilgisi
$sql = "SELECT t.title, t.content, t.created_at, u.username FROM threads t LEFT JOIN users u ON  t.user_id = u.id WHERE t.id = $id";
$result = $conn->query($sql);
if ($result->num_rows === 0) {
    die("Konu bulunamadı.");
}
$row = $result->fetch_assoc();

// Yorum ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $yorum = $conn->real_escape_string($_POST['yorum']);
    $user_id = $_SESSION['user_id'];
    $kullanici_adi = $_SESSION['kullanici_adi'];

    $conn->query("INSERT INTO comments (thread_id, kullanici_adi, yorum, tarih, user_id) VALUES ($id, '$kullanici_adi', '$yorum', NOW(), $user_id)");
    header("Location: konu.php?id=$id");
    exit();
}

$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

// Yorumlar + beğeni sayısı + kullanıcının beğenip beğenmediği
$yorumlar = $conn->query("
    SELECT c.id, c.kullanici_adi, c.yorum, c.tarih, 
           COUNT(cl.id) AS like_count,
           SUM(CASE WHEN cl.user_id = $current_user_id THEN 1 ELSE 0 END) AS liked_by_user
    FROM comments c
    LEFT JOIN comment_likes cl ON cl.comment_id = c.id
    WHERE c.thread_id = $id
    GROUP BY c.id
    ORDER BY c.tarih DESC
");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($row['title']) ?> - ForumArena</title>
    <link rel="stylesheet" href="style.css" />
    <style>
    .btn-like {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        color: #007BFF;
        padding: 0;
    }
    .btn-like.liked {
        color: #d6336c;
        font-weight: bold;
    }
    </style>
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
    </div>
</header>

<div class="main-container">
    <h2><?= htmlspecialchars($row['title']) ?></h2>
    <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
    <small>Yayınlanma Tarihi: <?= $row['created_at'] ?> | Yazar: <?= htmlspecialchars($row['username'] ?? 'Bilinmiyor') ?></small>
    <br><br>
    <a href="index.php" class="btn">← Geri Dön</a>

    <h3>Yorumlar</h3>
    <?php if ($yorumlar->num_rows > 0): ?>
        <?php while ($yorum = $yorumlar->fetch_assoc()): ?>
            <div class="thread-card">
                <strong><?= htmlspecialchars($yorum['kullanici_adi']) ?>:</strong>
                <p><?= nl2br(htmlspecialchars($yorum['yorum'])) ?></p>
                <small><?= $yorum['tarih'] ?></small>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="POST" action="like_comment.php" style="display:inline;">
                        <input type="hidden" name="comment_id" value="<?= $yorum['id'] ?>">
                        <button type="submit" class="btn-like <?= $yorum['liked_by_user'] ? 'liked' : '' ?>">
                            👍 <?= $yorum['like_count'] ?>
                        </button>
                    </form>
                <?php else: ?>
                    <span>👍 <?= $yorum['like_count'] ?></span>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Henüz yorum yapılmamış.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST">
            <textarea name="yorum" placeholder="Yorumunuzu yazın..." required></textarea><br>
            <input type="submit" value="Gönder" />
        </form>
    <?php else: ?>
        <p>Yorum yapmak için <a href="login.php">giriş yap</a> gerekiyor.</p>
    <?php endif; ?>
</div>
</body>
</html>
