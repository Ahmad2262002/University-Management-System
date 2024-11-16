<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
  <title>Lectures</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <style>
    body {
      font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      background-color: #1c1c1e;
      color: white;
      margin: 0;
      padding: 0;
    }

    .form-container {
      width: 90%;
      max-width: 600px;
      background-color: #2c2c2e;
      border-radius: 12px;
      margin: 10vh auto;
      padding: 30px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
    }

    .navbar {
      background-color: #1c1c1e;
      padding: 10px 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    }

    .navbar a {
      color: white;
    }

    .navbar a:hover {
      color: #007aff;
    }

    .table {
      width: 90%;
      max-width: 800px;
      margin: 20px auto;
      border-radius: 10px;
      overflow: hidden;
    }

    .table th,
    .table td {
      border: 1px solid #6a6a6c;
    }

    .table th {
      background-color: #007aff;
      color: white;
    }

    .table tr:nth-child(even) {
      background-color: #2c2c2e;
    }

    .btn-danger {
      background-color: #FF3B30;
      color: white;
      border: none;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .btn-danger:hover {
      background-color: #C32D28;
    }

    .btn-primary {
      background-color: #007aff;
      border: none;
      transition: background-color 0.3s;
      width: 100%; /* Adjusted width to fit the card */
      padding: 10px;
    }

    .btn-primary:hover {
      background-color: #005bb5;
    }

    label {
      font-weight: bold;
    }

    .form-check-label {
      color: white;
    }

    .form-control {
      background-color: #3a3a3c;
      color: white;
      border: 1px solid #6a6a6c;
    }

    .form-control-file {
      color: white;
    }

    select.form-control {
      background-color: #3a3a3c;
      color: white;
      border: 1px solid #6a6a6c;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="TeacherCP.php">Back</a>
    </div>
  </nav>

  <div class="form-container">
    <form action="lecture_action.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="lectureName">Lecture Name:</label>
        <input type="text" id="lectureName" class="form-control" name="lectureName" required>
      </div>

      <div class="form-group">
        <label for="startTime">Start Date and Time:</label>
        <input type="datetime-local" id="startTime" class="form-control" name="startTime" required>
      </div>

      <div class="form-group">
        <label for="endTime">End Date and Time:</label>
        <input type="datetime-local" id="endTime" class="form-control" name="endTime" required>
      </div>

      <div class="form-group">
        <label for="file">Upload File</label>
        <input type="file" class="form-control-file" id="file" name="file">
      </div>

      <div class="form-group">
        <label for="courses">Select Course:</label>
        <?php
        include 'connection.php';
        session_start();

        $teacherID = $_SESSION['teacherID'];
        $result = mysqli_query($con, "SELECT teaching.CourseID, course.name FROM teaching INNER JOIN course ON teaching.CourseID=course.CourseID WHERE teacherID = $teacherID");
        if (!$result) {
          die('Invalid query: ' . mysqli_error($con));
        }
        while ($row = mysqli_fetch_assoc($result)) {
          echo '<div class="form-check">';
          echo '<input type="radio" id="course' . $row['CourseID'] . '" name="courses" value="' . $row['CourseID'] . '" class="form-check-input">';
          echo '<label for="course' . $row['CourseID'] . '" class="form-check-label">' . $row['name'] . '</label>';
          echo '</div>';
        }
        ?>
      </div>

      <div class="form-group">
        <label for="year_id">Academic Year:</label>
        <?php
        $resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
        if ($resultAcademic) {
          echo '<select class="form-control" name="year_id" id="year_id">';
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

      <div class="form-group text-center">
        <input type="submit" class="btn btn-primary" value="Submit">
      </div>
    </form>
  </div>

  <?php
  $TeacherID = $_SESSION['teacherID']; // Retrieve teacherID from session

  include 'connection.php';

  $result = mysqli_query($con, "SELECT l.id, l.startTime, l.endTime, l.CourseID, l.File_url, l.YearID FROM lectures AS l INNER JOIN teaching AS t ON l.CourseID = t.CourseID AND l.YearID = t.YearID WHERE teacherID = $TeacherID;");

  echo '<div class="table-responsive">';
  echo '<table class="table table-dark table-striped">';
  echo '<thead>';
  echo '<tr>';
  echo '<th>Start Time</th>';
  echo '<th>End Time</th>';
  echo '<th>File Path</th>';
  echo '<th>Action</th>';
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

  while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . $row['startTime'] . '</td>';
    echo '<td>' . $row['endTime'] . '</td>';
    echo '<td><a href="' . $row['File_url'] . '" class="text-white" target="_blank">' . $row['File_url'] . '</a></td>';
    echo '<td><a href="lecture_action.php?id=' . $row['id'] . '" class="btn btn-danger">Delete</a></td>';
  }

  echo '</tbody>';
  echo '</table>';
  echo '</div>';
  ?>
</body>

</html>
