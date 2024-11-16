<?php
include '../dist/connection.php';

if (isset($_GET['SemesterID'])) {
    $semesterID = $_GET['SemesterID'];

    // Fetch semester details
    $query = "SELECT * FROM semister WHERE SemesterID=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $semesterID);
    $stmt->execute();
    $result = $stmt->get_result();
    $semester = $result->fetch_assoc();

    // Fetch associated courses
    $courseQuery = "SELECT CourseID FROM semister_courses WHERE SemesterID=?";
    $courseStmt = $con->prepare($courseQuery);
    $courseStmt->bind_param("i", $semesterID);
    $courseStmt->execute();
    $courseResult = $courseStmt->get_result();

    $selectedCourses = [];
    while ($courseRow = $courseResult->fetch_assoc()) {
        $selectedCourses[] = (int)$courseRow['CourseID'];
    }

    $semester['selected_courses'] = $selectedCourses;

    echo json_encode($semester);
}
?>
