<?php
session_start();
include "connection.php";

$email = $_POST['email'];
$password = $_POST['password'];

// Prepare the SQL statement for admin
$stmtAdmin = $con->prepare("SELECT admin.*, staff.role_id FROM admin INNER JOIN staff ON admin.staffID = staff.staff_id WHERE admin.email =? AND admin.password =?");
$stmtAdmin->bind_param("ss", $email, $password);

// Prepare the SQL statement for teacher
$stmtTeacher = $con->prepare("SELECT * FROM teachers WHERE email =? AND password =?");
$stmtTeacher->bind_param("ss", $email, $password);

// Execute the statement for admin
$stmtAdmin->execute();
$resultAdmin = $stmtAdmin->get_result();

// Execute the statement for teacher
$stmtTeacher->execute();
$resultTeacher = $stmtTeacher->get_result();

if ($resultAdmin->num_rows > 0) {
    while ($row = $resultAdmin->fetch_assoc()) {  
        $_SESSION['email'] = $email;
        $_SESSION['admin_id'] = $row['admin_id']; // Store admin_id in session
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
            header("Location:LogIn.php?error=1");
        }
        exit();
    }
} elseif ($resultTeacher->num_rows > 0) {
    while ($row = $resultTeacher->fetch_assoc()) {  
        $_SESSION['email'] = $email;
        $_SESSION['teacherID'] = $row['teacherID'];
        header("Location:TeacherCP.php");
        exit();
    }
} else {
    // Invalid credentials
    header("Location:LogIn.php?error=1");
    exit();
}
?>
