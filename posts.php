<?php
require 'db.php';

if (!isset($_GET['thread_id'])) {
    echo "Konu ID'si belirtilmedi.";
    exit;
}

$thread_id = intval($_GET['thread_id']);

// Konu başlığını alalım
$stmt = $conn->prepare("SELECT title FROM threads WHERE id = ?");
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Konu bulunamadı.";
    exit;
}
$thread = $result->fetch_assoc();

echo "<h1>" . htmlspecialchars($thread['title']) . "</h1>";

// Mesajları listele
$stmt = $conn->prepare("SELECT posts.content, users.username, posts.created_at FROM posts JOIN users ON posts.user_id = users.id WHERE posts.thread_id = ? ORDER BY posts.created_at ASC");
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Bu konuda henüz mesaj yok.";
} else {
    echo "<h2>Mesajlar</h2><ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li><strong>" . htmlspecialchars($row['username']) . "</strong> (" . $row['created_at'] . "):<br>" . nl2br(htmlspecialchars($row['content'])) . "</li><hr>";
    }
    echo "</ul>";
}
?>

<!-- Yeni Mesaj Ekleme Formu -->
<form action="post_message.php" method="post">
    <input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
    <textarea name="content" rows="4" cols="50" placeholder="Mesajınızı yazın..." required></textarea><br>
    <button type="submit">Gönder</button>
</form>
