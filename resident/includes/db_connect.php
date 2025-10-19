<?php
$host = '127.0.0.1';
$dbname = 'u539413584_db';
$username = 'u539413584_admin';
$password = 'Q5b&kOh+2';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    $host = "127.0.0.1";
    $dbname = "barangay468_db";
    $username = "root";
    $password = "";
    //$error = 'Database connection failed: ' . $e->getMessage();
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
?>
