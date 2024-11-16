<?php
session_start();

include "../dist/connection.php";

if (!isset($_SESSION['students_id'])) {
    die("Session variable 'students_id' not set.");
}

$StudentID = $_SESSION['students_id'];
$CourseID = $_GET['id'];

// Fetch course details
$query = "SELECT
    semister.SemesterName,
    course.CourseID,
    course.name AS course_name,
    course.Credits,
    course.picture_url 
FROM
    semister
JOIN
    semister_courses ON semister.SemesterID = semister_courses.SemesterID
JOIN
    course ON semister_courses.CourseID = course.CourseID
WHERE
    course.CourseID = ? AND course.status = '1';";

$stmt = $con->prepare($query);
if ($stmt === false) {
    die("Error preparing statement: " . $con->error);
}

$stmt->bind_param("i", $CourseID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
    // Set the course name in the session
    $_SESSION['CourseName'] = $course['course_name'];

    // Query to fetch assignments with teacher details
    $assignmentQuery = "SELECT a.assignment_id, a.title, a.description, a.assignment_url, t.name AS teacher_name, t.email AS teacher_email, t.phone_number AS teacher_phone 
                        FROM assignments AS a
                        JOIN teaching AS te ON a.CourseID = te.CourseID
                        JOIN teachers AS t ON te.teacherID = t.teacherID
                        WHERE a.CourseID = ?";

    $stmt_assignments = $con->prepare($assignmentQuery);
    if ($stmt_assignments === false) {
        die("Error preparing statement: " . $con->error);
    }

    $stmt_assignments->bind_param("i", $CourseID);
    $stmt_assignments->execute();
    $result_assignments = $stmt_assignments->get_result();

    $assignments = [];
    while ($row_assignment = $result_assignments->fetch_assoc()) {
        $assignments[] = $row_assignment;
    }

    $stmt_assignments->close();

    // Query to fetch exams with teacher details
    $examQuery = "SELECT e.ExamID, e.exam_type, e.date, e.File_url, t.name AS teacher_name, t.email AS teacher_email, t.phone_number AS teacher_phone 
                  FROM exam AS e
                  JOIN teaching AS te ON e.CourseID = te.CourseID
                  JOIN teachers AS t ON te.teacherID = t.teacherID
                  JOIN course AS c ON e.CourseID = c.CourseID
                  WHERE e.CourseID = ? AND c.status = '1'";

    $stmt_exams = $con->prepare($examQuery);
    if ($stmt_exams === false) {
        die("Error preparing statement: " . $con->error);
    }

    $stmt_exams->bind_param("i", $CourseID);
    $stmt_exams->execute();
    $result_exams = $stmt_exams->get_result();

    $exams = [];
    while ($row_exam = $result_exams->fetch_assoc()) {
        // Fetch grade for each exam
        $gradeQuery = "SELECT grade FROM grades WHERE students_id = ? AND ExamID = ?";
        $stmt_grade = $con->prepare($gradeQuery);
        $stmt_grade->bind_param("ii", $StudentID, $row_exam['ExamID']);
        $stmt_grade->execute();
        $result_grade = $stmt_grade->get_result();
        if ($result_grade->num_rows > 0) {
            $grade = $result_grade->fetch_assoc()['grade'];
        } else {
            $grade = "No grades yet";
        }
        $stmt_grade->close();
        $row_exam['grade'] = $grade;

        $exams[] = $row_exam;
    }

    // Fetch session email if set
    $student_email = $_SESSION['email'] ?? '';

    $stmt_exams->close();

    // Query to fetch total grades from mark table
    $markQuery = "SELECT SUM(final_exams_score) as total_grade FROM mark WHERE students_id = ? AND CourseID = ?";
    $stmt_mark = $con->prepare($markQuery);
    if ($stmt_mark === false) {
        die("Error preparing statement: " . $con->error);
    }
    $stmt_mark->bind_param("ii", $StudentID, $CourseID);
    $stmt_mark->execute();
    $result_mark = $stmt_mark->get_result();
    $total_grade = $result_mark->fetch_assoc()['total_grade'] ?? 0;
    $stmt_mark->close();

    // Query to fetch lectures with teacher details
    $lectureQuery = "SELECT l.id, l.startTime, l.endTime, l.File_url, l.Lecture_name, t.name AS teacher_name, t.email AS teacher_email, t.phone_number AS teacher_phone 
                     FROM lectures AS l
                     JOIN teaching AS te ON l.CourseID = te.CourseID AND l.YearID = te.YearID
                     JOIN teachers AS t ON te.teacherID = t.teacherID
                     JOIN course AS c ON l.CourseID = c.CourseID
                     WHERE l.CourseID = ? AND c.status = '1'";

    $stmt_lectures = $con->prepare($lectureQuery);
    if ($stmt_lectures === false) {
        die("Error preparing statement: " . $con->error);
    }

    $stmt_lectures->bind_param("i", $CourseID);
    $stmt_lectures->execute();
    $result_lectures = $stmt_lectures->get_result();

    $lectures = [];
    while ($row_lecture = $result_lectures->fetch_assoc()) {
        $lectures[] = $row_lecture;
    }

    $stmt_lectures->close();
    // Query to fetch attendance data
    $attendanceQuery = "SELECT COUNT(*) AS attendance_count FROM attendance WHERE CourseID = ? and students_id = ? ";
    $stmt_attendance = $con->prepare($attendanceQuery);
    if ($stmt_attendance === false) {
        die("Error preparing statement: " . $con->error);
    }

    $stmt_attendance->bind_param("ii", $CourseID,$StudentID);
    $stmt_attendance->execute();
    $result_attendance = $stmt_attendance->get_result();
    $attendance_count = $result_attendance->fetch_assoc()['attendance_count'] ?? 0;
    $stmt_attendance->close();

    // Fetch detailed attendance data for the popup
    $detailedAttendanceQuery = "SELECT a.AttendanceDate, a.status, l.Lecture_name 
    FROM attendance AS a 
    JOIN lectures AS l ON a.lecture_id = l.id 
    WHERE a.CourseID = ? and students_id=?";

$stmt_detailed_attendance = $con->prepare($detailedAttendanceQuery);
    if ($stmt_detailed_attendance === false) {
        die("Error preparing statement: " . $con->error);
    }

    $stmt_detailed_attendance->bind_param("ii", $CourseID, $StudentID);
    $stmt_detailed_attendance->execute();
    $result_detailed_attendance = $stmt_detailed_attendance->get_result();

    $detailed_attendance = [];
    while ($row_attendance = $result_detailed_attendance->fetch_assoc()) {
        $detailed_attendance[] = $row_attendance;
    }

    $stmt_detailed_attendance->close();

} else {
    die("Course not found or inactive.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['course_name']); ?></title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        .navbar-custom {
            background-color: #0056b3;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            color: #fff;
            font-weight: bold;
        }

        .course-card {
            background-size: cover;
            background-position: center;
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .course-card h1,
        .course-card h6,
        .course-card p {
            color: #fff;
        }

        .card-body {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 12px;
        }

        .list-group-item {
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #0069d9;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control-file,
        .form-control {
            border-radius: 5px;
        }

        .mt-4 {
            margin-top: 2rem !important;
        }

        .mt-3 {
            margin-top: 1.5rem !important;
        }

        .lecture-details {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .lecture-details h5 {
            margin-bottom: 10px;
            color: #0056b3;
        }

        .lecture-details p {
            margin-bottom: 5px;
            font-size: 16px;
        }

        .container {
            max-width: 900px;
        }

        .assignment-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .assignment-card h5 {
            color: #007bff;
            margin-bottom: 15px;
        }

        .assignment-card p {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .grade-card {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .grade-card h5 {
            color: #007bff;
            margin-bottom: 15px;
        }

        .grade-card p {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .total-grade {
            font-weight: bold;
            font-size: 18px;
            color: #007bff;
            margin-top: 10px;
        }
        .modal-header {
            background-color: #0056b3;
            color: #fff;
        }

        .modal-body {
            background-color: #f8f9fa;
            color: #212529;
        }

        .alert {
            cursor: pointer;
            margin-bottom: 0;
        }

        .submission-message {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 105, 217, 0.9);
    color: #fff;
    padding: 15px 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 9999;
}

         
    </style>
</head>

<body>

<nav class="navbar navbar-expand-sm navbar-custom navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="StudentCp.php" onclick="window.history.back();">Back</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="course-card" style="background-image: url('<?php echo htmlspecialchars($course['picture_url']); ?>');">
            <div class="card-body">
                <h1 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h1>
                <h6 class="card-subtitle mb-2"><?php echo htmlspecialchars($course['SemesterName']); ?></h6>
                <p class="card-text">Credits: <?php echo htmlspecialchars($course['Credits']); ?></p>
                <div class="alert alert-info" onclick="showAttendancePopup()">
                    Attendance: <?php echo $attendance_count; ?> (Click for details)
                </div>
            </div>
           
        </div>
        
        <div class="exam-section mt-4">
            <h4>Exams</h4>
            <?php if (count($exams) > 0) : ?>
                <?php foreach ($exams as $exam) : ?>
                    <div class="grade-card">
                        <h5><?php echo htmlspecialchars($exam['exam_type']); ?></h5>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($exam['date']); ?></p>
                        <p><strong>Teacher:</strong> <?php echo htmlspecialchars($exam['teacher_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($exam['teacher_email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($exam['teacher_phone']); ?></p>
                        
                        <p class="total-grade">Grade: <?php echo htmlspecialchars($exam['grade']); ?></p>
                    </div>
                <?php endforeach; ?>
                <p class="total-grade">Total Grades: <?php echo htmlspecialchars($total_grade); ?></p>
            <?php else : ?>
                <p>No exams found.</p>
            <?php endif; ?>
        </div>
         <!-- Display Lectures -->
         <div class="mt-4">
                    <h3>Lectures</h3>
                    <?php if (!empty($lectures)): ?>
                        <div class="row">
                            <?php foreach ($lectures as $lecture): ?>
                                <div class="col-md-6">
                                    <div class="lecture-details mb-4">
                                    <h5><?php echo htmlspecialchars($lecture['Lecture_name']); ?></h5>
                        <p><strong>Start Time:</strong> <?php echo htmlspecialchars($lecture['startTime']); ?></p>
                        <p><strong>End Time:</strong> <?php echo htmlspecialchars($lecture['endTime']); ?></p>
                        <p><strong>Teacher:</strong> <?php echo htmlspecialchars($lecture['teacher_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($lecture['teacher_email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($lecture['teacher_phone']); ?></p>
                       
                                        </p>
                                        <?php if (!empty($lecture['File_url'])): ?>
                                            <a href="<?php echo htmlspecialchars($lecture['File_url']); ?>" target="_blank">View
                                                File</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>No lectures found for this course.</p>
                    <?php endif; ?>
                </div>
                
        
        <div class="assignment-section mt-4">
        <h3>Assignments</h3>
<?php if (!empty($assignments)): ?>
    <?php foreach ($assignments as $assignment): ?>
        <div class="assignment-card">
            <h5><?php echo htmlspecialchars($assignment['title']); ?></h5>
            <p><?php echo htmlspecialchars($assignment['description']); ?></p>
            <p>Assigned by: <?php echo htmlspecialchars($assignment['teacher_name']); ?></p>
            <p>Contact: <?php echo htmlspecialchars($assignment['teacher_email']); ?>,
                <?php echo htmlspecialchars($assignment['teacher_phone']); ?>
            </p>
            <?php if (!empty($assignment['assignment_url'])): ?>
                <a href="<?php echo htmlspecialchars($assignment['assignment_url']); ?>" target="_blank">Download</a>
            <?php endif; ?>
            <!-- Form for submitting solution -->
            <!-- Form for submitting solution -->
<form action="submit_solution.php" method="post" enctype="multipart/form-data" class="mt-3"  id="submitForm_<?php echo htmlspecialchars($assignment['assignment_id']); ?>" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="file_<?php echo htmlspecialchars($assignment['assignment_id']); ?>" class="form-label">Upload Solution</label>
        <input type="file" class="form-control" id="file_<?php echo htmlspecialchars($assignment['assignment_id']); ?>" name="file"
            accept=".pdf,.doc,.docx,png,jpg,jpeg" required>
    </div>
    <input type="hidden" name="assignment_id" value="<?php echo htmlspecialchars($assignment['assignment_id']); ?>">
    <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($CourseID); ?>">
    <button type="button" class="btn btn-primary btn-lg" onclick="submitAssignment(<?php echo htmlspecialchars($assignment['assignment_id']); ?>)">Submit Solution</button>
</form>

            <?php if (isset($_SESSION['email']) && $_SESSION['email'] == $student_email): ?>
                <script>
                    // Check if assignment form should be hidden based on session email
                    var hiddenAssignments = <?php echo json_encode($_SESSION['hidden_assignments'] ?? []); ?>;
                    if (hiddenAssignments.includes(<?php echo htmlspecialchars($assignment['assignment_id']); ?>)) {
                        document.getElementById("submitForm_<?php echo htmlspecialchars($assignment['assignment_id']); ?>").style.display = 'none';
                    }
                </script>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No assignments found for this course.</p>
<?php endif; ?>

<script>
    function submitAssignment(assignmentId) {
    var formId = "submitForm_" + assignmentId;
    var form = document.getElementById(formId);
    
    if (form) {
        var formData = new FormData(form);
        
        // Create a new XMLHttpRequest to submit the form
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "submit_solution.php", true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Successfully submitted solution, now mark as hidden
                var submissionMessage = $("<div class='submission-message'>You have submitted your solution.</div>");
                submissionMessage.hide().appendTo('body').fadeIn(500).delay(2000).fadeOut(500, function() {
                    $(this).remove(); // Remove message after fading out
                });

                // Hide the form after submission
                form.style.display = 'none';

                // Now mark assignment as hidden
                var xhrHidden = new XMLHttpRequest();
                xhrHidden.open("POST", "mark_assignment_hidden.php", true);
                xhrHidden.setRequestHeader("Content-Type", "application/json");
                xhrHidden.onreadystatechange = function() {
                    if (xhrHidden.readyState === XMLHttpRequest.DONE) {
                        if (xhrHidden.status === 200) {
                            console.log("Assignment marked as hidden for user");
                        } else {
                            console.error("Error marking assignment as hidden: " + xhrHidden.status);
                        }
                    }
                };
                var data = JSON.stringify({ assignment_id: assignmentId });
                xhrHidden.send(data);
            } else {
                console.error("Error submitting solution: " + xhr.status);
            }
        };
        xhr.send(formData);
    }
}


    
</script>

        </div>
    </div>
     <!-- Attendance Modal -->
     <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">Attendance Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
    <ul class="list-group">
        <?php foreach ($detailed_attendance as $attendance) : ?>
            <li class="list-group-item">
                <strong>Date:</strong> <?php echo htmlspecialchars($attendance['AttendanceDate']); ?> | 
                <strong>Status:</strong> <?php echo htmlspecialchars($attendance['status']); ?> | 
                <strong>Lecture:</strong> <?php echo htmlspecialchars($attendance['Lecture_name']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showAttendancePopup() {
            var attendanceModal = new bootstrap.Modal(document.getElementById('attendanceModal'));
            attendanceModal.show();
        }
    </script>
</body>

</html>
