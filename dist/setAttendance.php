<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all required fields are set
    if (isset($_POST['course'], $_POST['lecture'], $_POST['students'], $_POST['years'], $_POST['status'])) {
        $courseId = $_POST['course'];
        $lectureId = $_POST['lecture'];
        $students = $_POST['students']; // Assuming students[] is an array of student IDs
        $year_id = $_POST['years'];
        $status = $_POST['status'];
        $teacherID = $_SESSION['teacherID'];

        $alertMessage = '';

        foreach ($students as $studentId) {
            // Check if the student already has an attendance record for this lecture
            $checkQuery = "SELECT * FROM attendance WHERE students_id = ? AND lecture_id = ?";
            $checkStmt = $con->prepare($checkQuery);
            $checkStmt->bind_param('ii', $studentId, $lectureId);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                // Attendance record already exists, prepare alert message
                $alertMessage .= "Student ID $studentId already has an attendance record for this lecture.\n";
            } else {
                // Prepare SQL statement to insert new attendance record
                $query = "INSERT INTO attendance (students_id, AttendanceDate, TeacherID, CourseID, YearID, status, lecture_id) VALUES (?, NOW(), ?, ?, ?, ?, ?)";
                $stmt = $con->prepare($query);
                if ($stmt) {
                    // Bind parameters and execute statement for each student
                    $stmt->bind_param('iiisss', $studentId, $teacherID, $courseId, $year_id, $status, $lectureId);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    // Handle statement preparation error
                    echo "Error preparing statement: " . $con->error;
                }
            }
            $checkStmt->close();
        }

        // Store the alert message in session and redirect
        $_SESSION['alert_message'] = $alertMessage;
        header('Location: Attendance.php');
        exit();
    } else {
        // Handle missing POST keys
        echo "Required fields are missing.";
    }
}
?>
