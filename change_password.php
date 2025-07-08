<?php
session_start();
require 'db.php';

// Giriş yapılmamışsa login'e yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $user_id = $_SESSION['user_id'];

    $hashed = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed, $user_id);
    if ($stmt->execute()) {
        $message = "Şifren başarıyla değiştirildi.";
    } else {
        $message = "Bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre Değiştir</title>
</head>
<body>
    <h2>Şifre Değiştir</h2>
    <form method="POST" action="">
        <label>Yeni Şifre:</label><br>
        <input type="password" name="new_password" required><br><br>
        <button type="submit">Değiştir</button>
    </form>
    <p><?php echo $message; ?></p>
</body>
</html>
