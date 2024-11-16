<?php

include 'connection.php';

$courseID = $_POST['course'];
$startdate = $_POST['startdate'];
$enddate = $_POST['enddate'];
$departmentID = $_POST['department'];

$query = "
    INSERT INTO semester (CourseID, Startdate, Enddate, DepartmentID) 
    VALUES (?, ?, ?, ?)
";
$stmt = $con->prepare($query);
$stmt->bind_param("issi", $courseID, $startdate, $enddate, $departmentID);

if ($stmt->execute()) {
    echo "Semester added successfully!";
} else {
    echo "Error: " . $stmt->error;
}
?>
