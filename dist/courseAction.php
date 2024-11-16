<?php
session_start();
include "connection.php";

$upload_url = 'uploads/Courses';

if (!file_exists($upload_url)) {
    mkdir($upload_url, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $Code = $_POST['code'];
    $CreditPrice = $_POST['creditPrice']; 

    $selectedDepartmentID = $_POST['department'];
    $Curriculum = $_POST['curriculum']; // Ensure this matches the form field name

    $credits = $_POST['credits'];
    $year_id = $_POST['year_id'];
    $status = "0";

    if (isset($_FILES['Profile_photo']) && $_FILES['Profile_photo']['error'] === UPLOAD_ERR_OK) {
        $imgName = pathinfo($_FILES['Profile_photo']['name'], PATHINFO_FILENAME);
        $extension = pathinfo($_FILES['Profile_photo']['name'], PATHINFO_EXTENSION);
        $upload_path = $upload_url . "/" . $imgName . '.' . $extension;

        if (move_uploaded_file($_FILES['Profile_photo']['tmp_name'], $upload_path)) {
            echo "Photo uploaded successfully";
        } else {
            echo "Failed to upload photo";
        }
    } else {
        echo "No photo uploaded or there was an error with the upload";
        $imgName = null;
        $upload_path = null;
    }

    // Check if the curriculum_year_id exists in the curriculum_year table
    $curriculum_check_stmt = $con->prepare("SELECT curriculum_year_id  FROM curriculum_year WHERE curriculum_year_id = ?");
    $curriculum_check_stmt->bind_param("i", $Curriculum);
    $curriculum_check_stmt->execute();
    $curriculum_check_stmt->store_result();

    if ($curriculum_check_stmt->num_rows === 0) {
        die("Error: The provided curriculum_year_id does not exist.");
    }

    $curriculum_check_stmt->close();

    // Check if the Code already exists in the course table
    $code_check_stmt = $con->prepare("SELECT CourseID FROM course WHERE Code = ?");
    $code_check_stmt->bind_param("s", $Code);
    $code_check_stmt->execute();
    $code_check_stmt->store_result();

    if ($code_check_stmt->num_rows > 0) {
        echo "<script>alert('The code you have entered already exists.'); window.location.href='Course.php';</script>";
        exit;
    }

    $code_check_stmt->close();

    // Check if the query is prepared successfully
    $stmt = $con->prepare("INSERT INTO course (name, Code, dept_id, credits, credit_price, YearID, status, picture_url, curriculum_year_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Error: " . $con->error);
    }

    $stmt->bind_param("ssiissssi", $name, $Code, $selectedDepartmentID, $credits, $CreditPrice, $year_id, $status, $upload_path, $Curriculum);

    if ($stmt->execute()) {
        echo "Course added successfully!";
        header("location:Course.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
} else {
    die("Error: Invalid request method.");
}
?>
