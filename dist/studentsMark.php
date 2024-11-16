<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Exams Grade</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
    <style>
        body {
            background-color:  #1c1c1e;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar div {
            background-color:  #1c1c1e;
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
            margin: 50px auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 10px;
        }
        h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #343a40;
            text-align: center;
        }
        .form-group label {
            font-weight: bold;
            color: #495057;
        }
        .btn-primary {
            background-color: #4b2e83;
            border: none;
        }
        .btn-primary:hover {
            background-color: #3b2266;
        }
        .form-control {
            border-radius: 0.25rem;
        }
        .alert {
            margin-top: 20px;
        }
        .grade-table {
            margin-top: 30px;
        }
        .grade-table th, .grade-table td {
            text-align: center;
        }
        .btn {
            margin-right: 5px;
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
    <h2>Manage Exams Grades</h2>
    <form id="examStudentForm" method="post">
        <div class="form-group">
            <label for="examSelect">Select Exam</label>
            <select class="form-control" id="examSelect" name="examSelect" required>
                <!-- Options will be populated via JavaScript -->
            </select>
        </div>
        <div class="form-group">
            <label for="studentSelect">Select Student</label>
            <select class="form-control" id="studentSelect" name="studentSelect" required>
                <!-- Options will be populated via JavaScript -->
            </select>
        </div>
        <div class="form-group">
            <label for="studentScore">Student Score</label>
            <input type="number" class="form-control" id="studentScore" name="studentScore" required>
        </div>
        <input type="hidden" id="gradeId" name="gradeId">
        <button type="submit" class="btn btn-primary btn-block">Save Score</button>
    </form>
    <div id="message" class="alert d-none"></div>
    <table class="table table-striped grade-table">
        <thead>
            <tr>
                <th>Grade ID</th>
                <th>Student ID</th>
                <th>Exam ID</th>
                <th>Grade</th>
                <th>Course ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="gradeTableBody">
            <!-- Rows will be populated via JavaScript -->
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
    function loadGrades() {
        $.ajax({
            url: 'fetch_grades.php',
            method: 'GET',
            success: function(data) {
                var grades = JSON.parse(data);
                var gradeTableBody = $('#gradeTableBody');
                gradeTableBody.empty();
                grades.forEach(function(grade) {
                    var row = '<tr>' +
                        '<td>' + grade.grade_id + '</td>' +
                        '<td>' + grade.student_name + '</td>' +
                        '<td>' + grade.exam_type + '</td>' +
                        '<td>' + grade.grade + '</td>' +
                        '<td>' + grade.course_name + '</td>' +
                        '<td>' +
                        '<button class="btn btn-warning btn-sm update-btn" data-grade-id="' + grade.grade_id + '" data-student-id="' + grade.students_id + '" data-exam-id="' + grade.ExamID + '" data-grade="' + grade.grade + '">Update</button>' +
                        '<button class="btn btn-danger btn-sm delete-btn" data-grade-id="' + grade.grade_id + '">Delete</button>' +
                        '</td>' +
                        '</tr>';
                    gradeTableBody.append(row);
                });
            }
        });
    }

    // Fetch exams and populate the dropdown
$.ajax({
    url: 'fetch_exams.php',
    method: 'GET',
    success: function(data) {
        var exams = JSON.parse(data);
        var examSelect = $('#examSelect');
        exams.forEach(function(exam) {
            var option = '<option value="' + exam.ExamID + '">' + exam.exam_type + ' ' + exam.course_name + ' (ID: ' + exam.ExamID + ')</option>';
            examSelect.append(option);
        });

        // Trigger the change event to load students for the first exam
        examSelect.trigger('change');
    }
});


    // Fetch students when an exam is selected
    $('#examSelect').change(function() {
        var examId = $(this).val();
        $.ajax({
            url: 'fetch-students.php',
            method: 'GET',
            data: { exam_id: examId },
            success: function(data) {
                var students = JSON.parse(data);
                var studentSelect = $('#studentSelect');
                studentSelect.empty();
                students.forEach(function(student) {
                    var option = '<option value="' + student.students_id + '">' + student.name + ' (ID: ' + student.students_id + ')</option>';
                    studentSelect.append(option);
                });
            }
        });
    });

    // Handle form submission
    $('#examStudentForm').on('submit', function(event){
        event.preventDefault();
        var examId = $('#examSelect').val();
        var studentId = $('#studentSelect').val();
        var studentScore = $('#studentScore').val();
        var gradeId = $('#gradeId').val();
        var url = gradeId ? 'update_student_score.php' : 'save_student_score.php';

        $.ajax({
            url: url,
            method: 'POST',
            data: { examId: examId, studentId: studentId, studentScore: studentScore, gradeId: gradeId },
            success: function(response) {
                var messageDiv = $('#message');
                if (response.startsWith('Error:')) {
                    messageDiv.removeClass('d-none alert-success').addClass('alert-danger').text(response);
                } else {
                    messageDiv.removeClass('d-none alert-danger').addClass('alert-success').text(response);
                    loadGrades();
                    $('#examStudentForm')[0].reset();
                    $('#gradeId').val('');
                }
                setTimeout(function(){
                    messageDiv.addClass('d-none').removeClass('alert-success alert-danger');
                }, 3000);
            },
            error: function() {
                var messageDiv = $('#message');
                messageDiv.removeClass('d-none alert-success').addClass('alert-danger').text('Error saving score');
                setTimeout(function(){
                    messageDiv.addClass('d-none').removeClass('alert-danger');
                }, 3000);
            }
        });
    });

    // Handle update button click
    $(document).on('click', '.update-btn', function() {
        var gradeId = $(this).data('grade-id');
        var studentId = $(this).data('student-id');
        var examId = $(this).data('exam-id');
        var grade = $(this).data('grade');

        $('#gradeId').val(gradeId);
        $('#examSelect').val(examId);
        $('#studentSelect').val(studentId);
        $('#studentScore').val(grade);
    });

    // Handle delete button click
    $(document).on('click', '.delete-btn', function() {
        var gradeId = $(this).data('grade-id');
        if(confirm('Are you sure you want to delete this grade?')) {
            $.ajax({
                url: 'delete_grade.php',
                method: 'POST',
                data: { grade_id: gradeId },
                success: function(response) {
                    if(response.startsWith('Error:')) {
                        alert(response);
                    } else {
                        loadGrades(); // Reload grades after deletion
                    }
                },
                error: function() {
                    alert('Error deleting grade');
                }
            });
        }
    });

    // Load grades on page load
    loadGrades();
});
</script>
</body>
</html>
