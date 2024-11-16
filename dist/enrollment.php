<?php
session_start();
include "../dist/connection.php";

include "../dist/security.php";

if (!isset($_SESSION['email'])) {
   header("Location: StudentLogin.php"); 
   exit();
}
$StudentID = $_SESSION['students_id']; 





?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment</title>
    <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom styles for the course cards */
        .course-card {
            background-color: #f8f9fa;
            border: 1px solid #e2e6ea;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .course-title {
            font-size: 18px;
            font-weight: bold;
        }
        .course-details {
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Assuming you have fetched data from your database
        // Replace this with your actual data retrieval logic
        $courses = [
            ['title' => 'Mathematics', 'credits' => 3],
            ['title' => 'Computer Science', 'credits' => 4],
            // Add more courses here...
        ];

        foreach ($courses as $course) {
            echo '<div class="course-card">';
            echo '<div class="course-title">' . $course['title'] . '</div>';
            echo '<div class="course-details">Credits: ' . $course['credits'] . '</div>';
            // Add other course details (lectures, assignments, etc.) here
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
