<?php
session_start();
include('../dist/connection.php');
$AdminID = $_SESSION['admin_id']; // Retrieve admin_id from session

// Retrieve and sanitize input values
$name = mysqli_real_escape_string($con, $_POST['name']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$password = mysqli_real_escape_string($con, $_POST['password']);
$department = mysqli_real_escape_string($con, $_POST['department']);
$description = 'registration';
$role_id = '3';
$YearID = $_POST['year-id'];
$phone = mysqli_real_escape_string($con, $_POST['phone_number']);

$now = date('Y-m-d H:i:s');

// Check if the email already exists in the registration table
$stmt_check_email = $con->prepare("SELECT * FROM registration WHERE email = ?");
$stmt_check_email->bind_param("s", $email);
$stmt_check_email->execute();
$emailResult = $stmt_check_email->get_result();

if ($emailResult->num_rows > 0) {
    // Email is already in use
    echo "<script>alert('The email is already in use. Please use a different email.'); window.location.href = 'RegistrationSignIn.php';</script>";
    exit();
}

// Verify the academic year exists and is within the date range
$stmt = $con->prepare("SELECT * FROM academicyear WHERE YearID = ? AND ? BETWEEN startdate AND enddate");
if (!$stmt) {
    echo "Error preparing statement for academicyear: " . $con->error;
} else {
    $stmt->bind_param("is", $YearID, $now);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Insert into the staff table
        $stmt_staff = $con->prepare("INSERT INTO staff(name, email, password, description, role_id) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt_staff) {
            echo "Error preparing statement for staff: " . $con->error;
        } else {
            $stmt_staff->bind_param('sssss', $name, $email, $password, $description, $role_id);
            if ($stmt_staff->execute()) {
                echo "Record inserted successfully into staff table";

                $staffID = $con->insert_id;

                // Insert into the registration table
                $stmt_registration = $con->prepare("INSERT INTO registration(staffID, name, email, password, department_id, YearID, phone,admin_id ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt_registration) {
                    echo "Error preparing statement for registration: " . $con->error;
                } else {
                    $stmt_registration->bind_param('isssiisi', $staffID, $name, $email, $password, $department, $YearID, $phone,$AdminID);
                    if ($stmt_registration->execute()) {
                        echo "Record inserted successfully into registration table";
                        header("Location: RegistrationSignIn.php");
                    } else {
                        echo "ERROR: Could not execute registration query: " . $stmt_registration->error;
                    }
                }
            } else {
                echo "ERROR: Could not execute staff query: " . $stmt_staff->error;
            }
        }
    } else {
        echo "The YearID does not exist in the academicyear table or the current date and time is not within the academic year range";
    }
    $stmt->close();
}

$con->close();
?>
