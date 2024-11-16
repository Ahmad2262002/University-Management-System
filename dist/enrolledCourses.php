<?php
include "../dist/connection.php";


// Fetch student-course data
$query = "SELECT s.name AS student_name, c.title AS course_title
          FROM students s
          INNER JOIN student_courses sc ON s.id = sc.student_id
          INNER JOIN courses c ON sc.course_id = c.id";

$result = mysqli_query($con, $query);
if (!$result) {
    die('Query failed: ' . mysqli_error($con));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Courses</title>
    <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Student Enrollments</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Enrolled Courses</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['student_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['course_title']) . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($con);
?>
