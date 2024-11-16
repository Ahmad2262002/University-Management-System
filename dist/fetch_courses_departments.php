<?php
include '../dist/connection.php';

$response = [];

$courses = mysqli_query($con, "SELECT CourseID, title FROM course");
$departments = mysqli_query($con, "SELECT dept_id, dept_name FROM department");

$response['courses'] = mysqli_fetch_all($courses, MYSQLI_ASSOC);
$response['departments'] = mysqli_fetch_all($departments, MYSQLI_ASSOC);

echo json_encode($response);
?>
