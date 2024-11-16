<?php
include('connection.php'); 

$day = $_GET['day'] ?? '';
$department = $_GET['department'] ?? '';
$curriculum_year = $_GET['curriculum_year'] ?? '';
$year_id = $_GET['year_id'] ?? '';


if (empty($day) || empty($department) || empty($curriculum_year) || empty($year_id)) {
    echo "<tr><td colspan='12'>Please select all filters to view schedules.</td></tr>";
    exit;
}

$sql = "SELECT s.schedule_id, c.name AS course_name, t.name AS teacher_name, sec.section_name, cl.class_name, 
        s.start_time, s.end_time,s.day_of_week, cy.name AS curriculum_year, d.dept_name, ay.startdate, ay.enddate
        FROM schedule s
        JOIN course c ON s.CourseID = c.CourseID
        JOIN teachers t ON s.teacherID = t.teacherID
        JOIN sections sec ON s.section_id = sec.section_id
        JOIN classes cl ON s.class_id = cl.class_id
        JOIN curriculum_year cy ON s.curriculum_year_id = cy.curriculum_year_id
        JOIN departments d ON s.department_id = d.dept_id
        JOIN academicyear ay ON s.YearID = ay.YearID
        WHERE s.day_of_week = '$day' AND s.department_id = '$department' AND s.curriculum_year_id = '$curriculum_year' AND s.YearID = '$year_id'";

$result = $con->query($sql);

if (!$result) {
    echo "Error: " . $con->error;
    exit;
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['schedule_id']}</td>";
        echo "<td>{$row['course_name']}</td>";
        echo "<td>{$row['teacher_name']}</td>";
        echo "<td>{$row['section_name']}</td>";
        echo "<td>{$row['class_name']}</td>";    
        echo "<td>{$row['day_of_week']}</td>";
        echo "<td>{$row['start_time']}</td>";
        echo "<td>{$row['end_time']}</td>";
        echo "<td>{$row['curriculum_year']}</td>";
        echo "<td>{$row['dept_name']}</td>";
        echo "<td>{$row['startdate']} - {$row['enddate']}</td>";
        echo '<td><button class="btn btn-secondary btn-sm" onclick="openUpdateModal(' . $row['schedule_id'] . ')">Update</button></td>';

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='12'>No related schedules found</td></tr>";
}
if (isset($_GET['schedule_id'])) {
  $schedule_id = $_GET['schedule_id'];

  $sql = "SELECT * FROM schedule WHERE schedule_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $schedule_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      echo json_encode($row);
  } else {
      echo json_encode(['error' => 'No schedule found']);
  }

  $stmt->close();}
?>
