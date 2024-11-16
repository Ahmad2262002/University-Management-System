<?php
session_start();
include "../dist/connection.php"; 

$query = "
SELECT d.dept_name, s.CourseID, s.teacherID, s.section_id, s.start_time, s.end_time, s.day_of_week, s.YearID, cy.name AS curriculum_year_name, s.class_id, s.SemesterID,sem.SemesterName AS semester_name, c.name AS course_name,
       t.name AS teacher_name, cl.class_name, se.section_name
FROM schedule s
JOIN departments d ON s.department_id = d.dept_id
JOIN curriculum_year cy ON s.curriculum_year_id = cy.curriculum_year_id
JOIN course c ON s.CourseID = c.CourseID
JOIN semister sem ON s.SemesterID = sem.SemesterID 

JOIN teachers t ON s.teacherID = t.teacherID
JOIN classes cl ON s.class_id = cl.class_id
JOIN sections se ON s.section_id = se.section_id
ORDER BY d.dept_name, s.day_of_week, s.start_time";

$result = $con->query($query);

if (!$result) {
    die("Query failed: " . $con->error); 
}

$schedules = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dept_name = $row['dept_name'];
        $semester_name = $row['semester_name']; // Corrected key name
        $CurriculumYearName = $row['curriculum_year_name'];
        $day_of_week = ucfirst(strtolower($row['day_of_week'])); // Standardize day capitalization
        $start_time = substr($row['start_time'], 0, 5); // Extract first 5 characters
        $end_time = substr($row['end_time'], 0, 5); // Extract first 5 characters
        $timeslot = $start_time . '-' . $end_time;

        if (!isset($schedules[$dept_name])) {
            $schedules[$dept_name] = [];
        }

        if (!isset($schedules[$dept_name][$CurriculumYearName][$day_of_week])) {
            $schedules[$dept_name][$CurriculumYearName][$day_of_week] = [];
        }

        if (!isset($schedules[$dept_name][$CurriculumYearName][$day_of_week][$timeslot])) {
            $schedules[$dept_name][$CurriculumYearName][$day_of_week][$timeslot] = [];
        }

        $schedules[$dept_name][$CurriculumYearName][$day_of_week][$timeslot][] = [
            'CourseID' => $row['CourseID'],
            'course_name' => $row['course_name'],
            'teacherID' => $row['teacherID'],
            'teacher_name' => $row['teacher_name'],
            'section_id' => $row['section_id'],
            'section_name' => $row['section_name'],
            'class_id' => $row['class_id'],
            'class_name' => $row['class_name']
        ];
    }
} else {
    echo "No schedule found.";
}

// Day order mapping
$day_order = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7];

// Function to sort days
function sort_days($days, $day_order) {
    uksort($days, function($a, $b) use ($day_order) {
        return $day_order[$a] - $day_order[$b];
    });
    return $days;
}

// Function to sort timeslots
function sort_timeslots($timeslots) {
    uksort($timeslots, function($a, $b) {
        list($start_a) = explode('-', $a);
        list($start_b) = explode('-', $b);
        return strcmp($start_a, $start_b);
    });
    return $timeslots;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Schedule</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: 'Merriweather', serif;
        }
        .container {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .container2 {
            padding: 20px;
            background-color: #2c2c2e;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .department-title {
            background-color: #007aff; /* iOS 17 blue */
            color: #fff;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .table {
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
        }
        .table th, .table td {
            vertical-align: top;
            padding: 12px; /* Adjusted padding for better spacing */
            text-align: left;
            font-size: 0.9rem;
        }
        .table th {
            background-color: #343a40;
            color: #fff;
        }
        .table td {
            background-color: #f8f9fa;
        }
        .course-details {
            margin-bottom: 8px; /* Reduced margin for tighter spacing */
            text-align: left;
            font-size: 0.85rem; /* Slightly smaller font size for compactness */
        }
        .course-details strong {
            display: block;
            color: #007aff; /* iOS 17 blue */
            font-size: 0.9rem; /* Matched font size with surrounding text */
            margin-bottom: 3px; /* Adjusted margin for better alignment */
        }
        .navbar {
            background-color: #2c2c2e; /* Dark navbar color */
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }
        .navbar a {
            color: white; /* Navbar link color */
            text-decoration: none;
            font-size: 0.9rem; /* Adjusted font size for navbar links */
        }
        .navbar a:hover {
            color: #ddd; /* Navbar link hover color */
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container2">
            <a class="nav-link" href="javascript:history.back()">Back</a>
        </div>
    </nav>
    <div class="container">
        <h1 class="text-center" style="color: #007aff;">University Schedule</h1>
        <?php if (!empty($schedules)): ?>
            <?php foreach ($schedules as $dept_name => $CurriculumYears): ?>
                <?php foreach ($CurriculumYears as $CurriculumYearName => $days): ?>
                    <div class="department-title">
                        <h2><?php echo htmlspecialchars($dept_name); ?></h2>
                        <h3><?php echo htmlspecialchars($CurriculumYearName); ?><br><?php echo htmlspecialchars($semester_name); ?></h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <?php 
                                    // Get all time slots for the week
                                    $all_timeslots = [];
                                    foreach ($days as $day => $timeslots) {
                                        $all_timeslots = array_merge($all_timeslots, array_keys($timeslots));
                                    }
                                    $all_timeslots = array_unique($all_timeslots);
                                    $sorted_timeslots = sort_timeslots($all_timeslots);
                                    foreach ($sorted_timeslots as $timeslot): ?>
                                        <th><?php echo htmlspecialchars($timeslot); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sorted_days = sort_days($days, $day_order);
                                foreach ($sorted_days as $day => $timeslots): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($day); ?></td>
                                        <?php foreach ($sorted_timeslots as $timeslot): ?>
                                            <td>
                                                <?php if (isset($timeslots[$timeslot])): ?>
                                                    <?php foreach ($timeslots[$timeslot] as $lecture): ?>
                                                        <div class="course-details">
                                                            <strong>Course Name:</strong> <?php echo htmlspecialchars($lecture['course_name']); ?><br>
                                                            <strong>Teacher:</strong> <?php echo htmlspecialchars($lecture['teacher_name']); ?><br>
                                                            <strong>Section:</strong> <?php echo htmlspecialchars($lecture['section_name']); ?><br>
                                                            <strong>Class:</strong> <?php echo htmlspecialchars($lecture['class_name']); ?><br>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="course-details">No class scheduled.</div>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No schedule available.</p>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
