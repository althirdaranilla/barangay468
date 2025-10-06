<?php
session_start();
require_once('includes/db_connect.php');


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $cellphone = $_POST['cellphone'];
    $address = $_POST['address'];
    $household_number = $_POST['household_number'];
    $purpose = $_POST['purpose'];
    $permit_type = $_POST['permit_type'];

    $sql = "INSERT INTO permit_requests 
            (user_id, first_name, middle_name, last_name, email, cellphone, address, household_number, purpose, permit_type)
            VALUES (:user_id, :first_name, :middle_name, :last_name, :email, :cellphone, :address, :household_number, :purpose, :permit_type)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':first_name' => $first_name,
        ':middle_name' => $middle_name,
        ':last_name' => $last_name,
        ':email' => $email,
        ':cellphone' => $cellphone,
        ':address' => $address,
        ':household_number' => $household_number,
        ':purpose' => $purpose,
        ':permit_type' => $permit_type
    ]);

    echo "<script>alert('Permit request submitted successfully!'); window.location='RequestDocuments.php';</script>";
}
?>
