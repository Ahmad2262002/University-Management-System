<?php
include '../dist/connection.php';
$selectedCourses = [];
$message = '';

// Handle form submission for adding or updating a semester
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $semesterName = $_POST['semesterName'];
    $selectedCourses = isset($_POST['selected_courses']) ? $_POST['selected_courses'] : [];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    $departmentID = $_POST['department'];
    $curriculumID = $_POST['curriculum'];
    $YearID = $_POST['year_id'];
    $semesterID = isset($_POST['SemesterID']) ? $_POST['SemesterID'] : null;

    // Check if the semester already exists
    $checkQuery = "SELECT * FROM semister
                   WHERE SemesterName = ? AND DepartmentID = ? AND curriculum_year_id = ? AND YearID = ?";
    if ($semesterID) {
        $checkQuery .= " AND SemesterID != ?";
    }
    $checkStmt = $con->prepare($checkQuery);
    if ($semesterID) {
        $checkStmt->bind_param("siii", $semesterName, $departmentID, $curriculumID, $YearID, $semesterID);
    } else {
        $checkStmt->bind_param("siii", $semesterName, $departmentID, $curriculumID, $YearID);
    }
    $checkStmt->execute();
    $existingSemester = $checkStmt->get_result()->fetch_assoc();
    $checkStmt->close();

    if ($existingSemester) {
        $message = "A semester with this name, department, curriculum year, and academic year already exists.";
    } else {
        if ($semesterID) {
            // Update existing semester
            $query = "UPDATE semister SET SemesterName=?, Startdate=?, ENDdate=?, DepartmentID=?, YearID=?, curriculum_year_id=? WHERE SemesterID=?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("sssiiii", $semesterName, $startdate, $enddate, $departmentID, $YearID, $curriculumID, $semesterID);

            if ($stmt->execute()) {
                $message = "Semester updated successfully.";
            } else {
                echo "Error updating semester: " . $stmt->error;
            }

            $stmt->close();

            // Delete existing course associations
            $deleteQuery = "DELETE FROM semister_courses WHERE SemesterID=?";
            $deleteStmt = $con->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $semesterID);
            $deleteStmt->execute();
        } else {
            // Add new semester
            $query = "INSERT INTO semister (SemesterName, Startdate, ENDdate, DepartmentID, YearID, curriculum_year_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("sssiii", $semesterName, $startdate, $enddate, $departmentID, $YearID, $curriculumID);
            $stmt->execute();
            $semesterID = $stmt->insert_id;
            $message = "Semester added successfully.";
        }

        // Insert selected courses into semister_courses table
        foreach ($selectedCourses as $courseID) {
            $courseQuery = "INSERT INTO semister_courses (SemesterID, CourseID, date) VALUES (?, ?, NOW())";
            $courseStmt = $con->prepare($courseQuery);
            $courseStmt->bind_param("ii", $semesterID, $courseID);
            $courseStmt->execute();
        }
    }
}
// Handle deletion of a semester
if (isset($_GET['delete'])) {
    $semesterID = $_GET['delete'];

    // Delete from semister_courses first
    $deleteCoursesQuery = "DELETE FROM semister_courses WHERE SemesterID=?";
    $deleteCoursesStmt = $con->prepare($deleteCoursesQuery);
    $deleteCoursesStmt->bind_param("i", $semesterID);
    $deleteCoursesStmt->execute();

    // Now delete the semester from semister
    $deleteSemesterQuery = "DELETE FROM semister WHERE SemesterID=?";
    $deleteSemesterStmt = $con->prepare($deleteSemesterQuery);
    $deleteSemesterStmt->bind_param("i", $semesterID);
    $deleteSemesterStmt->execute();

    header("Location: Semisters.php");
    exit();
}

// Fetch all semesters
$query = "
   SELECT
    s.SemesterID,
    s.SemesterName,
    c.name AS courseTitle,
    sc.semister_course_id,
    sc.CourseID,
    sc.date,
    s.Startdate,
    s.Enddate,
    d.dept_name AS departmentName
FROM semister s
JOIN semister_courses sc ON s.SemesterID = sc.SemesterID
JOIN course c ON sc.CourseID = c.CourseID
JOIN departments d ON s.DepartmentID = d.dept_id;
";
$semesters = $con->query($query);

