<?php
include 'connection.php';

if (isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];

    $sql = "SELECT * FROM schedule WHERE schedule_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No schedule found']);
    }

    $stmt->close();
}
$con->close();

