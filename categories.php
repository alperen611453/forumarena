<?php
require 'db.php';

$result = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kategoriler</title>
</head>
<body>
    <h2>Kategori Listesi</h2>

    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li><?php echo htmlspecialchars($row['name']); ?></li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Hiç kategori bulunamadı.</p>
    <?php endif; ?>

    <p><a href="index.php">Ana Sayfa</a></p>
</body>
</html>
