<!DOCTYPE html>
<html>
<head>
    <title>Class Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />

    <style>
        body {
            background-color: #1c1c1e;
            color: white;
        }
        .navbar {
            background-color: #3a3a3c;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        .navbar a:hover {
            color: #ffffff;
        }
        .btn-primary {
            background-color: #0a84ff;
            border-color: #0a84ff;
        }
        .btn-danger {
            background-color: #ff453a;
            border-color: #ff453a;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-light">
    <a class="navbar-brand" href="AdminCP.php">Back</a>
</nav>
<div class="container">
    <h1>Add Class</h1>
    <form action="classesAction.php" method="post">
        <div class="form-group">
            <label for="class_name">Class Name</label>
            <input type="text" class="form-control" id="class_name" name="class_name" required>
        </div>
        <div class="form-group">
            <label for="year_id">Academic Year</label>
            <?php
            include ('connection.php');
            $resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
            if ($resultAcademic) {
                echo '<select name="year_id" id="year_id" class="form-control">';
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
        <button type="submit" class="btn btn-primary">Add Class</button>
    </form>
    <h1>Classes</h1>
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Class ID</th>
                <th scope="col">Class Name</th>
                <th scope="col">Year ID</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'connection.php';
            $result = mysqli_query($con, "SELECT * FROM classes");
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['class_id'] . '</td>';
                echo '<td>' . $row['class_name'] . '</td>';
                echo '<td>' . $row['YearID'] . '</td>';
                echo '<td><form action="classesAction.php" method="post"><input type="hidden" name="class_id" value="' . $row['class_id'] . '"><button type="submit" class="btn btn-danger">Delete</button></form></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
