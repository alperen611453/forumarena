<?php
session_start();
include 'config.php';

if (isset($_SESSION['kullanici_adi'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($conn->real_escape_string($_POST['username']));
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    if ($password !== $password_confirm) {
        $error = "Şifreler uyuşmuyor!";
    } else if (strlen($username) < 3 || strlen($password) < 4) {
        $error = "Kullanıcı adı en az 3, şifre en az 4 karakter olmalı.";
    } else {
        // Kullanıcı adı var mı kontrol et
        $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
        if ($check->num_rows > 0) {
            $error = "Bu kullanıcı adı zaten kayıtlı.";
        } else {
            // Kayıt işlemi
            // Dilersen burada password_hash kullanabilirsin, şimdilik düz kayıt
            $insert = $conn->query("INSERT INTO users (username, password, created_at) VALUES ('$username', '$password', NOW())");
            if ($insert) {
                $success = "Kayıt başarılı! Giriş yapabilirsiniz.";
            } else {
                $error = "Kayıt sırasında hata oluştu.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kayıt Ol - ForumArena</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="login-container">
    <h1>ForumArena</h1>
    <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="success-msg"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" class="login-form">
        <input type="text" name="username" placeholder="Kullanıcı Adı" required autofocus />
        <input type="password" name="password" placeholder="Şifre" required />
        <input type="password" name="password_confirm" placeholder="Şifre Tekrar" required />
        <input type="submit" value="Kayıt Ol" />
    </form>
    <p class="register-link">Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
</div>
</body>
</html>
