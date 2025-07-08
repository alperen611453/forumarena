<?php
$conn = new mysqli("localhost", "root", "", "forumarena"); // Veritabanı adını kendi veritabanına göre değiştir
if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}
