<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: loginform.html");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forum Arena - Ana Sayfa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Hoş geldin, <?php echo $_SESSION['username']; ?>!</h1>
        <p>Forum sitemize hoş geldiniz. Buradan konulara katılabilirsiniz.</p>
        <a href="logout.php" class="btn">Çıkış Yap</a>
    </div>
</body>
</html>