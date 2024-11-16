<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $examId = $_POST['examId'];
    $studentId = $_POST['studentId'];
    $studentScore = $_POST['studentScore'];
    $gradeId = isset($_POST['gradeId']) ? $_POST['gradeId'] : null;

    if ($gradeId) {
        // Update the existing grade
        $sql = "UPDATE grades SET grade = ? WHERE grade_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $studentScore, $gradeId);
        $stmt->execute();
        $stmt->close();
        
        echo "Score updated successfully";
    } else {
        // Check if the grade already exists for the same exam, student, and course
        $check_sql = "SELECT * FROM grades WHERE ExamID = ? AND students_id = ? AND CourseID = (SELECT CourseID FROM exam WHERE ExamID = ?)";
        $stmt = $con->prepare($check_sql);
        $stmt->bind_param("iii", $examId, $studentId, $examId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Error: This student has already been marked for this exam and course.";
            $stmt->close();
            $con->close();
            exit;
        }

        $stmt->close();

        // Save the student grade in the grades table
        $sql = "INSERT INTO grades (grade, ExamID, students_id, CourseID) 
                VALUES (?, ?, ?, (SELECT CourseID FROM exam WHERE ExamID = ?))";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("siii", $studentScore, $examId, $studentId, $examId);
        $stmt->execute();
        $stmt->close();

        // Count the number of records for the same exam, course, and student
        $sql = "SELECT COUNT(*) AS record_count 
                FROM grades 
                WHERE CourseID = (SELECT CourseID FROM exam WHERE ExamID = ?) 
                AND students_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $examId, $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $recordCount = $row['record_count'];
        $stmt->close();

        if ($recordCount > 1) {
            // Sum the student scores for the same CourseID and students_id
            $sql = "SELECT SUM(grade) AS total_score 
                    FROM grades 
                    WHERE CourseID = (SELECT CourseID FROM exam WHERE ExamID = ?) 
                    AND students_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ii", $examId, $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $totalScore = $row['total_score'];
            $stmt->close();

            // Save the total score in the mark table
            $sql = "INSERT INTO mark (final_exams_score, students_id, ExamID, CourseID, YearID) 
                    VALUES (?, ?, ?, (SELECT CourseID FROM exam WHERE ExamID = ?), (SELECT YearID FROM exam WHERE ExamID = ?))
                    ON DUPLICATE KEY UPDATE final_exams_score = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("siiiii", $totalScore, $studentId, $examId, $examId, $examId, $totalScore);
            $stmt->execute();
            $stmt->close();
        }

        echo "Score saved successfully";
    }

    $con->close();
}
?>
