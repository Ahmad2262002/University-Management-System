<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
    <title>Add Assignment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
        body {
            background-color: #1c1c1e;
            color: white;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .form-container {
            width: 90%;
            max-width: 600px;
            background-color: #2c2c2e;
            border-radius: 12px;
            margin: 10vh auto;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .form-control,
        .form-control-file {
            background-color: #3a3a3c;
            border: 1px solid #5a5a5c;
            color: white;
        }

        .form-control:focus,
        .form-control-file:focus {
            background-color: #4a4a4c;
            border-color: #007aff;
            color: white;
        }

        .btn-primary {
            background-color: #0a84ff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #007aff;
        }

        .btn-danger {
            background-color: #FF3B30;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-danger:hover {
            background-color: #C32D28;
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

        .table-container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
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
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <a class="nav-link" href="TeacherCP.php">Back</a>
        </div>
    </nav>
    <div class="form-container">
        <h1 class="text-center">Add Assignment</h1>
        <form action="assignment_action.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="file">Upload File</label>
                <input type="file" class="form-control-file" id="file" name="file">
            </div>
            <div class="form-group">
                <label for="courses">Course: </label>
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
                <label for="years">Academic Year</label>
                <?php
                include('connection.php');
                $resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
                if ($resultAcademic) {
                    echo '<select name="years" id="years" class="form-control">';
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
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
    </div>
    <div class="table-container">
        <?php
        $TeacherID = $_SESSION['teacherID']; // Retrieve teacherID from session

        include 'connection.php';

        $result = mysqli_query($con, "SELECT a.assignment_id, a.title, a.description, a.assignment_url, a.CourseID, a.YearID FROM assignments AS a INNER JOIN teaching AS t ON a.CourseID = t.CourseID AND a.YearID = t.YearID WHERE teacherID = $TeacherID;");
        if (!$result) {
            die('Invalid query: ' . mysqli_error($con));
        }
        echo '<table class="table table-dark table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th scope="col">Name</th>';
        echo '<th scope="col">Description</th>';
        echo '<th scope="col">Path</th>';
        echo '<th scope="col">Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['title'] . '</td>';
            echo '<td>' . $row['description'] . '</td>';
            echo '<td><a href="' . $row['assignment_url'] . '" class="text-white" target="_blank">' . $row['assignment_url'] . '</a></td>';
            echo '<td><a href="assignment_action.php?id=' . $row['assignment_id'] . '" class="btn btn-danger">Delete</a></td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        ?>
    </div>
</body>

</html>
