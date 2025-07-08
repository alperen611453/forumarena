<?php
session_start();
include 'config.php';

if (!isset($_SESSION['kullanici_adi'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    $comment_id = intval($_POST['comment_id']);
    $kullanici = $_SESSION['kullanici_adi'];

    $userResult = $conn->query("SELECT id FROM users WHERE username = '$kullanici'");
    $userRow = $userResult->fetch_assoc();
    $user_id = $userRow['id'];

    $check = $conn->query("SELECT * FROM comment_likes WHERE comment_id = $comment_id AND user_id = $user_id");

    if ($check->num_rows > 0) {
        $conn->query("DELETE FROM comment_likes WHERE comment_id = $comment_id AND user_id = $user_id");
    } else {
        $conn->query("INSERT INTO comment_likes (comment_id, user_id) VALUES ($comment_id, $user_id)");
    }

    $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: $referer");
    exit();
}
?>

