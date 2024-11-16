<?php
include 'connection.php';

// Retrieve parameters
$departmentID = $_POST['departmentID'];
$curriculumID = $_POST['curriculumID'];

// Query to fetch semesters based on department and curriculum year
$query = "
    SELECT s.SemesterID, s.SemesterName, s.Startdate, s.Enddate, d.dept_name as departmentName 
    FROM semister s
    JOIN departments d ON s.DepartmentID = d.dept_id
    WHERE s.DepartmentID = ? AND s.curriculum_year_id = ?
";

$stmt = $con->prepare($query);
$stmt->bind_param('ii', $departmentID, $curriculumID);
$stmt->execute();
$result = $stmt->get_result();

$semesters = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($semesters);
?>
