<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />

    <!-- Custom CSS for Night Mode -->
    <style>
        body {
            background-color: #121212; /* Dark background */
            color: #ffffff; /* Light text */
            font-family: 'Georgia', serif;
        }
        .container {
            max-width: 700px;
            margin-top: 50px;
            padding: 20px;
            background-color: #1e1e1e; /* Darker background for the form */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); /* Subtle shadow */
            border-radius: 8px; /* Rounded corners */
        }
        h2 {
            font-weight: bold;
            margin-bottom: 20px;
            color: #bb86fc; /* Primary color for the header */
            text-align: center;
            font-family: 'Merriweather', serif;
        }
        .form-group label {
            font-weight: bold;
            color: #b0b0b0; /* Muted color for labels */
            font-size: 1.1rem;
        }
        .form-control {
            background-color: #2c2c2c;
            color: #ffffff;
            border: 1px solid #4a4a4a;
            border-radius: 4px; /* Slightly rounded inputs */
        }
        .form-control:focus {
            background-color: #3c3c3c;
            color: #ffffff;
            border-color: #bb86fc; /* Focus color */
            box-shadow: 0 0 0 0.2rem rgba(187, 134, 252, 0.25); /* Focus shadow */
        }
        .btn-primary {
            background-color: #333333;
            border-color: #121212;
            font-weight: bold;
            padding: 10px 20px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #333333;
            border-color: #bb86fc;
        }
        .navbar {
            background-color: #2c2c2e;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar a:hover {
            color: #ddd;
        }
        .table {
            margin-top: 20px;
        }
        .table th, .table td {
            color: #ffffff;
        }
        .table thead th {
            background-color: #333333;
        }
        .table tbody tr {
            background-color: #2c2c2e;
        }
        .btn-secondary {
            background-color: #4a4a4a;
            border-color: #4a4a4a;
        }
        .btn-secondary:hover {
            background-color: #6a6a6a;
            border-color: #6a6a6a;
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
    <h2>Schedule Management</h2>
    <form id="filterForm" method="post" action="process_schedule.php" enctype="multipart/form-data">
     <!-- Courses Dropdown -->
     <div class="form-group">
                <label for="courses">Courses:</label>
                <select class="form-control" id="courses" name="courses" required>
                    <option value="">Select Course</option>
                    <?php
                    include('connection.php');
                    $sql = "SELECT CourseID, name FROM course";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['CourseID'] . '">' . $row['name'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No courses found</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Teacher Dropdown -->
            <div class="form-group">
                <label for="teacher">Teacher:</label>
                <select class="form-control" id="teacher" name="teacher" required>
                    <option value="">Select Teacher</option>
                    <?php
                    $sql = "SELECT teacherID, name FROM teachers";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['teacherID'] . '">' . $row['name'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No teachers found</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Sections Dropdown -->
            <div class="form-group">
                <label for="section">Section:</label>
                <select class="form-control" id="section" name="section" required>
                    <option value="">Select Section</option>
                    <?php
                    $sql = "SELECT section_id, section_name FROM sections";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['section_id'] . '">' . $row['section_name'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No sections found</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Classes Dropdown -->
            <div class="form-group">
                <label for="class">Class:</label>
                <select class="form-control" id="class" name="class" required>
                    <option value="">Select Class</option>
                    <?php
                    $sql = "SELECT class_id, class_name FROM classes";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['class_id'] . '">' . $row['class_name'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No classes found</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Day Dropdown -->
            <div class="form-group">
                <label for="day">Select day:</label>
                <select class="form-control" id="day" name="day" required>
                    <option value="">Select Day</option>
                    <option value="monday">Monday</option>
                    <option value="tuesday">Tuesday</option>
                    <option value="wednesday">Wednesday</option>
                    <option value="thursday">Thursday</option>
                    <option value="friday">Friday</option>
                </select>
            </div>

            <!-- Curriculum Year Dropdown -->
            <div class="form-group">
                <label for="curriculum_year">Curriculum Year:</label>
                <select class="form-control" id="curriculum_year" name="curriculum_year" required>
                    <option value="">Select Curriculum Year</option>
                    <?php
                    $resultCurriculumYear = mysqli_query($con, "SELECT * FROM curriculum_year");
                    while ($curriculumYear = mysqli_fetch_array($resultCurriculumYear)) {
                        echo '<option value="' . $curriculumYear['curriculum_year_id'] . '">' . $curriculumYear['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Department Dropdown -->
            <div class="form-group">
                <label for="department">Department:</label>
                <select class="form-control" id="department" name="department" required>
                    <option value="">Select Department</option>
                    <?php
                    $sql = "SELECT dept_id, dept_name FROM departments";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['dept_id'] . '">' . $row['dept_name'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No departments found</option>';
                    }
                    ?>
                </select>
            </div>
           <!-- Semester Dropdown -->


<div class="form-group">
    <label for="semester">Semester</label>
    <select id="semester" name="semester" class="form-control" required>
        <option value="">Select a semester </option>
        <?php
        include 'connection.php';

        $sql = "SELECT s.SemesterID, s.SemesterName, cy.name 
        FROM semister s
        JOIN curriculum_year cy ON s.curriculum_year_id = cy.curriculum_year_id";


        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Combine SemesterName and curriculum year name in the same option
                echo '<option value="' . $row["SemesterID"] . '-' . $row["curriculum_year_id"] . '">'
                     . $row["SemesterName"] . ' - ' . $row["name"] . '</option>';
            }
        }
        ?>
    </select>
</div>

            

            <!-- Academic Year Dropdown -->
            <div class="form-group">
                <label for="year_id">Academic Year:</label>
                <select class="form-control" id="year_id" name="year_id" required>
                    <option value="">Select Academic Year</option>
                    <?php
                    $sql = "SELECT YearID, startdate, enddate FROM academicyear";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['YearID'] . '">' . $row['startdate'] . ' - ' . $row['enddate'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No academic years found</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Start Time and End Time -->
            <div class="form-group">
                <label for="start_time">Start Time:</label>
                <input type="time" class="form-control" id="start_time" name="start_time" required>
            </div>

            <div class="form-group">
                <label for="end_time">End Time:</label>
                <input type="time" class="form-control" id="end_time" name="end_time" required>
            </div>
        <button type="submit" class="btn btn-primary">Add Schedule</button>
        <button type="button" class="btn btn-primary" onclick="fetchSchedules()">Fetch Schedules</button>

    </form>

    
</div>
<div>
<h3 class="mt-4" style="text-align: center;">Related Schedules</h3>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Schedule ID</th>
                <th>Course</th>
                <th>Teacher</th>
                <th>Section</th>
                <th>Class</th>
                <th>Day</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Curriculum Year</th>
                <th>Department</th>
                <th>Academic Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="relatedSchedules">
            <!-- Rows will be inserted here by JavaScript -->
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function fetchSchedules() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const queryParams = new URLSearchParams(formData).toString();

        fetch(`fetch_schedules.php?${queryParams}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('relatedSchedules');
                tbody.innerHTML = ''; // Clear previous results

                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="12">No schedules found.</td></tr>';
                } else {
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.schedule_id}</td>
                            <td>${row.course_name}</td>
                            <td>${row.teacher_name}</td>
                            <td>${row.section_name}</td>
                            <td>${row.class_name}</td>
                            <td>${row.day_of_week}</td>
                            <td>${row.start_time}</td>
                            <td>${row.end_time}</td>
                            <td>${row.curriculum_year}</td>
                            <td>${row.dept_name}</td>
                            <td>${row.startdate} - ${row.enddate}</td>
                            <td><a href="update_schedule.php?schedule_id=${row.schedule_id}" class="btn btn-primary">Update</a></td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            })
            .catch(error => console.error('Error fetching schedules:', error));
    }
</script>
</body>
</html>
