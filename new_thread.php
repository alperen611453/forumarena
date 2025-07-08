<?php
session_start();
include 'db.php';

$category_id = $_GET['category_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $user_id = $_SESSION['user_id'];  // Giriş yapan kullanıcının id'si

    // Veritabanına ekle
    $stmt = $conn->prepare("INSERT INTO threads (category_id, user_id, title) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $category_id, $user_id, $title);
    $stmt->execute();

    // Başarılıysa kategori sayfasına yönlendir
    header("Location: threads.php?category_id=$category_id");
    exit();
}
?>

<form method="post" action="new_thread.php?category_id=<?= $category_id ?>">
    <label>Konu Başlığı:</label>
    <input type="text" name="title" required>
    <button type="submit">Ekle</button>
</form>
