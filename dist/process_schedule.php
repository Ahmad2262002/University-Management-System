<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $selectedCourses = isset($_POST['courses']) ? (array)$_POST['courses'] : [];
    $teacherID = $_POST['teacher'] ?? '';
    $Day = $_POST['day'] ?? '';
    $sectionID = $_POST['section'] ?? '';
    $classID = $_POST['class'] ?? '';
    $departmentID = $_POST['department'] ?? '';
    $yearID = $_POST['year_id'] ?? '';
    $startTime = $_POST['start_time'] ?? '';
    $endTime = $_POST['end_time'] ?? '';
    $Curriculum = $_POST['curriculum_year'] ?? '';
    $semesterID = $_POST['semester'] ?? '';

    $insertScheduleQuery = "INSERT INTO schedule (CourseID, teacherID, section_id, class_id, department_id, YearID, start_time, end_time, day_of_week, curriculum_year_id ,SemesterID )
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $checkScheduleQuery = "SELECT * FROM schedule WHERE CourseID = ? AND teacherID = ? AND section_id = ? AND class_id = ? AND department_id = ? AND YearID = ? AND start_time = ? AND end_time = ? AND day_of_week = ? AND curriculum_year_id = ? AND SemesterID = ?";

    $checkStmt = $con->prepare($checkScheduleQuery);
    $insertStmt = $con->prepare($insertScheduleQuery);

    if ($checkStmt && $insertStmt) {
        foreach ($selectedCourses as $courseID) {
            $checkStmt->bind_param("iiiiisssssi", $courseID, $teacherID, $sectionID, $classID, $departmentID, $yearID, $startTime, $endTime, $Day, $Curriculum, $semesterID);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                // Schedule already exists
                echo "<script>alert('Schedule already exists.'); window.location.href='Schedules.php';</script>";
                exit;
            }

            // Schedule does not exist, proceed with insertion
            $insertStmt->bind_param("iiiiisssssi", $courseID, $teacherID, $sectionID, $classID, $departmentID, $yearID, $startTime, $endTime, $Day, $Curriculum, $semesterID);

            if (!$insertStmt->execute()) {
                echo "Error creating schedule: " . $insertStmt->error . "<br>";
            }
        }

        // Redirect after all inserts
        header("location: Schedules.php");
        exit;
    } else {
        echo "Prepare statement error: " . $con->error;
    }

    $checkStmt->close();
    $insertStmt->close();
} else {
    echo "No data received from the form.";
}

$con->close();
