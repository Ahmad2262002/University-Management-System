<?php
include 'connection.php';

// Query to fetch the required details by joining tables
$sql = "SELECT g.grade_id, 
               g.students_id, 
               s.name AS student_name, 
               g.ExamID, 
               e.exam_type, 
               g.grade, 
               g.CourseID, 
               c.name AS course_name 
        FROM grades g
        INNER JOIN student s ON g.students_id = s.students_id
        INNER JOIN exam e ON g.ExamID = e.ExamID
        INNER JOIN course c ON g.CourseID = c.CourseID";

if (!$result = $con->query($sql)) {
    echo "Error: " . $con->error;
    $con->close();
    exit;
}

$grades = array();
while ($row = $result->fetch_assoc()) {
    $grades[] = $row;
}

echo json_encode($grades);

$con->close();
?>
