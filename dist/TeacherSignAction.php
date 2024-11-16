<?php
session_start();
include('connection.php');
$upload_url = 'uploads/teachers';

if (!file_exists($upload_url)) {
    mkdir($upload_url, 0777, true);
}
$AdminID = $_SESSION['admin_id']; // Retrieve admin_id from session

$name = mysqli_real_escape_string($con, $_POST['name']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$password = mysqli_real_escape_string($con, $_POST['password']);
$department = mysqli_real_escape_string($con, $_POST['department']);
$description = 'Teacher';
$role_id = '2';
$status = "1";
$YearID = $_POST['year-id'];
$phone_number = $_POST['phone_number'];

$imgName = pathinfo($_FILES['Profile_photo']['name'], PATHINFO_FILENAME);
$extension = pathinfo($_FILES['Profile_photo']['name'], PATHINFO_EXTENSION);
$upload_path = $upload_url . "/" . $imgName . '.' . $extension;

$now = date('Y-m-d H:i:s');

// Check if email already exists
$stmt = $con->prepare("SELECT * FROM teachers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$emailResult = $stmt->get_result();

if ($emailResult->num_rows > 0) {
    echo "<script>alert('The email is already in use. Please use a different email.'); window.history.back();</script>";
    exit();
}

// Validate academic year
$stmt = $con->prepare("SELECT * FROM academicyear WHERE YearID = ? AND ? BETWEEN startdate AND enddate");
$stmt->bind_param("is", $YearID, $now);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt = $con->prepare("INSERT INTO staff(name, email, password, description, role_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $description, $role_id);
    if ($stmt->execute()) {
        echo "Record inserted successfully into staff table";

        $staffID = $con->insert_id;

        $stmt2 = $con->prepare("INSERT INTO teachers(staffID, name, email, phone_number, password, department, YearID,admin_id ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("isssssii", $staffID, $name, $email, $phone_number, $password, $department, $YearID,$AdminID);
        if ($stmt2->execute()) {
            echo "Record inserted successfully";

            $teacherID = $con->insert_id;

            if (move_uploaded_file($_FILES['Profile_photo']['tmp_name'], $upload_path)) {
                echo "Photo uploaded successfully";
            } else {
                echo "Failed to upload photo";
            }

            $stmt3 = $con->prepare("UPDATE teachers SET photo_name = ?, photo_url = ? WHERE teacherID = ?");
            $stmt3->bind_param("ssi", $imgName, $upload_path, $teacherID);
            if ($stmt3->execute()) {
                echo "Record updated successfully";

                if (isset($_POST['courses'])) {
                    foreach ($_POST['courses'] as $CourseID) {
                        $stmt4 = $con->prepare("INSERT INTO teaching(teacherID, CourseID, YearID) VALUES (?, ?, ?)");
                        $stmt4->bind_param("iii", $teacherID, $CourseID, $YearID);
                        if ($stmt4->execute()) {
                            $stmt5 = $con->prepare("UPDATE course SET status = ? WHERE CourseID = ?");
                            $stmt5->bind_param("si", $status, $CourseID);
                            if (!$stmt5->execute()) {
                                echo "ERROR: Could not execute update Course. Please check the server logs for more details.";
                            }
                        } else {
                            echo "ERROR: Could not execute insert query. Please check the server logs for more details.";
                        }
                    }
                }

                header("Location: Staff.php");
            } else {
                echo "ERROR: Could not execute update query. Please check the server logs for more details.";
            }
        } else {
            echo "ERROR: Could not execute insert query. Please check the server logs for more details.";
        }
    } else {
        echo "ERROR: Could not execute insert query. Please check the server logs for more details.";
    }
} else {
    echo "The YearID does not exist in the academicyear table or the current date and time is not within the academic year range";
}
?>
