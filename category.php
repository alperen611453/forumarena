<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$category_id = intval($_GET['id']);

// Kategoriyi alalım
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Kategori bulunamadı.";
    exit;
}

$category = $result->fetch_assoc();

// O kategorideki konuları çekelim
$stmt = $conn->prepare("SELECT threads.id, threads.title, threads.created_at, users.username FROM threads JOIN users ON threads.user_id = users.id WHERE category_id = ? ORDER BY threads.created_at DESC");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$threads = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title><?php echo htmlspecialchars($category['name']); ?> - ForumArena</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($category['name']); ?> Kategorisi</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p><a href="new_thread.php?category_id=<?php echo $category_id; ?>">Yeni Konu Aç</a></p>
    <?php else: ?>
        <p>Konu açmak için <a href="login.php">giriş yapın</a>.</p>
    <?php endif; ?>

    <?php if ($threads->num_rows > 0): ?>
        <ul>
            <?php while ($thread = $threads->fetch_assoc()): ?>
                <li>
                    <a href="thread.php?id=<?php echo $thread['id']; ?>">
                        <?php echo htmlspecialchars($thread['title']); ?>
                    </a> — <?php echo htmlspecialchars($thread['username']); ?>, <?php echo $thread['created_at']; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Bu kategoride henüz konu yok.</p>
    <?php endif; ?>

    <p><a href="index.php">Ana sayfaya dön</a></p>
</body>
</html>
