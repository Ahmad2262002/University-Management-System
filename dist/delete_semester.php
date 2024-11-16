<?php
include 'connection.php';

$semesterID = $_POST['SemesterID'];

$query = "DELETE FROM semester WHERE SemesterID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $semesterID);

if ($stmt->execute()) {
    echo "Semester deleted successfully!";
} else {
    echo "Error: " . $stmt->error;
}
?>
