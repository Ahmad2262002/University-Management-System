<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$register_id = $data['register_id'];

if (isset($register_id)) {
  include '../dist/connection.php';
  include '../dist/security.php';

    $sql = "DELETE FROM registration WHERE register_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $register_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting registration']);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
