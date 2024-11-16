<?php
include "../dist/security.php";
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];

    // Start a transaction
    mysqli_begin_transaction($con);

    try {
        // Delete dependent rows from assignments table
        $delete_assignments_sql = "DELETE FROM assignments WHERE CourseID = ?";
        $stmt = $con->prepare($delete_assignments_sql);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();

        // Delete dependent rows from lectures table
        $delete_lectures_sql = "DELETE FROM lectures WHERE CourseID = ?";
        $stmt = $con->prepare($delete_lectures_sql);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();

        // Delete row from course table
        $delete_course_sql = "DELETE FROM course WHERE CourseID = ?";
        $stmt = $con->prepare($delete_course_sql);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();

        // Commit transaction
        mysqli_commit($con);
        echo "Course and related assignments and lectures deleted successfully!";
        header("location: DeleteCourse.php");
    } catch (Exception $e) {
        // Rollback transaction in case of error
        mysqli_rollback($con);
        echo "Error: " . $e->getMessage();
    }

    $stmt->close();
    $con->close();
} else {
    // Fetch courses for the form
    $resultCourses = mysqli_query($con, "SELECT * FROM course");
    ?>

<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
    <title>Delete Course</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1c1c1e;
            color: white;
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            max-width: 500px;
            margin: auto;
            border: 1px solid #FFD700;
            box-shadow: 0px 10px 20px rgba(255, 215, 0, 0.4);
            border-radius: 15px;
            padding: 20px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #FFD700;
            box-shadow: 0px 10px 20px rgba(255, 215, 0, 0.4);
            border-radius: 15px;
        }

        input[type="submit"] {
            background-color: #FFD700;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            width: 100%;
            box-shadow: 0px 10px 20px rgba(255, 215, 0, 0.4);
            transition: transform 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #b8860b;
            transform: scale(1.05);
        }

        .navbar {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            background-color: #1c1c1e;
            padding: 10px 20px;
        }

        .navbar a {
            background-color: #FFD700;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            text-decoration: none;
            box-shadow: 0px 10px 20px rgba(255, 215, 0, 0.4);
            transition: transform 0.3s;
            margin-right: 5px;
        }

        .navbar a:hover {
            background-color: #b8860b;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="Settings.php">Back</a>
        <a href="Course.php">Add</a>
        <a href="UpdateCourse.php">Update</a>
        <a href="ViewCourse.php">View</a>
    </div>
    <form action="DeleteCourse.php" method="post">
        <div class="form-group">
            <label for="course_id" class="form-label">Select Course to Delete</label>
            <?php
            if (mysqli_num_rows($resultCourses)) {
                echo '<select class="form-select" name="course_id" id="course_id" required>';
                echo '<option value="">Select a course</option>';
                while ($row = mysqli_fetch_assoc($resultCourses)) {
                    $CourseID = htmlspecialchars($row['CourseID']);
                    $CourseTitle = htmlspecialchars($row['name']);
                    echo "<option value='$CourseID'>$CourseTitle</option>";
                }
                echo '</select>';
            } else {
                echo '<p>No courses found.</p>';
            }
            ?>
        </div>

        <input type="submit" value="Delete Course">
    </form>
</body>

</html>
<?php
}
?>
