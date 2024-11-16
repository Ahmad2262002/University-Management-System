<?php
include "../dist/security.php";
include "connection.php";

// Fetch all courses
$sql = "SELECT c.CourseID, c.name, d.dept_name, c.credits, a.startdate, a.enddate, c.picture_url 
        FROM course c 
        JOIN departments d ON c.dept_id = d.dept_id 
        JOIN academicyear a ON c.YearID = a.YearID";
$resultCourses = mysqli_query($con, $sql);

// Check if the query was successful
if ($resultCourses === false) {
    die('Error: ' . mysqli_error($con));
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>View Courses</title>
    <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1c1c1e;
            color: white;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #FFD700;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #FFD700;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #333;
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
        <a href="DeleteCourse.php">Delete</a>
    </div>

    <h2>View Courses</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Department</th>
                <th>Credits</th>
                <th>Academic Year</th>
                <th>Profile Photo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($resultCourses) > 0) {
                while ($row = mysqli_fetch_assoc($resultCourses)) {
                    $courseID = htmlspecialchars($row['CourseID']);
                    $courseTitle = htmlspecialchars($row['name']);
                    $departmentName = htmlspecialchars($row['dept_name']);
                    $credits = htmlspecialchars($row['credits']);
                    $startDate = htmlspecialchars($row['startdate']);
                    $endDate = htmlspecialchars($row['enddate']);
                    $pictureURL = htmlspecialchars($row['picture_url']);
                    echo "<tr>";
                    echo "<td>$courseID</td>";
                    echo "<td>$courseTitle</td>";
                    echo "<td>$departmentName</td>";
                    echo "<td>$credits</td>";
                    echo "<td>$startDate - $endDate</td>";
                    echo "<td><img src='$pictureURL'  width='100'></td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr><td colspan="6">No courses found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</body>

</html>
