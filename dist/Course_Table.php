<?php
session_start();

include 'connection.php';

if (!isset($_SESSION['students_id'])) {
    die("Student ID not set in session.");
}

$StudentID = $_SESSION['students_id'];

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Prepare the SQL query
$sql = "SELECT semister.SemesterName, course.CourseID, course.name, course.Credits, course.Code, course.credit_price, course.curriculum_year_id, course.YearID, academicyear.startdate, academicyear.enddate, departments.dept_name AS DepartmentName, curriculum_year.name AS curriculum_name
        FROM semister
        JOIN semister_courses ON semister.SemesterID = semister_courses.SemesterID
        JOIN course ON semister_courses.CourseID = course.CourseID
        JOIN enrollment ON course.CourseID = enrollment.CourseID
        JOIN student ON enrollment.students_id = student.students_id
        JOIN academicyear ON course.YearID = academicyear.YearID
        JOIN curriculum_year ON course.curriculum_year_id = curriculum_year.curriculum_year_id
        JOIN departments ON course.dept_id = departments.dept_id 
        WHERE student.students_id = ? AND course.status = '1'
        ORDER BY semister.SemesterName, course.name";

$stmt = $con->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $con->error);
}

$stmt->bind_param("i", $StudentID);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $academicYear = htmlspecialchars($row['startdate']) . " - " . htmlspecialchars($row['enddate']);
    $curriculumYearName = htmlspecialchars($row['curriculum_name']);
    $DepartmentName = htmlspecialchars($row['DepartmentName']);

    $courses[$row['SemesterName']][] = array_merge($row, ['academicYear' => $academicYear, 'curriculumYearName' => $curriculumYearName, 'DepartmentName' => $DepartmentName]);
}
$stmt->close();
$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Courses</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color:  #2c2c2e; /* iOS 17 background color */
            color: #333333; /* Dark text color */
            font-family: Arial, sans-serif; /* Standard font */
        }

        .navbar {
            background-color: #2c2c2e; /* Dark navbar color */
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .navbar a {
            color: white; /* Navbar link color */
            text-decoration: none;
        }

        .navbar a:hover {
            color: #ddd; /* Navbar link hover color */
        }

        .container {
            margin-top: 20px; /* Margin from top */
        }

        h2 {
            color: #007aff; /* iOS Blue */
        }

        .table {
            background-color: #ffffff; /* White background for table */
            border-radius: 30px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .table th, .table td {
            border: 1px solid #dddddd; /* Light gray borders */
            padding: 8px; /* Padding inside cells */
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9; /* Alternate row background color */
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1; /* Hover row background color */
        }

        .alert-info {
            background-color: #2c2c2e; /* Dark alert background */
            border-color: #bb1333; /* Border color */
            color: white; /* Text color */
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="container">
        <a class="nav-link" href="StudentCP.php">Back</a>
    </div>
</nav>
<div class="container mt-3">
    <?php
    if (!empty($courses)) {
        foreach ($courses as $semesterName => $semesterCourses) {
            $academicYear = $semesterCourses[0]['academicYear'];
            $curriculumYearName = $semesterCourses[0]['curriculumYearName'];
            $DepartmentName = $semesterCourses[0]['DepartmentName'];

            
            echo "<h2 class='mt-4'>Semester: " . htmlspecialchars($semesterName) . " (" . $academicYear . ", " . $curriculumYearName . ", " . $DepartmentName . ")</h2>";
            echo "<table class='table table-striped table-bordered table-hover'>
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Credits</th>
                            <th>Credit Price</th>
                            <th>Code</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($semesterCourses as $course) {
                echo "<tr>
                        <td>" . htmlspecialchars($course['name']) . "</td>
                        <td>" . htmlspecialchars($course['Credits']) . "</td>
                        <td>" . htmlspecialchars($course['credit_price']) . "</td>
                        <td>" . htmlspecialchars($course['Code']) . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        }
    } else {
        echo "<div class='alert alert-info'>0 results</div>";
    }
    ?>
</div>
</body>
</html>
