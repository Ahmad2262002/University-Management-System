<?php
include "../dist/security.php";
include "connection.php";

// Fetch departments
$sql = "SELECT * FROM departments";
$resultDepartment = mysqli_query($con, $sql);

// Fetch academic years
$resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
$resultCurriculum_year_id  = mysqli_query($con, "SELECT * FROM curriculum_year");

?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Add Course</title>
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
            background-color: #2c2c2e;
            color: white;
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
            margin-bottom: 20px;
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

        .form-control {
            background-color: #2c2c2e;
            color: white;
            border: none;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="Settings.php">Back</a>
        <a href="UpdateCourse.php">Update</a>
        <a href="DeleteCourse.php">Delete</a>
        <a href="ViewCourse.php">View</a>
    </div>
    <form action="courseAction.php" method="post" enctype="multipart/form-data">
        <h2>Add Course</h2>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="code">Code:</label>
        <input type="text" id="code" name="code" required>

        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <?php
            if (mysqli_num_rows($resultDepartment)) {
                echo '<select class="form-select" name="department" id="department" required>';
                echo "<option value=''>Select a department</option>";
                while ($row = mysqli_fetch_assoc($resultDepartment)) {
                    $DepartmentID = htmlspecialchars($row['dept_id']);
                    $DepartmentName = htmlspecialchars($row['dept_name']);
                    echo "<option value='$DepartmentID'>$DepartmentName</option>";
                }
                echo "</select>";
            } else {
                echo '<p>No departments found.</p>';
            }
            ?>
        </div>

        <div class="mb-3">
            <label for="Curriculum" class="form-label">Curriculum Year</label>
            <?php
            if (mysqli_num_rows($resultCurriculum_year_id)) {
                echo '<select class="form-select" name="curriculum" id="Curriculum" required>';
                echo "<option value=''>Select a Curriculum Year</option>";
                while ($row = mysqli_fetch_assoc($resultCurriculum_year_id)) {
                    $Curriculum_year_id = htmlspecialchars($row['curriculum_year_id']);
                    $Curriculum_year_name = htmlspecialchars($row['name']);
                    echo "<option value='$Curriculum_year_id'>$Curriculum_year_name</option>";
                }
                echo "</select>";
            } else {
                echo '<p>No Years found.</p>';
            }
            ?>
        </div>

        <label for="credits">Credits:</label>
        <input type="number" id="credits" name="credits" required>

        <label for="creditPrice">Credit Price:</label>
        <input type="text" id="creditPrice" name="creditPrice" required>

        <div class="form-group">
            <label for="Profile_photo">Profile Photo:</label>
            <input type="file" class="form-control" id="Profile_photo" name="Profile_photo" required>
        </div>

        <div class="form-group">
            <label for="year_id" class="form-label">Academic Year</label>
            <?php
            if (mysqli_num_rows($resultAcademic)) {
                echo '<select class="form-select" name="year_id" id="year_id" required>';
                echo '<option value="">Select Year</option>';
                while ($row = mysqli_fetch_assoc($resultAcademic)) {
                    $YearID = htmlspecialchars($row['YearID']);
                    $startDate = htmlspecialchars($row['startdate']);
                    $endDate = htmlspecialchars($row['enddate']);
                    echo "<option value='$YearID'>$startDate | $endDate</option>";
                }
                echo '</select>';
            } else {
                echo '<p>No academic years found.</p>';
            }
            ?>
        </div>

        <input type="submit" value="Add Course">
    </form>
</body>

</html>
