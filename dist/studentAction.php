<?php
session_start();
include "../dist/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yearId = $_POST['year_id'];
    $studentId = $_POST['student_id'];

    // Check if student ID exists
    $sql_check = "SELECT COUNT(*) FROM student WHERE students_id = ?";
    $stmt_check = $con->prepare($sql_check);
    if ($stmt_check) {
        $stmt_check->bind_param("i", $studentId);
        if ($stmt_check->execute()) {
            $stmt_check->bind_result($count);
            $stmt_check->fetch();
            $stmt_check->close(); // Close the result set

            if ($count === 0) {
                echo '<script>
                    alert("Student ID not found!");
                    window.location.href = "Student.php"; // Redirect back to the form
                </script>';
                exit;
            } else {
                if (isset($_POST['enroll_student'])) {
                    if (isset($_POST['courses'])) {
                        $courses = $_POST['courses'];
                        $success = true;

                        // Prepare the statement for multiple course insertions
                        $sql = "INSERT INTO enrollment (students_id, CourseID, YearID) VALUES (?, ?, ?)";
                        $stmt = $con->prepare($sql);

                        // Bind parameters
                        $stmt->bind_param("iii", $studentId, $courseId, $yearId);

                        // Execute for each selected course
                        foreach ($courses as $courseId) {
                            if (!$stmt->execute()) {
                                $success = false;
                                echo '<p>Error enrolling student for CourseID ' . $courseId . ': ' . $stmt->error . '</p>';
                            }
                        }

                        $stmt->close(); // Close the statement

                        if ($success) {
                            header("Location: Student.php");
                            exit;
                        }
                    } else {
                        echo '<p>No courses selected!</p>';
                    }
                } elseif (isset($_POST['view_student'])) {
                    // Set session variable and redirect
                    $_SESSION['students_id'] = $studentId;
                    header("Location: viewStudent.php");
                    exit;
                }
            }
        } else {
            echo '<p>Error executing statement: ' . $stmt_check->error . '</p>';
        }
    } else {
        echo '<p>Error preparing statement: ' . $con->error . '</p>';
    }
}
?>
