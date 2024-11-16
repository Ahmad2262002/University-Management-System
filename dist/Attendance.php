<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lecture Management</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #1c1c1e;
      color: white;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      background-color: #1c1c1e;
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .navbar-brand {
      color: white;
      font-size: 1.5rem;
    }

    .form-container {
      max-width: 600px;
      background-color: #343a40;
      padding: 30px;
      border-radius: 10px;
      margin-top: 80px;
      box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
      margin-bottom: 80px;
      margin-left: 220px;
    }

    .form-container h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    .form-group label {
      color: white;
    }

    .btn-toggle {
      margin-bottom: 20px;
    }

    .student-list {
      display: none;
    }

    .student-list label {
      display: block;
      margin-bottom: 10px;
    }

    .checkbox-group {
      padding: 10px;
      background-color: #454d55;
      border-radius: 5px;
      margin-top: 10px;
    }

    .checkbox-group label {
      display: block;
      margin-bottom: 5px;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="TeacherCP.php">Back</a>
    </div>
  </nav>

  <div class="container">
  <div class="form-container">
      <h1>Lecture Attendance</h1>
      <form action="" method="post">
        <div class="form-group">
          <label for="course">Select Course:</label>
          <select name="course" id="course" class="form-control" required>
            <?php
            include 'connection.php';
            session_start();

            $teacherID = $_SESSION['teacherID'];
            $result = mysqli_query($con, "SELECT teaching.CourseID, course.name FROM teaching INNER JOIN course ON teaching.CourseID=course.CourseID WHERE teacherID = $teacherID");
            if (!$result) {
              die('Invalid query: ' . mysqli_error($con));
            }
            while ($row = mysqli_fetch_assoc($result)) {
              echo '<option value="' . $row['CourseID'] . '">' . $row['name'] . '</option>';
            }
            ?>
          </select>
        </div>

        <div class="form-group">
          <label for="years">Academic Year:</label>
          <?php
          include ('connection.php');
          $resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
          if ($resultAcademic) {
            echo '<select name="years" id="years" class="form-control" required>';
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

        <button type="submit" name="submit_course_year" class="btn btn-primary btn-block mb-4">Submit Course and Year</button>
      </form>

      <?php
      if (isset($_POST['submit_course_year'])) {
        echo '
        <form action="setAttendance.php" method="post">
          <input type="hidden" name="course" value="' . $_POST['course'] . '">
          <input type="hidden" name="years" value="' . $_POST['years'] . '">

          <div class="form-group">
            <label for="lecture">Select Lecture:</label>
            <select name="lecture" id="lecture" class="form-control mb-4" required>';
              $teacherID = $_SESSION['teacherID'];
              $YearID = $_POST['years'];
              $CourseID = $_POST['course'];

              $result = mysqli_query($con, "SELECT l.id, l.startTime, l.endTime, l.CourseID, l.File_url, l.YearID
              FROM lectures l
              JOIN teaching t ON l.CourseID = t.CourseID AND l.YearID = t.YearID
              WHERE t.teacherID = $teacherID AND l.YearID = $YearID AND l.CourseID = $CourseID");
              if (!$result) {
                die('Invalid query: ' . mysqli_error($con));
              }
              while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['id'] . '">' . $row['startTime'] . ' - ' . $row['endTime'] . ' - ' . $row['File_url'] . '</option>';
              }
              echo '
            </select>
          </div>

          <div class="form-group">
            <label for="student">Select Students:</label>
            <button type="button" class="btn btn-outline-primary btn-toggle mb-3" onclick="toggleStudentList()">Show Students</button>
            <div class="student-list">';
            
            // Retrieve students as checkboxes
            $stmt = $con->prepare("SELECT s.students_id, s.name, s.email, s.major, s.photo_url
            FROM student s
            JOIN enrollment e ON s.students_id = e.students_id
            JOIN teaching t ON e.CourseID = t.CourseID AND e.YearID = t.YearID
            WHERE t.teacherID = ? AND t.CourseID = ? AND t.YearID = ?
            ORDER BY s.name;");
            $stmt->bind_param("iii", $teacherID, $CourseID, $YearID);
            $stmt->execute();
            $result = $stmt->get_result();
            if (!$result) {
              die('Invalid query: ' . mysqli_error($con));
            }
            echo '<div class="checkbox-group">';
            while ($row = $result->fetch_assoc()) {
              echo '<div class="form-check">';
              echo '<input class="form-check-input" type="checkbox" name="students[]" value="' . $row['students_id'] . '" id="student_' . $row['students_id'] . '">';
              echo '<label class="form-check-label" for="student_' . $row['students_id'] . '">' . $row['name'] . '</label>';
              echo '</div>';
            }
            echo '</div>';
            echo '
            </div>
          </div>

          <div class="form-group">
            <label for="status">Attendance Status:</label>
            <select name="status" id="status" class="form-control mb-3" required>
              <option value="present">Present</option>
              <option value="absent">Absent</option>
            </select>
          </div>

          <button type="submit" name="submit_attendance" class="btn btn-primary btn-block">Set Attendance</button>
        </form>';
      }

      if (isset($_SESSION['alert_message'])) {
          echo '<div class="alert alert-warning" role="alert">' . nl2br(htmlspecialchars($_SESSION['alert_message'])) . '</div>';
          unset($_SESSION['alert_message']);
      }
      ?>
    </div>
  </div>
  <script>
    function toggleStudentList() {
      var studentList = document.querySelector('.student-list');
      var toggleButton = document.querySelector('.btn-toggle');
      if (studentList.style.display === 'none') {
        studentList.style.display = 'block';
        toggleButton.textContent = 'Hide Students';
      } else {
        studentList.style.display = 'none';
        toggleButton.textContent = 'Show Students';
      }
    }


    document.getElementById('attendance-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting the default way

    var formData = new FormData(this);

    fetch('setAttendance.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      // Handle the response from setAttendance.php
      alert(data); // Display the alert message from PHP
    })
    .catch(error => console.error('Error:', error));
  });
  </script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
