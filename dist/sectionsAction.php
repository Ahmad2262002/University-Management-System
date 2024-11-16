<?php
include 'connection.php';

if (isset($_POST['section_name'], $_POST['year_id'], $_POST['class_id'])) {

    $year_id = $_POST['year_id'];
    $class_id = $_POST['class_id'];
    $className = isset($_POST['class_name']) ? $_POST['class_name'] : '';

    // Concatenate the class name and section name as strings
    $section_name = $className . ' ' . $_POST['section_name'];

    // Check if the section already exists for the given class
    $check_sql = "SELECT COUNT(*) FROM sections WHERE section_name = ? AND class_id = ?";
    $check_stmt = $con->prepare($check_sql);
    if ($check_stmt) {
        $check_stmt->bind_param("si", $section_name, $class_id);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            echo "<script>alert('A section with this name already exists for the selected class. Please choose a different name.'); window.location.href = 'sections.php';</script>";
            exit;
        } else {
            // Insert into sections table
            $sql = "INSERT INTO sections (section_name, YearID, class_id) VALUES (?, ?, ?)";
            $stmt = $con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sii", $section_name, $year_id, $class_id);
                $stmt->execute();
            } else {
                echo "Error preparing statement: " . $con->error;
            }
        }
    } else {
        echo "Error preparing statement: " . $con->error;
    }
} elseif (isset($_POST['section_id'])) {
    $section_id = $_POST['section_id'];

    // First, delete related rows in the schedule table
    $sql = "DELETE FROM schedule WHERE section_id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $section_id);
        $stmt->execute();

        // Now delete the section
        $sql = "DELETE FROM sections WHERE section_id = ?";
        $stmt = $con->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $section_id);
            $stmt->execute();
        } else {
            echo "Error preparing statement: " . $con->error;
        }
    } else {
        echo "Error preparing statement: " . $con->error;
    }
}

header("Location: sections.php");
exit;
?>
