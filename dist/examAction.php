<?php
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $examTypeID = isset($_POST['exam_type_id']) ? $_POST['exam_type_id'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $selectedCourses = isset($_POST['selected_courses']) ? $_POST['selected_courses'] : [];
    $yearID = isset($_POST['year_id']) ? $_POST['year_id'] : '';
    $upload_url = 'uploads/exams';
    $Class = isset($_POST['class_id']) ? $_POST['class_id'] : '';
    $Mark = isset($_POST['mark']) ? $_POST['mark'] : '';

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileinfo = pathinfo($file['name'], PATHINFO_FILENAME);
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $upload_path = $upload_url . "/" . $fileinfo . '.' . $extension;

        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            die("Failed to upload file.");
        }
    } else {
        die("No file uploaded.");
    }

    // Fetch the exam type name
    $stmt = $con->prepare("SELECT name FROM exam_type WHERE id = ?");
    $stmt->bind_param("i", $examTypeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $examTypeName = "";
    if ($row = $result->fetch_assoc()) {
        $examTypeName = $row['name'];
    }
    $stmt->close();

    // Validation for duplicate exams
    foreach ($selectedCourses as $courseID) {
        $stmt = $con->prepare("SELECT * FROM exam WHERE exam_type_id = ? AND CourseID = ? AND YearID = ?");
        $stmt->bind_param("iis", $examTypeID, $courseID, $yearID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('This exam already exists. Please use another name.'); window.location.href = 'exam.php';</script>";
            exit;
        }

        // Insert into the exam table
        $stmt = $con->prepare("INSERT INTO exam (exam_type_id, exam_type, date, CourseID, File_url, room, mark, YearID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssi", $examTypeID, $examTypeName, $date, $courseID, $upload_path, $Class, $Mark, $yearID); // Adjusted this line
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
    }

    header("Location: exam.php");
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $ExamID = $_POST['ExamID'];

    $sql = "DELETE FROM exam WHERE ExamID = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $ExamID);
        $stmt->execute();
        header("Location: exam.php");
        exit;
    } else {
        echo "Error preparing statement: " . $con->error;
    }
}
$con->close();
?>
