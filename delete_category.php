<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['kullanici_adi'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Önce bu kategoriye bağlı konuları sil
    $conn->query("DELETE FROM threads WHERE category_id = $id");

    // Sonra kategoriyi sil
    $conn->query("DELETE FROM categories WHERE id = $id");
}

header("Location: admin.php");
exit;
