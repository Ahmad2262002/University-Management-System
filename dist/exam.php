<?php
include ('connection.php');

$selectedCourses = isset($_POST['selected_courses']) ? $_POST['selected_courses'] : [];
$courses = $con->query("SELECT CourseID, name FROM course");

// Error handling for the courses query
if (!$courses) {
    die("Error fetching courses: " . $con->error);
}

// Fetch exam types for the dropdown
$examTypes = $con->query("SELECT id, name FROM exam_type");
if (!$examTypes) {
    die("Error fetching exam types: " . $con->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1c1c1e;
            color: white;
        }
        .navbar {
            background-color: #2c2c2e;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .navbar a:hover {
            color: #ddd;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-control {
            background-color: #2c2c2e;
            color: white;
            border: none;
        }
        .btn-primary, .btn-danger, #showCoursesBtn {
            border-radius: 10px;
        }
        #showCoursesBtn {
            background-color: #007aff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 10px;
            cursor: pointer;
        }
        #showCoursesBtn:hover {
            background-color: #005bb5;
        }
        .checkbox label {
            color: #f0f0f5;
        }
        #courseList {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
        }
        .table thead th {
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a class="nav-link" href="AdminCP.php">Back</a>
    </div>
</nav>

<div class="container">
    <h1 class="mb-4">Exam Management</h1>
    <form id="examForm" action="examAction.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="create" value="1">
        <div class="form-group">
    <label for="exam_type_id">Exam Type:</label>
    <select class="form-control" id="exam_type_id" name="exam_type_id" required>
        <option value="">Select Exam Type</option>
        <?php while ($examType = $examTypes->fetch_assoc()) { ?>
            <option value="<?= $examType['id'] ?>"><?= $examType['name'] ?></option>
        <?php } ?>
    </select>
</div>
<input type="hidden" id="exam_type" name="exam_type" value=""> <!-- Hidden input for exam_type -->
<script>
    // Populate the hidden exam_type input based on the selected option
    document.getElementById('exam_type_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        document.getElementById('exam_type').value = selectedOption.text;
    });
</script>

        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="courses">Available Courses:</label>
            <button type="button" id="showCoursesBtn" onclick="toggleCourses()">Select Course</button>
            <div id="courseList" style="display: none;">
                <?php
                $courses = $con->query("SELECT CourseID, name FROM course");
                if (!$courses) {
                    die("Error fetching courses: " . $con->error);
                }
                while ($course = $courses->fetch_assoc()) { ?>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="selected_courses[]" value="<?= $course['CourseID'] ?>">
                            <?= $course['name'] ?>
                        </label>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="form-group">
            <label for="mark">Select Mark</label>
            <select class="form-control" id="mark" name="mark">
                <option value="60">60</option>
                <option value="40">40</option>
            </select>
        </div>
        <div class="form-group">
            <label for="file">File:</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>

        <div class="form-group">
            <label for="class_id">Select Class</label>
            <?php
            $resultClasses = mysqli_query($con, "SELECT classes.class_id, classes.class_name, academicyear.startdate, academicyear.enddate, course.name as course_name
                FROM classes
                JOIN academicyear ON classes.YearID = academicyear.YearID
                JOIN course ON course.YearID = academicyear.YearID");
            if (!$resultClasses) {
                die("Error fetching classes: " . $con->error);
            }
            echo '<select name="class_id" id="class_id" class="form-control">';
            echo '<option value="">Select Class</option>';
            while ($classRow = mysqli_fetch_assoc($resultClasses)) {
                $classID = htmlspecialchars($classRow['class_id']);
                $className = htmlspecialchars($classRow['class_name']);
                $startDate = htmlspecialchars($classRow['startdate']);
                $endDate = htmlspecialchars($classRow['enddate']);
                $courseName = htmlspecialchars($classRow['course_name']);
                echo "<option value=\"$classID\">$className | $courseName | $startDate - $endDate</option>";
            }
            echo '</select>';
            ?>
        </div>

        <div class="form-group">
            <label for="year_id">Academic Year:</label>
            <?php
            $resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
            if (!$resultAcademic) {
                die("Error fetching academic years: " . $con->error);
            }
            echo '<select class="form-control" name="year_id" id="year_id">';
            echo '<option value="">Select Year</option>';
            while ($row = mysqli_fetch_assoc($resultAcademic)) {
                $YearID = htmlspecialchars($row['YearID']);
                $startDate = htmlspecialchars($row['startdate']);
                $endDate = htmlspecialchars($row['enddate']);
                echo "<option value=\"$YearID\">$startDate | $endDate</option>";
            }
            echo '</select>';
            ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    
    <h2 class="mt-5">Existing Exams</h2>
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Exam ID</th>
                <th scope="col">Exam Type</th>
                <th scope="col">Course Name</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Class Name</th>
                <th scope="col">Mark</th>
                <th scope="col">File URL</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($con, "SELECT exam.ExamID, exam.exam_type_id, exam_type.name as exam_type, course.name as course_name, academicyear.startdate, academicyear.enddate, classes.class_name, exam.mark, exam.File_url
                FROM exam
                JOIN course ON exam.CourseID = course.CourseID
                JOIN academicyear ON exam.YearID = academicyear.YearID
                JOIN classes ON exam.room  = classes.class_id
                JOIN exam_type ON exam.exam_type_id = exam_type.id");
            if (!$result) {
                die("Error fetching exams: " . $con->error);
            }
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['ExamID'] . '</td>';
                echo '<td>' . $row['exam_type'] . '</td>';
                echo '<td>' . $row['course_name'] . '</td>';
                echo '<td>' . $row['startdate'] . '</td>';
                echo '<td>' . $row['enddate'] . '</td>';
                echo '<td>' . $row['class_name'] . '</td>';
                echo '<td>' . $row['mark'] . '</td>';
                echo '<td><a href="' . $row['File_url'] . '">View File</a></td>';
                echo '<td>';
                echo '<form action="examAction.php" method="post" style="display: inline-block;">';
                echo '<input type="hidden" name="ExamID" value="' . $row['ExamID'] . '">';
                echo '<button type="submit" class="btn btn-danger" name="delete">Delete</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function toggleCourses() {
        var courseList = document.getElementById("courseList");
        if (courseList.style.display === "none") {
            courseList.style.display = "block";
        } else {
            courseList.style.display = "none";
        }
    }
</script>

</body>
</html>
