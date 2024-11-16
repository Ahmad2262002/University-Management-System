<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $title = $_POST['title'];
    $description = $_POST['description'];
    $yearID = $_POST['years'];
    $courseID = $_POST['courses'];
    $upload_url = 'uploads/assignment';

    if(isset($_FILES['file'])){
        $file = $_FILES['file'];
        $fileinfo = pathinfo($file['name'], PATHINFO_FILENAME);
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $upload_path = $upload_url . "/" . $fileinfo . '.' . $extension;

        // Upload file to server
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            die("Failed to upload file.");
        }
    } else {
        die("No file uploaded.");
    }

    // Insert into database
    $stmt = $con->prepare("INSERT INTO assignments (title, description, assignment_url	, CourseID, YearID) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $title, $description, $upload_path, $courseID, $yearID);
    $stmt->execute();
    header("Location: Assignments.php");
    exit();
}

if (isset($_GET['id'])) {
    // Handle delete request
    $assignment_id = $_GET['id'];

    $result = mysqli_query($con, "DELETE FROM assignments WHERE assignment_id = $assignment_id");

    if (!$result) {
        die('Invalid query: ' . mysqli_error($con));
    }

    header('Location: Assignments.php');
    exit();
}

