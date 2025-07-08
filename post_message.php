<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $thread_id = (int)$_POST['thread_id'];
    $content = $_POST['content'];
    $user_id = 1; // Şimdilik sabit kullanıcı ID

    if (empty($content)) {
        echo "Mesaj boş olamaz.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO posts (thread_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $thread_id, $user_id, $content);

    if ($stmt->execute()) {
        header("Location: posts.php?thread_id=" . $thread_id);
        exit;
    } else {
        echo "Mesaj kaydedilirken hata oluştu.";
    }
}
?>
