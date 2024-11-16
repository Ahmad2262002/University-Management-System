<?php

include 'coonnection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentId = $_POST['studentId'];

    $sql = "SELECT ExamID, exam_type, mark, student_score, date, CourseID, File_url, YearID, room FROM exam WHERE students_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    $exams = array();
    while ($row = $result->fetch_assoc()) {
        $exams[] = $row;
    }

    $stmt->close();
    $con->close();

    echo json_encode($exams);
}
?>
