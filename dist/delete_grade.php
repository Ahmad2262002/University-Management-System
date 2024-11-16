<?php
include 'connection.php';

$grade_id = $_POST['grade_id'];

$sql = "DELETE FROM grade WHERE grade_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $grade_id);

if ($stmt->execute()) {
    echo "Grade deleted successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
?>
