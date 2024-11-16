<?php
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
$sql = "SELECT semister.SemesterName, course.CourseID, course.name, course.Credits, course.Code, 
               academicyear.startdate, academicyear.enddate, semister.curriculum_year_id, picture_url, course.status
        FROM semister
        JOIN semister_courses ON semister.SemesterID = semister_courses.SemesterID
        JOIN course ON semister_courses.CourseID = course.CourseID
        JOIN enrollment ON course.CourseID = enrollment.CourseID
        JOIN student ON enrollment.students_id = student.students_id
        JOIN academicyear ON semister.YearID = academicyear.YearID 
        JOIN curriculum_year ON semister.curriculum_year_id = curriculum_year.curriculum_year_id
        WHERE student.students_id = ? AND course.status = '1'";

$stmt = $con->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $con->error);
}

$stmt->bind_param("i", $StudentID);
$stmt->execute();
$result = $stmt->get_result();
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
        .course-card {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white; /* Ensure text is readable on background image */
        }
        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* Dark overlay to improve text visibility */
            border-radius: 15px;
        }
        .course-card .card-body {
            position: relative;
            z-index: 1; /* Ensure text is above the overlay */
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $picture_url = htmlspecialchars($row['picture_url']);
                    echo "<div class='col-lg-4 col-md-6 col-sm-12 mb-4'>
                            <div class='card course-card' style='background-image: url(\"$picture_url\");'>
                                <div class='card-body'>
                                <h6 class='card-text'>Year: ".htmlspecialchars($row['startdate'])." ".htmlspecialchars($row['enddate'])."</p>
                                    <h6 class='card-title'>".htmlspecialchars($row['name'])."</h5>
                                    
                                    <h6 class='card-subtitle mb-2 semester-name'>".htmlspecialchars($row['SemesterName'])."</h6>
                                    <p class='card-text'>Credits: ".htmlspecialchars($row['Credits'])."</p>
                                    <p class='card-text'>Code: ".htmlspecialchars($row['Code'])."</p>
                                    <a href='CoursesDetails.php?id=".htmlspecialchars($row['CourseID'])."' class='btn btn-primary'>Read More</a>
                                </div>
                            </div>
                          </div>";
                }
            } else {
                echo "<div class='alert alert-info'>0 results</div>";
            }
            $stmt->close();
            $con->close();
            ?>
        </div>
    </div>
</body>
</html>
