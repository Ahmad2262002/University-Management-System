<?php
session_start();
include "../dist/connection.php";

// Function to display alerts and redirect
function showAlertAndRedirect($message, $redirectUrl) {
    echo '<script>
        alert("' . $message . '");
        window.location.href = "' . $redirectUrl . '";
    </script>';
    exit;
}

// Validate and sanitize input
function validateAndSanitizeInput($input, $type) {
    switch ($type) {
        case 'int':
            if (filter_var($input, FILTER_VALIDATE_INT) === false) {
                return false;
            }
            return (int)$input;
        case 'string':
            return htmlspecialchars(strip_tags($input));
        default:
            return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yearId = ($_POST['year_id']);
    $studentId = ($_POST['student_id']);
    error_log("Year ID: $yearId");
    error_log("Student ID: $studentId");
    error_log("Enroll Student: " . isset($_POST['enroll-student']));
    error_log("View Student: " . isset($_POST['view-student']));
    

    if (isset($_POST['courses'])) {
        error_log("Courses: " . print_r($_POST['courses'], true));
    } else {
        error_log("Courses: Not set");
    }

    $sql_check = "SELECT COUNT(*) FROM student WHERE students_id = ?";
    $stmt_check = $con->prepare($sql_check);
    if ($stmt_check) {
        $stmt_check->bind_param("i", $studentId);
        if ($stmt_check->execute()) {
            $stmt_check->bind_result($count);
            $stmt_check->fetch();
            if ($count === 0) {
                showAlertAndRedirect("Student ID not found!", "Student.php");
            } else {
                if (isset($_POST['enroll-student'])) {
                    if (isset($_POST['courses'])) {
                        foreach ($_POST['courses'] as $courseId) {
                            $courseId = validateAndSanitizeInput($courseId, 'int');
                            if ($courseId === false) {
                                continue; // Skip invalid course IDs
                            }
                            $sql = "INSERT INTO enrollment (students_id, CourseID, YearID) VALUES (?, ?, ?)";
                            $stmt = $con->prepare($sql);
                            if ($stmt) {
                                error_log("Course ID: $courseId");
                                $stmt->bind_param("iii", $studentId, $courseId, $yearId);
                                if ($stmt->execute()) {
                                    error_log("Insert Success: Student ID $studentId, Course ID $courseId, Year ID $yearId");
                                } else {
                                    error_log("Error executing statement: " . $stmt->error);
                                }
                            } else {
                                error_log("Error preparing statement: " . $con->error);
                            }
                        }
                        showAlertAndRedirect("Student enrolled successfully!", "Student.php");
                    } else {
                        showAlertAndRedirect("No courses selected!", "Student.php");
                    }
                } elseif (isset($_POST['view-student'])) {
                    $sql = "SELECT * FROM student WHERE students_id = ?";
                    $stmt = $con->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("i", $studentId);
                        if ($stmt->execute()) {
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                
                            // Add debug statement before redirection
                            error_log("Redirecting to viewStudent.php with Student ID: $studentId");
                
                            $_SESSION['students_id'] = $studentId; // Fixed the session key whitespace issue
                            header("Location: viewStudent.php");
                            exit;
                        } else {
                            error_log("Error executing statement: " . $stmt->error);
                        }
                    } else {
                        error_log("Error preparing statement: " . $con->error);
                    }
                }
                
            }
        } else {
            error_log("Error executing statement: " . $stmt_check->error);
            showAlertAndRedirect("An error occurred while checking the student ID.", "Student.php");
        }
    } else {
        error_log("Error preparing statement: " . $con->error);
        showAlertAndRedirect("An error occurred while preparing the statement.", "Student.php");
    }
}
?>
