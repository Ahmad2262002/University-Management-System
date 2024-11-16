<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve POST data
    $schedule_id = $_POST['schedule_id'];
    $courseID = $_POST['courseID'];
    $teacherID = $_POST['teacherID'];
    $section_id = $_POST['section'];
    $class_id = $_POST['class'];
    $day_of_week = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $curriculum_year_id = $_POST['curriculum_year'];
    $department_id = $_POST['department'];
    $YearID = $_POST['year_id'];
    $semesterID = $_POST['semester'] ?? '';

    // Prepare SQL statement
    $sql = "UPDATE schedule SET 
                CourseID = ?, 
                teacherID = ?, 
                section_id = ?, 
                class_id = ?, 
                day_of_week = ?, 
                start_time = ?, 
                end_time = ?, 
                curriculum_year_id = ?, 
                department_id = ?, 
                YearID = ?, 
                SemesterID = ?
            WHERE schedule_id = ?";

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: ' . $con->error);
    }

    $stmt->bind_param(
        "iiissssiiiii", 
        $courseID, 
        $teacherID, 
        $section_id, 
        $class_id, 
        $day_of_week, 
        $start_time, 
        $end_time, 
        $curriculum_year_id, 
        $department_id, 
        $YearID, 
        $semesterID,
        $schedule_id
    );

    // Execute query and check for success
    if ($stmt->execute()) {
        header("Location: Schedules.php");
        exit(); // Ensure no further code is executed
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
