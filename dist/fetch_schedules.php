<?php
include('connection.php');

$day = $_GET['day'] ?? '';
$department = $_GET['department'] ?? '';
$curriculum_year = $_GET['curriculum_year'] ?? '';
$year_id = $_GET['year_id'] ?? '';

$sql = "SELECT s.schedule_id, c.name AS course_name, t.name AS teacher_name, sec.section_name, cl.class_name, 
        s.start_time, s.end_time, s.day_of_week, cy.name AS curriculum_year, d.dept_name, ay.startdate, ay.enddate
        FROM schedule s
        JOIN course c ON s.CourseID = c.CourseID
        JOIN teachers t ON s.teacherID = t.teacherID
        JOIN sections sec ON s.section_id = sec.section_id
        JOIN classes cl ON s.class_id = cl.class_id
        JOIN curriculum_year cy ON s.curriculum_year_id = cy.curriculum_year_id
        JOIN departments d ON s.department_id = d.dept_id
        JOIN academicyear ay ON s.YearID = ay.YearID
        WHERE 1=1";

if ($day) $sql .= " AND s.day_of_week = '" . $con->real_escape_string($day) . "'";
if ($department) $sql .= " AND s.department_id = '" . $con->real_escape_string($department) . "'";
if ($curriculum_year) $sql .= " AND s.curriculum_year_id = '" . $con->real_escape_string($curriculum_year) . "'";
if ($year_id) $sql .= " AND s.YearID = '" . $con->real_escape_string($year_id) . "'";

$result = $con->query($sql);

$schedules = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($schedules);
?>
