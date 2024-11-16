<?php
session_start();

include "../dist/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement for registration
    $stmt = $con->prepare("SELECT registration.*, staff.role_id FROM registration INNER JOIN staff ON registration.staffID = staff.staff_id WHERE registration.email = ? AND registration.password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $email;
        $_SESSION['register_id'] = $row['register_id'];

        if ($row['role_id'] == 1) {
            header("Location: AdminCP.php");
        } elseif ($row['role_id'] == 2) {
            header("Location: TeacherCP.php");
        } elseif ($row['role_id'] == 3) {
            header("Location: RegistrationCP.php");
        } else {
            header("Location: RegLogIn.php");
        }
        exit();
    } else {
        header("Location: RegLogIn.php?error=1");
        exit();
    }
}
?>
