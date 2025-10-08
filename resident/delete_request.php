<?php
session_start();
require_once('includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized.');
}

if (isset($_POST['table'], $_POST['id'])) {
    $allowed = ['clearance_requests', 'permit_requests', 'certificate_requests'];
    $table = $_POST['table'];
    $id = (int)$_POST['id'];

    if (!in_array($table, $allowed)) {
        exit('Invalid table.');
    }

    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    echo "Request deleted successfully.";
} else {
    echo "Missing parameters.";
}
