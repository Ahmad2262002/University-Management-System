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
            background-color: #bb86fc;
            border-color: #bb86fc;
            font-weight: bold;
            padding: 10px 20px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #985eff;
            border-color: #985eff;
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
        <a class="nav-link" href="Schedules.php">Back</a>
    </div>
</nav>
<div class="container">
    <h2>Update Schedule</h2>
    <?php
    include('connection.php');

    if (isset($_GET['schedule_id'])) {
        $schedule_id = $_GET['schedule_id'];

        $sql = "SELECT * FROM schedule WHERE schedule_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <form method="post" action="update_schedule_process.php">
                <input type="hidden" name="schedule_id" value="<?php echo htmlspecialchars($schedule_id); ?>">
                
                <div class="form-group">
                    <label for="courseID">Course:</label>
                    <select class="form-control" id="courseID" name="courseID" required>
                        <?php
                        $sql = "SELECT CourseID, name FROM course";
                        $result = $con->query($sql);
                        while ($course = $result->fetch_assoc()) {
                            $selected = $course['CourseID'] == $row['CourseID'] ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($course['CourseID']) . '" ' . $selected . '>' . htmlspecialchars($course['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="teacherID">Teacher:</label>
                    <select class="form-control" id="teacherID" name="teacherID" required>
                        <?php
                        $sql = "SELECT teacherID, name FROM teachers";
                        $result = $con->query($sql);
                        while ($teacher = $result->fetch_assoc()) {
                            $selected = $teacher['teacherID'] == $row['teacherID'] ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($teacher['teacherID']) . '" ' . $selected . '>' . htmlspecialchars($teacher['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="section">Section:</label>
                    <select class="form-control" id="section" name="section" required>
                        <option value="">Select Section</option>
                        <?php
                        $sql = "SELECT section_id, section_name FROM sections";
                        $result = $con->query($sql);
                        while ($section = $result->fetch_assoc()) {
                            $selected = $section['section_id'] == $row['section_id'] ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($section['section_id']) . '" ' . $selected . '>' . htmlspecialchars($section['section_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="class">Class:</label>
                    <select class="form-control" id="class" name="class" required>
                        <option value="">Select Class</option>
                        <?php
                        $sql = "SELECT class_id, class_name FROM classes";
                        $result = $con->query($sql);
                        while ($class = $result->fetch_assoc()) {
                            $selected = $class['class_id'] == $row['class_id'] ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($class['class_id']) . '" ' . $selected . '>' . htmlspecialchars($class['class_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="day">Select Day:</label>
                    <select class="form-control" id="c" name="day" required>
                        <option value="">Select Day</option>
                        <option value="monday" <?php echo $row['day_of_week'] == 'monday' ? 'selected' : ''; ?>>Monday</option>
                        <option value="tuesday" <?php echo $row['day_of_week'] == 'tuesday' ? 'selected' : ''; ?>>Tuesday</option>
                        <option value="wednesday" <?php echo $row['day_of_week'] == 'wednesday' ? 'selected' : ''; ?>>Wednesday</option>
                        <option value="thursday" <?php echo $row['day_of_week'] == 'thursday' ? 'selected' : ''; ?>>Thursday</option>
                        <option value="friday" <?php echo $row['day_of_week'] == 'friday' ? 'selected' : ''; ?>>Friday</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="curriculum_year">Curriculum Year:</label>
                    <select class="form-control" id="curriculum_year" name="curriculum_year" required>
                        <option value="">Select Curriculum Year</option>
                        <?php
                        $resultCurriculumYear = $con->query("SELECT * FROM curriculum_year");
                        while ($curriculumYear = $resultCurriculumYear->fetch_assoc()) {
                            $selected = $curriculumYear['curriculum_year_id'] == $row['YearID'] ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($curriculumYear['curriculum_year_id']) . '" ' . $selected . '>' . htmlspecialchars($curriculumYear['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="department">Department:</label>
                    <select class="form-control" id="department" name="department" required>
                        <option value="">Select Department</option>
                        <?php
                        $sql = "SELECT dept_id, dept_name FROM departments";
                        $result = $con->query($sql);
                        while ($department = $result->fetch_assoc()) {
                            $selected = $department['dept_id'] == $row['department_id'] ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($department['dept_id']) . '" ' . $selected . '>' . htmlspecialchars($department['dept_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

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

                <div class="form-group">
                    <label for="year_id">Academic Year:</label>
                    <select class="form-control" id="year_id" name="year_id" required>
                        <option value="">Select Academic Year</option>
                        <?php
                        $sql = "SELECT YearID, startdate, enddate FROM academicyear";
                        $result = $con->query($sql);
                        while ($academicYear = $result->fetch_assoc()) {
                            $selected = $academicYear['YearID'] == $row['YearID'] ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($academicYear['YearID']) . '" ' . $selected . '>' . htmlspecialchars($academicYear['startdate']) . ' - ' . htmlspecialchars($academicYear['enddate']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time:</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" value="<?php echo htmlspecialchars($row['start_time']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="end_time">End Time:</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" value="<?php echo htmlspecialchars($row['end_time']); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
            <?php
        } else {
            echo "<p>No schedule found.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Invalid schedule ID.</p>";
    }
    $con->close();
    ?>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
