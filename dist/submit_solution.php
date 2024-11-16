<?php
session_start();
include "../dist/connection.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate session
    if (!isset($_SESSION['students_id']) || empty($_SESSION['students_id'])) {
        die("Student ID not found in session. Please log in again.");
    }
    if (!isset($_SESSION['CourseName']) || empty($_SESSION['CourseName'])) {
        die("Course name is not set in the session.");
    }

    // Assign variables
    $assignment_id = $_POST['assignment_id'];
    $course_id = $_POST['course_id'];
    $StudentID = $_SESSION['students_id'];
    $courseName = trim($_SESSION['CourseName']); // Trim whitespace

    // Define base upload path
    $upload_url = 'uploads/Solution';

    // Check if the student has already submitted a solution for any assignment
    $student_submission_path = $upload_url . '/students_solution/' . $courseName . "/";
    
    // Check if there are existing submissions for the student
    $already_submitted = false;
    foreach (glob($student_submission_path . '*', GLOB_ONLYDIR) as $assignmentDir) {
        if (is_dir($assignmentDir . "/" . $StudentID)) {
            $already_submitted = true;
            break;
        }
    }

    if ($already_submitted) {
        die("You have already submitted a solution for an assignment.");
    }

    // Define path for this assignment
    $upload_students_solution = $upload_url . '/students_solution/' . $courseName . "/" . $assignment_id . "/" . $StudentID;

    // Create directories recursively if they don't exist
    if (!is_dir($upload_url)) {
        if (!mkdir($upload_url, 0777, true)) {
            die("Failed to create directory '$upload_url'. Please check permissions.");
        }
    }

    if (!is_dir($upload_students_solution)) {
        if (!mkdir($upload_students_solution, 0777, true)) {
            die("Failed to create directory '$upload_students_solution'. Please check permissions.");
        }
    }

    // Allowed file extensions and maximum file size
    $allowed_extensions = ['pdf', 'doc', 'docx', 'txt', 'png', 'jpg', 'jpeg'];
    $max_file_size = 5000000; // 5MB

    // Validate file upload
    if (!isset($_FILES['file'])) {
        die("No file uploaded.");
    }

    // Get file information
    $file_name = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
    $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

    // Validate file extension
    if (!in_array($extension, $allowed_extensions)) {
        die("Only PDF, Word, text, and image files are allowed.");
    }

    // Validate file size
    if ($_FILES['file']['size'] > $max_file_size) {
        die("File is too large. Max size is 5MB.");
    }

    // Construct path for final upload
    $file_path = $upload_students_solution . "/" . $file_name . '.' . $extension;

    // Move uploaded file to final directory
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
        die("File upload failed.");
    }

    // Prepare SQL query to insert the solution
    $query = "INSERT INTO assignments (assignment_id, CourseID, solution_url, assignment_status)
              VALUES (?, ?, ?, 1)
              ON DUPLICATE KEY UPDATE solution_url = VALUES(solution_url), assignment_status = 1";

    // Execute SQL query
    if ($stmt = $con->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("iis", $assignment_id, $course_id, $file_path);

        // Execute statement
        if ($stmt->execute()) {
            echo "Success"; // Send success response
        } else {
            echo "Error submitting solution: " . $stmt->error;
        }
    } else {
        echo "Error preparing query: " . $con->error;
    }

} else {
    error_log("Invalid request method for submit_solution.php");
}
?>
