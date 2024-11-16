<?php
include 'connection.php';

// Fixed SQL query
$sql = "SELECT exam.ExamID, exam.exam_type, exam.CourseID, course.name as course_name
        FROM exam
        JOIN course ON course.CourseID = exam.CourseID
        WHERE exam.exam_type_id IS NOT NULL";

$result = $con->query($sql);

if (!$result) {
    die("Error executing query: " . $con->error);
}

$exams = array();
while ($row = $result->fetch_assoc()) {
    $exams[] = $row;
}

$con->close();

echo json_encode($exams);
?>