// Fetch all courses and departments
$courses = $con->query("SELECT CourseID, name FROM course");
$departments = $con->query("SELECT dept_id, dept_name FROM departments");
$resultCurriculumYear = mysqli_query($con, "SELECT * FROM curriculum_year");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />

    <title>Semester Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #1c1c1e;
            color: #f0f0f5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
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

        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            color: #f0f0f5;
            border: none;
            border-radius: 10px;
            padding: 15px;
        }

        .form-control::placeholder {
            color: #d1d1d6;
        }

        .form-group label {
            color: #f0f0f5;
        }

        .btn-primary {
            background-color: #007aff;
            border-color: #007aff;
            border-radius: 10px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #005bb5;
            border-color: #005bb5;
        }

        .btn-warning {
            background-color: #ff9500;
            border-color: #ff9500;
            border-radius: 10px;
            padding: 10px 20px;
        }

        .btn-danger {
            background-color: #ff3b30;
            border-color: #ff3b30;
            border-radius: 10px;
            padding: 10px 20px;
        }

        .btn-sm {
            padding: 5px 10px;
        }

        .table {
            background-color: rgba(255, 255, 255, 0.1);
            color: #f0f0f5;
        }

        .alert-info {
            background-color: #5ac8fa;
            border-color: #5ac8fa;
            color: #fff;
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

        select.form-control {
    background-color: rgba(255, 255, 255, 0.1); 
    color: #f0f0f5; 
    border: none;
    border-radius: 10px; 
    padding: 15px; 
    width: 100%; 
    appearance: none; 
    -webkit-appearance: none;
    -moz-appearance: none;
    height: auto;
}

select.form-control option {
    background-color: #2c2c2e; /* Background color of options */
    color: #f0f0f5; /* Text color of options */
    padding: 10px; /* Padding inside each option */
}


    </style>
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <a class="nav-link" href="AdminCP.php">Back</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Semester Management</h2>
        <?php if (isset($message)) {
            echo "<div class='alert alert-info'>$message</div>";
        } ?>
        <form method="post" action="Semisters.php">
            <input type="hidden" name="SemesterID" id="SemesterID">
            <div class="form-group">
                <label for="semesterName">Semester Name:</label>
                <input type="text" id="semesterName" name="semesterName" class="form-control" required>
            </div>
            <div class="form-group">
    <label for="courseList">Courses:</label>
    <button type="button" id="showCoursesBtn">Show Courses</button>
    <div id="courseList" style="display: none;">
        <?php while ($course = $courses->fetch_assoc()) { ?>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="selected_courses[]" value="<?php echo $course['CourseID']; ?>" class="course-checkbox">
                    <?php echo $course['name']; ?>
                </label>
            </div>
        <?php } ?>
    </div>
</div>

<script>
document.getElementById('showCoursesBtn').addEventListener('click', function() {
    var courseList = document.getElementById('courseList');
    if (courseList.style.display === 'none') {
        courseList.style.display = 'block';
    } else {
        courseList.style.display = 'none';
    }
});

document.querySelector('form').addEventListener('submit', function(event) {
    var checkboxes = document.querySelectorAll('.course-checkbox');
    var isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    if (!isChecked) {
        alert('Please select at least one course.');
        event.preventDefault(); // Prevent form submission
    }
});
</script>

            <div class="form-group">
                <label for="startdate">Start Date:</label>
                <input type="date" id="startdate" name="startdate" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="enddate">End Date:</label>
                <input type="date" id="enddate" name="enddate" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <select id="department" name="department" class="form-control" required>
                    <?php while ($department = $departments->fetch_assoc()) { ?>
                        <option value="<?php echo $department['dept_id']; ?>"><?php echo $department['dept_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
    <label for="curriculum">Curriculum Year:</label>
    <select class="form-control" id="curriculum" name="curriculum" required>
        <option value="">Select Curriculum Year</option>
        <?php while ($curriculumYear = mysqli_fetch_array($resultCurriculumYear)) { ?>
            <option value="<?= $curriculumYear['curriculum_year_id'] ?>">
                <?= $curriculumYear['name'] ?>
            </option>
        <?php } ?>
    </select>
</div>

<div class="form-group">
    <label for="year_id">Year:</label>
    <?php
    $resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
    if ($resultAcademic) {
        echo '<select class="form-control" name="year_id" id="year_id" required>';
        echo '<option value="">Select Year</option>';
        while ($row = mysqli_fetch_assoc($resultAcademic)) {
            $YearID = htmlspecialchars($row['YearID']);
            $startDate = htmlspecialchars($row['startdate']);
            $endDate = htmlspecialchars($row['enddate']);
            echo "<option value=\"$YearID\">$startDate | $endDate</option>";
        }
        echo '</select>';
    } else {
        echo '<p>No academic years found.</p>';
    }
    ?>
</div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h3 class="mt-5">Semesters List</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Semester ID</th>
            <th>Semester Name</th>
            <th>Courses</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $semestersData = [];
        while ($row = $semesters->fetch_assoc()) {
            $semesterID = $row['SemesterID'];
            if (!isset($semestersData[$semesterID])) {
                $semestersData[$semesterID] = [
                    'SemesterID' => $row['SemesterID'],
                    'SemesterName' => $row['SemesterName'],
                    'Startdate' => $row['Startdate'],
                    'Enddate' => $row['Enddate'],
                    'DepartmentName' => $row['departmentName'],
                    'Courses' => []
                ];
            }
            if (!in_array($row['courseTitle'], $semestersData[$semesterID]['Courses'])) {
                $semestersData[$semesterID]['Courses'][] = $row['courseTitle'];
            }
        }

        foreach ($semestersData as $semester) {
            echo "<tr>
                    <td>{$semester['SemesterID']}</td>
                    <td>{$semester['SemesterName']}</td>
                    <td>" . implode(', ', $semester['Courses']) . "</td>
                    <td>{$semester['Startdate']}</td>
                    <td>{$semester['Enddate']}</td>
                    <td>{$semester['DepartmentName']}</td>
                    <td>
                        <button class='btn btn-warning btn-sm editBtn' data-id='{$semester['SemesterID']}'>Edit</button>
                        <a href='Semisters.php?delete={$semester['SemesterID']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this semester?');\">Delete</a>
                    </td>
                </tr>";
        }
        ?>
    </tbody>
</table>

    </div>

    <script>
       
        document.querySelectorAll('.editBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                var semesterID = this.getAttribute('data-id');

                fetch('get_semester_details.php?SemesterID=' + semesterID)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('SemesterID').value = data.SemesterID;
                        document.getElementById('semesterName').value = data.SemesterName;
                        document.getElementById('startdate').value = data.Startdate;
                        document.getElementById('enddate').value = data.Enddate;
                        document.getElementById('department').value = data.DepartmentID;
                        document.getElementById('curriculum').value = data.curriculum_year_id;
                        document.getElementById('year_id').value = data.YearID;

                        document.querySelectorAll('input[name="selected_courses[]"]').forEach(function (checkbox) {
                            checkbox.checked = data.selected_courses.includes(parseInt(checkbox.value));
                        });
                    });
            });
        });
    </script>
</body>

</html>
