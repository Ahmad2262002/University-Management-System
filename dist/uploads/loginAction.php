<?php
session_start();
include "connection.php";

$email = $_POST['email'];
$password = $_POST['password'];

// Prepare the SQL statement
$stmt = $con->prepare("SELECT admin.*, staff.role_id FROM admin INNER JOIN staff ON admin.staffID = staff.staff_id WHERE admin.email =? AND admin.password =?");
$stmt->bind_param("ss", $email, $password);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION['email'] = $email;
        $_SESSION['admin-id'] = $row['admin-id']; // Store admin_id in session
        if ($row['role_id'] == 1) {
            // If role_id is 1 (admin)
            header("Location:AdminCP.php");
        } elseif ($row['role_id'] == 2) {
            // If role_id is 2 (teacher)
            header("Location:TeacherCP.php");
        } elseif ($row['role_id'] == 3) {
            // If role_id is 3 (registration)
            header("Location:RegistrationCP.php");
        } else {
            header("Location:LogIn.php");
        }
        exit();
    }
} else {
    header("Location: LogIn.php?error=1");
    exit();
}
?>
