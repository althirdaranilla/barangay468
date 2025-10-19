<?php
session_start();
require_once('includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("INSERT INTO clearance_requests (number) VALUES (NULL) ");
    $datetime_now = new DateTime('now');
    $datetime_now->setTimezone(new DateTimeZone("Etc/GMT-8"));
    if($stmt->execute()){
        $last_id = $pdo->lastInsertId();;
        $hex_id = sprintf('%04X', $last_id);
        $year_now = $datetime_now->format('Y');
        $id = "CLE-" . $year_now . "-" . $hex_id;
        $user_id = $_SESSION['user_id'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $cellphone = $_POST['cellphone'];
        $address = $_POST['address'];
        $household_number = $_POST['household_number'];
        $purpose = $_POST['purpose'];

        $date_requested = $datetime_now->format('Y-m-d');

        $sql = "UPDATE clearance_requests 
            SET id=:id,
                user_id=:user_id, 
                first_name=:first_name, 
                middle_name=:middle_name, 
                last_name=:last_name, 
                email=:email, 
                cellphone=:cellphone, 
                address=:address, 
                household_number=:household_number, 
                purpose=:purpose, 
                date_requested=:date_requested,
                status=:status
            WHERE number=:number";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':user_id' => $user_id,
            ':first_name' => $first_name,
            ':middle_name' => $middle_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':cellphone' => $cellphone,
            ':address' => $address,
            ':household_number' => $household_number,
            ':purpose' => $purpose,
            ':date_requested' => $date_requested,
            ':number' => $last_id,
            ':status'=> "Pending"
        ]);
    }
    echo "<script>alert('Clearance request submitted successfully!'); window.location='RequestDocuments.php';</script>";
}
?>
