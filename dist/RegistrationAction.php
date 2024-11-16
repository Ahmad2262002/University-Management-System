<?php
include('../dist/connection.php');


$name = $_POST['name'];
$email =  $_POST['email'];
$password = $_POST['password'];
$role_id = '3';
$description='registration';




$stmt = $con->prepare("INSERT INTO staff(role_id,name, email, password, description) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Error preparing statement: " . $con->error);
}

$stmt->bind_param("sssss",$role_id, $name, $email, $password, $description);

if ($stmt->execute()) {
    header('location: RegistrationSignIn.php');
} else {
    echo "Error executing statement: " . $stmt->error;
}

?>
