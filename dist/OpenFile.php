<?php
include "../dist/connection.php";

include "../dist/security.php";
$sql = "SELECT * FROM departments";
$resultDepartment = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />

    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
        }
        .container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .form-label {
            color: #e0e0e0;
        }
        .form-control {
            background-color: #2c2c2c;
            color: #e0e0e0;
            border: 1px solid #444;
        }
        .form-control:focus {
            background-color: #2c2c2c;
            color: #e0e0e0;
            border-color: #555;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #2c2c2c;
        }
        .table-striped > tbody > tr:nth-of-type(even) {
            background-color: #1e1e1e;
        }
        .table thead th {
            background-color: #333;
            color: #e0e0e0;
        }
        .table tbody td {
            color: #e0e0e0;
        }

        .navbar {
            background-color: #2c2c2e;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }
        .navbar a {
            color: #f0f0f3;
            text-decoration: none;
        }
        .navbar a:hover {
            color: #ddd;
        }
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 10px;
            text-align: left;
        }

        .form-group input, 
        .form-group select {
            width: 100%;
            padding: 12px;
            border-radius: 15px;
            border: none;
            background-color: #3a3a3c;
            color: #fff;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.5);
            font-size: 16px;
            outline: none;
        }

        .mb-3 input::placeholder {
            color: #b0b0b5;
        }

        .form-group button,
        .button-group button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            box-shadow: 0px 10px 20px rgba(0, 123, 255, 0.4);
            transition: transform 0.3s, background-color 0.3s;
            cursor: pointer;
            margin: 10px;
            font-size: 16px;
        }

        .form-group button:hover,
        .button-group button:hover {
            background-color: #005cbf;
            transform: scale(1.05);
        }

    </style>
</head>
<body>
<nav class="navbar">
        <div class="container">
            <a class="nav-link" href="#" onclick="window.history.back();">Back</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center">Student Management</h1>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form action="student_action.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="tel"   class="form-control" id="phone_number" name="phone_number" placeholder="e.g., 123-45-678" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password"  required>
                    </div>
                    <div class="form-group">
                    <label for="year_id">Academic Year</label>
    <?php
    include ('connection.php');
    $resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
    if ($resultAcademic) {
        echo '<select name="year_id" id="year_id">';
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
                <label for="major">Department</label>
                <?php
                if (mysqli_num_rows($resultDepartment)) {
                    echo '<select name="major" id="major">';
                    echo "<option value=''>Select a department</option>";
                    while ($row = mysqli_fetch_assoc($resultDepartment)) {
                        $DepartmentID = $row['dept_id'];
                        $DepartmentName = $row['dept_name'];
                        echo "<option value='$DepartmentID'>$DepartmentName</option>";
                    }
                    echo "</select>";
                }
                ?>
            </div>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </form>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Student List</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Photo</th>
                            <th>Year</th>
                            <th>Major</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Fetch and display student records here -->
                        <?php
                        include 'fetch_students.php';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
