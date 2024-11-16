<?php
include ('connection.php');

$student_id = $_GET['students_id'];

// Fetch student data
$sql = "SELECT * FROM student WHERE students_id = '$student_id'";
$result = $con->query($sql);
$row = $result->fetch_assoc();

// Fetch academic years
$academicYearsSql = "SELECT * FROM academicyear";
$academicYearsResult = $con->query($academicYearsSql);

// Fetch departments
$departmentsSql = "SELECT * FROM departments";
$departmentsResult = $con->query($departmentsSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+eudb05abM2xcRbqogskj1u5W0Zgh4ymNfH2c9v" crossorigin="anonymous">
    <style>
        body {
            background-color: #1c1c1e;
            color: #f0f0f3;
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
        .container {
            background-color: #2c2c2e;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            background-color: #3a3a3c;
            border: 1px solid #48484a;
            color: #f0f0f3;
        }
        .form-control:focus {
            background-color: #3a3a3c;
            color: #f0f0f3;
            border-color: #646464;
            box-shadow: none;
        }
        .form-label {
            color: #f0f0f3;
        }
        .btn-primary {
            background-color: #007aff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #005fcb;
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
        <h1 class="text-center">Update Student</h1>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form action="student_action.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($row['students_id']); ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($row['phone_number']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                        <input type="hidden" name="photo_url" value="<?php echo htmlspecialchars($row['photo_url']); ?>">
                        <input type="hidden" name="photo_name" value="<?php echo htmlspecialchars($row['photo_name']); ?>">
                        <img src="<?php echo htmlspecialchars($row['photo_url']); ?>" width="50">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($row['password']); ?>" required>
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="fa fa-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="year_id" class="form-label">Academic Year</label>
                        <select name="year_id" id="year_id" class="form-control" required>
                            <option value="">Select Academic Year</option>
                            <?php
                            while ($academicYear = $academicYearsResult->fetch_assoc()) {
                                $yearID = htmlspecialchars($academicYear['YearID']);
                                $startDate = htmlspecialchars($academicYear['startdate']);
                                $endDate = htmlspecialchars($academicYear['enddate']);
                                $selected = ($yearID == $row['YearID']) ? 'selected' : '';
                                echo "<option value=\"$yearID\" $selected>$startDate | $endDate</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dept_id" class="form-label">Department</label>
                        <select name="dept_id" id="dept_id" class="form-control" required>
                            <option value="">Select Department</option>
                            <?php
                            while ($department = $departmentsResult->fetch_assoc()) {
                                $deptID = htmlspecialchars($department['dept_id']);
                                $deptName = htmlspecialchars($department['dept_name']);
                                $selected = ($deptID == $row['major']) ? 'selected' : '';
                                echo "<option value=\"$deptID\" $selected>$deptName</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle icon class
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$con->close();
?>
