<?php
require 'db.php';

if (!isset($_GET['category_id'])) {
    echo "Kategori ID'si belirtilmedi.";
    exit;
}

$category_id = intval($_GET['category_id']);

// Kategori adını alalım
$stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Kategori bulunamadı.";
    exit;
}
$category = $result->fetch_assoc();

echo "<h1>" . htmlspecialchars($category['name']) . " Kategorisindeki Konular</h1>";

// Konuları listele
$stmt = $conn->prepare("SELECT threads.id, threads.title, users.username, threads.created_at FROM threads JOIN users ON threads.user_id = users.id WHERE threads.category_id = ? ORDER BY threads.created_at DESC");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Bu kategoride henüz konu yok.";
} else {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li><a href='posts.php?thread_id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a> - " . htmlspecialchars($row['username']) . " (" . $row['created_at'] . ")</li>";
    }
    echo "</ul>";
}

// Yeni konu ekleme linki
echo "<a href='new_thread.php?category_id=" . $category_id . "'>Yeni Konu Ekle</a>";
?>
