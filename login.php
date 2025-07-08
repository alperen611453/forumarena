<?php
session_start();
include 'config.php';

if (isset($_SESSION['kullanici_adi'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Basit şifre kontrolü (şifre hash varsa, ona göre değiştir)
        if ($password === $user['password']) {
            $_SESSION['kullanici_adi'] = $username;
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Hatalı kullanıcı adı veya şifre!";
        }
    } else {
        $error = "Hatalı kullanıcı adı veya şifre!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Giriş Yap - ForumArena</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="login-container">
    <h1>ForumArena</h1>
    <form method="POST" class="login-form">
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Kullanıcı Adı" required autofocus />
        <input type="password" name="password" placeholder="Şifre" required />
        <input type="submit" value="Giriş Yap" />
    </form>
    <p class="register-link">Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
</div>
</body>
</html>
