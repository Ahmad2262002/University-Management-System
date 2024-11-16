<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['lectureName'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $courseID = $_POST['courses'];
    $yearID = $_POST['year_id'];

    $targetDir = "uploads/lectures";

    // Check if file was uploaded
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $lectureName = pathinfo($file['name'], PATHINFO_FILENAME); // Changed 'lectureName' to 'name'
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION); // Changed 'lectureName' to 'name'

        $targetFilePath = $targetDir . "/" . $lectureName . "." . $extension;

        // Allow certain file formats
        $allowTypes = array('pdf', 'pptx');
        if (in_array($extension, $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                $insert = $con->query("INSERT INTO lectures (startTime, endTime, File_url, CourseID, YearID) VALUES ('" . $startTime . "', '" . $endTime . "', '" . $targetFilePath . "', '" . $courseID . "', '" . $yearID . "')");
                if ($insert) {
                    header("Location: lectures.php");
                } else {
                    echo "File upload failed, please try again. Error: " . $con->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo 'Sorry, only PDF and PPTX files are allowed to upload.';
        }
    } else {
        echo 'No file was uploaded.';
    }
}

if (isset($_GET['id'])) {
    // Handle delete request
    $lecture_id = $_GET['id'];

    $result = mysqli_query($con, "DELETE FROM lectures WHERE id = $lecture_id");

    if (!$result) {
        die('Invalid query: ' . mysqli_error($con));
    }

    header('Location: lectures.php');
    exit();
}
?>
