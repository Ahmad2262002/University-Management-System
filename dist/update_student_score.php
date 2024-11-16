<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gradeId = $_POST['gradeId'];
    $examId = $_POST['examId'];
    $studentId = $_POST['studentId'];
    $studentScore = $_POST['studentScore'];

    // Validate the provided gradeId
    if (empty($gradeId) || empty($examId) || empty($studentId) || empty($studentScore)) {
        echo "Error: Missing required fields.";
        $con->close();
        exit;
    }

    // Update the student score in the grades table
    $sql = "UPDATE grades 
            SET grade = ? 
            WHERE grade_id = ? AND ExamID = ? AND students_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("siii", $studentScore, $gradeId, $examId, $studentId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Check if the updated score affects the total score in the mark table
        $sql = "SELECT CourseID FROM exam WHERE ExamID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $examId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $courseId = $row['CourseID'];
        $stmt->close();

        // Calculate the new total score
        $sql = "SELECT SUM(grade) AS total_score 
                FROM grades 
                WHERE CourseID = ? 
                AND students_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $courseId, $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $totalScore = $row['total_score'];
        $stmt->close();

        // Delete the previous record in the mark table
        $sql = "DELETE FROM mark 
                WHERE students_id = ? AND ExamID = ? AND CourseID = ? AND YearID = (SELECT YearID FROM exam WHERE ExamID = ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iiii", $studentId, $examId, $courseId, $examId);
        $stmt->execute();
        $stmt->close();

        // Insert the new total score into the mark table
        $sql = "INSERT INTO mark (final_exams_score, students_id, ExamID, CourseID, YearID) 
                VALUES (?, ?, ?, ?, (SELECT YearID FROM exam WHERE ExamID = ?))";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("siiii", $totalScore, $studentId, $examId, $courseId, $examId);
        $stmt->execute();
        $stmt->close();

        echo "Score updated successfully";
    } else {
        echo "Error: Unable to update score.";
    }

    $con->close();
}
?>
