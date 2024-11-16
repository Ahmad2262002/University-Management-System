<?php
include '../dist/connection.php';

if (isset($_POST['semesterID'])) {
    $semesterID = $_POST['semesterID'];

    $sql = "
        SELECT sc.CourseID, c.name, c.credits,credit_price,c.Code
        FROM semister_courses sc
        JOIN course c ON sc.CourseID = c.CourseID
        JOIN semister s ON sc.SemesterID = s.SemesterID
        WHERE s.SemesterID = ?";

    // Prepare the statement
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $semesterID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<ul>';
            while ($row = $result->fetch_assoc()) {
                echo '<li>';
                echo '<input type="checkbox" id="course' . $row['CourseID'] . '" name="courses[]" value="' . $row['CourseID'] . '">';
                echo '<label for="course' . $row['CourseID'] . '">' . $row['name'] . ' - ' . $row['credits'] . ' credits</label>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo 'No courses available for this semester.';
        }

        $stmt->close();
    } else {
        echo 'Error preparing the statement: ' . $con->error;
    }

    $con->close();
} else {
    echo 'Semester ID not provided.';
}
?>
