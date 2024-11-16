<!DOCTYPE html>
<html>
<head>
    <title>Section Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />

    <style>
        body {
            background-color: #1c1c1e; /* Dark mode background color */
            color: white; /* Dark mode text color */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Font style */
            padding-top: 20px;
        }
        .navbar {
            background-color: #2c2c2e; /* Dark mode navbar color */
        }
        .navbar a {
            color: white; /* Text color */
            text-decoration: none; /* Remove underline from link */
            transition: color 0.3s; /* Smooth color transition */
        }
        .navbar a:hover {
            color: #ffffff; /* White text color when hovered */
        }
        .container {
            max-width: 800px; /* Adjust container width as needed */
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #0a84ff; /* iOS 18 primary button color */
            border-color: #0a84ff; /* iOS 18 primary button color */
        }
        .btn-danger {
            background-color: #ff453a; /* iOS 18 delete button color */
            border-color: #ff453a; /* iOS 18 delete button color */
        }
        table {
            margin-top: 20px;
        }
        th {
            background-color: #2c2c2e; /* Dark mode table header background color */
            color: white;
        }
        .table-dark tbody tr:nth-of-type(odd) {
            background-color: #3a3a3c; /* Dark mode table odd row background color */
        }
        .table-dark tbody tr:nth-of-type(even) {
            background-color: #444446; /* Dark mode table even row background color */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark">
    <a class="navbar-brand" href="Settings.php">Back</a>
</nav>
<div class="container">
    <h1>Add Section</h1>
    <form action="sectionsAction.php" method="post">
        <div class="form-group">
            <label for="section_name">Section Name</label>
            <input type="text" class="form-control" id="section_name" name="section_name" required>
        </div>
        <div class="form-group">
            <label for="year_id">Academic Year</label>
            <?php
            include('connection.php');
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
        <div class="form-group">
            <label for="class_id">Select Class</label>
            <?php
            $resultClasses = mysqli_query($con, "SELECT * FROM classes");
            if ($resultClasses) {
                echo '<select name="class_id" id="class_id" class="form-control" onchange="updateClassName()">';
                echo '<option value="">Select Class</option>';
                while ($classRow = mysqli_fetch_assoc($resultClasses)) {
                    $classID = htmlspecialchars($classRow['class_id']);
                    $className = htmlspecialchars($classRow['class_name']);
                    echo "<option value=\"$classID\" data-class-name=\"$className\">$className</option>";
                }
                echo '</select>';
                echo '<input type="hidden" name="class_name" id="class_name">';
            } else {
                echo '<p>No classes found.</p>';
            }
            ?>
        </div>
        <button type="submit" class="btn btn-primary">Add Section</button>
    </form>
    <h1>Sections</h1>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th scope="col">Section ID</th>
                <th scope="col">Section Name</th>
                <th scope="col">Class ID</th>
                <th scope="col">Year ID</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM sections");
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['section_id'] . '</td>';
                echo '<td>' . $row['section_name'] . '</td>';
                echo '<td>' . $row['class_id']. '</td>';
                echo '<td>' . $row['YearID'] . '</td>';
                echo '<td><form action="sectionsAction.php" method="post"><input type="hidden" name="section_id" value="' . $row['section_id'] . '"><button type="submit" class="btn btn-danger">Delete</button></form></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function updateClassName() {
    const classSelect = document.getElementById('class_id');
    const selectedOption = classSelect.options[classSelect.selectedIndex];
    const className = selectedOption.getAttribute('data-class-name');
    document.getElementById('class_name').value = className;
}
</script>

</body>
</html>
