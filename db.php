<?php
$servername = "localhost";
$username = "root"; // XAMPP default kullanıcı adı
$password = "";     // XAMPP default şifre genelde boş
$dbname = "forumarena"; // senin veritabanı adın

// Bağlantı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>
