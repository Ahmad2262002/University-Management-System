<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$teacherID = $data['teacherID'];

if (isset($teacherID)) {
    include '../dist/connection.php';
    include '../dist/security.php';

    // Delete dependent records first
    $sql_delete_teaching = "DELETE FROM teaching WHERE teacherID = ?";
    $stmt_delete_teaching = $con->prepare($sql_delete_teaching);
    $stmt_delete_teaching->bind_param("i", $teacherID);

    if ($stmt_delete_teaching->execute()) {
        // Now delete from schedule table
        $sql_delete_schedule = "DELETE FROM schedule WHERE teacherID = ?";
        $stmt_delete_schedule = $con->prepare($sql_delete_schedule);
        $stmt_delete_schedule->bind_param("i", $teacherID);

        if ($stmt_delete_schedule->execute()) {
            // Now delete the teacher record
            $sql_delete_teacher = "DELETE FROM teachers WHERE teacherID = ?";
            $stmt_delete_teacher = $con->prepare($sql_delete_teacher);
            $stmt_delete_teacher->bind_param("i", $teacherID);

            if ($stmt_delete_teacher->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error deleting teacher']);
            }

            $stmt_delete_teacher->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting dependent schedule records']);
        }

        $stmt_delete_schedule->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting dependent teaching records']);
    }

    $stmt_delete_teaching->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
