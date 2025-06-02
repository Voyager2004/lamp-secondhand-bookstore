<?php
$dsn = 'mysql:host=localhost;dbname=secondhand;charset=utf8mb4';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
try {
    $pdo = new PDO($dsn, 'lampuser', 'strong_pass', $options);
} catch (PDOException $e) {
    exit('Database connection failed: '.$e->getMessage());
}
?>