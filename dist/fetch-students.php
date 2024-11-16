<?php
include 'connection.php';

$sql = "SELECT students_id , name FROM student
";
$result = $con->query($sql);

$students = array();
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

$con->close();

echo json_encode($students);
?>
