<?php 
$host = '127.0.0.1';
$dbname = 'u539413584_db';
$username = 'u539413584_admin';
$password = 'Q5b&kOh+2'; // root has no password
$port = 3306;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    $_SESSION['online'] = true;
} catch (Exception $e) {
    $error = $e->getMessage();
    $err_str = (string) $error;
    echo "<script>";
    echo "console.log('Connection to database failed');";
    echo "console.log('Connecting to local database.');";
    echo "</script>";
    $_SESSION['online'] = false;
    $dbname = 'barangay468_db';
    $username = "root";
    $password = "";
    $conn = new mysqli($host, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        echo "alert('Connection to database failed: $conn->connect_error')";
    }
}