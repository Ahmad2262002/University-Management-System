<?php
include "../dist/security.php";
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_course']) && !empty($_POST['update_course'])) {
        // Update course details
        $course_id = $_POST['course_id'];
        $title = $_POST['title'];
        $selectedDepartmentID = $_POST['department'];
        $credits = $_POST['credits'];
        $year_id = $_POST['year_id'];

        $stmt = $con->prepare("UPDATE course SET name=?, dept_id=?, credits=?, YearID=? WHERE CourseID=?");
        $stmt->bind_param("ssisi", $title, $selectedDepartmentID, $credits, $year_id, $course_id);

        if ($stmt->execute()) {
            echo "Course updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    } else {
        // Fetch course details for the form
        $course_id = $_POST['course_id'];

        $stmt = $con->prepare("SELECT * FROM course WHERE CourseID=?");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $course = $result->fetch_assoc();

        if (!$course) {
            echo "<script>alert('Course ID not found!'); window.location.href = 'UpdateCourse.php';</script>";
            exit();
        }

        // Fetch departments
        $sql = "SELECT * FROM departments";
        $resultDepartment = mysqli_query($con, $sql);

        // Fetch academic years
        $resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
        ?>

        <!DOCTYPE html>
        <html>

        <head>
            <title>Update Course</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />
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
                <a href="DeleteCourse.php">Delete</a>
                <a href="ViewCourse.php">View</a>
            </div>
            <form action="UpdateCourse.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['CourseID']); ?>">
                <input type="hidden" name="update_course" value="1">
                <label for="title">Name:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($course['name']); ?>" required>

                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <?php
                    if (mysqli_num_rows($resultDepartment)) {
                        echo '<select class="form-select" name="department" id="department" required>';
                        while ($row = mysqli_fetch_assoc($resultDepartment)) {
                            $DepartmentID = htmlspecialchars($row['dept_id']);
                            $DepartmentName = htmlspecialchars($row['dept_name']);
                            $selected = $DepartmentID == $course['dept_id'] ? 'selected' : '';
                            echo "<option value='$DepartmentID' $selected>$DepartmentName</option>";
                        }
                        echo "</select>";
                    }
                    ?>
                </div>

                <label for="credits">Credits:</label>
                <input type="number" id="credits" name="credits" value="<?php echo htmlspecialchars($course['credits']); ?>" required>

                <div class="form-group">
                    <label for="year_id" class="form-label">Academic Year</label>
                    <?php
                    if (mysqli_num_rows($resultAcademic)) {
                        echo '<select class="form-select" name="year_id" id="year_id" required>';
                        while ($row = mysqli_fetch_assoc($resultAcademic)) {
                            $YearID = htmlspecialchars($row['YearID']);
                            $startDate = htmlspecialchars($row['startdate']);
                            $endDate = htmlspecialchars($row['enddate']);
                            $selected = $YearID == $course['YearID'] ? 'selected' : '';
                            echo "<option value='$YearID' $selected>$startDate | $endDate</option>";
                        }
                        echo '</select>';
                    }
                    ?>
                </div>

                <input type="submit" value="Update Course">
            </form>
        </body>

        </html>
        <?php
    }
} else {
    ?>

<!DOCTYPE html>
<html>

<head>
    <title>Enter Course ID</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1c1c1e;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #333;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 10px 20px rgba(255, 215, 0, 0.4);
            max-width: 500px;
            width: 100%;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .form-group {
            width: 100%;
        }

        .form-group label {
            font-size: 1.2em;
            margin-bottom: 10px;
            display: block;
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
            background-color: #555;
            color: white;
        }

        input[type="submit"] {
            background-color: #FFD700;
            color: #1c1c1e;
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
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0px 10px 20px rgba(255, 215, 0, 0.4);
        }

        .navbar a {
            background-color: #FFD700;
            color: #1c1c1e;
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
        <a href="DeleteCourse.php">Delete</a>
        <a href="ViewCourse.php">View</a>
    </div>
    <div class="container">
        <h2>Enter Course ID</h2>
        <form action="UpdateCourse.php" method="post">
            <div class="form-group">
                <label for="course_id">Course ID:</label>
                <input type="text" id="course_id" name="course_id" required>
            </div>
            <input type="submit" value="Fetch Course">
        </form>
    </div>
</body>

</html>

<?php
}
?>
