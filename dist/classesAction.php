<?php 
include 'connection.php';

if (isset($_POST['class_name'], $_POST['year_id'])) {
    $class_name = $_POST['class_name'];
    $year_id = $_POST['year_id'];

    // Check if the class_name already exists in the classes table
    $class_check_stmt = $con->prepare("SELECT class_id FROM classes WHERE class_name = ? AND YearID = ?");
    $class_check_stmt->bind_param("si", $class_name, $year_id);
    $class_check_stmt->execute();
    $class_check_stmt->store_result();

    if ($class_check_stmt->num_rows > 0) {
        echo "<script>alert('The class name you have entered already exists.'); window.location.href='Classes.php';</script>";
        exit;
    }

    $class_check_stmt->close();

    $sql = "INSERT INTO classes (class_name, YearID) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $class_name, $year_id);
        $stmt->execute();
    } else {
        echo "Error preparing statement: " . $con->error;
    }
} elseif (isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];

    // First, delete related rows in the exam table
    $sql = "DELETE FROM exam WHERE room = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $class_id);
        $stmt->execute();

        // Now delete the class
        $sql = "DELETE FROM classes WHERE class_id = ?";
        $stmt = $con->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $class_id);
            $stmt->execute();
        } else {
            echo "Error preparing statement: " . $con->error;
        }
    } else {
        echo "Error preparing statement: " . $con->error;
    }
}

header("Location: Classes.php");
exit;
?>
