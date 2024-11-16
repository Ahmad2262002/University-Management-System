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
                echo '<p>Student ID not found!</p>';
                exit;
            } else {
                if (isset($_POST['enroll_student'])) {
                    if (isset($_POST['courses'])) {
                        foreach ($_POST['courses'] as $courseId) {
                            $sql = "INSERT INTO enrollment (students_id, CourseID, YearID) VALUES (?, ?, ?)";
                            $stmt = $con->prepare($sql);
                            if ($stmt) {
                                $stmt->bind_param("iii", $studentId, $courseId, $yearId);
                                if ($stmt->execute()) {
                                    header("Location: Student.php");
                                    exit;
                                } else {
                                    echo '<p>Error enrolling student: ' . $stmt->error . '</p>';
                                }
                                $stmt->close(); // Close the statement
                            } else {
                                echo '<p>Error preparing statement: ' . $con->error . '</p>';
                            }
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
        $stmt_check->close(); // Close the statement
    } else {
        echo '<p>Error preparing statement: ' . $con->error . '</p>';
    }
}
?>
