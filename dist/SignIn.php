<?php
include 'connection.php';
include "../dist/security.php";
$sql = "SELECT * FROM departments";
$resultDepartment = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Sign-In</title>
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1c1c1e;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .form-container {
            background-color: #2c2c2e;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 500px;
        }

        .form-container h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
        }

        .form-group label {
            color: #bbb;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #3a3a3c;
            color: #fff;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group button {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    
    

        .btn-container a, button {
            color: #007bff;
            text-decoration: none;
            background-color: #2c2c2e;
            border: 2px solid #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-container a:hover , button:hover{
            color: #fff;
            background-color: #007bff;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Admin Sign-In</h2>
        <form action="SignInAction.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="photo">Profile Photo:</label>
                <input type="file" id="photo" name="Profile_photo" required>
            </div>
            <div class="form-group">
                <label for="department" class="form-label">Department</label>
                <?php
                if (mysqli_num_rows($resultDepartment)) {
                    echo '<select class="form-select" name="department" id="department" required>';
                    echo "<option value=''>Select a department</option>";
                    while ($row = mysqli_fetch_assoc($resultDepartment)) {
                        $DepartmentID = htmlspecialchars($row['dept_id']);
                        $DepartmentName = htmlspecialchars($row['dept_name']);
                        echo "<option value='$DepartmentID'>$DepartmentName</option>";
                    }
                    echo "</select>";
                } else {
                    echo '<p>No departments found.</p>';
                }
                ?>
            </div>
            <div class="form-group">
                <label for="YearID">Academic Year</label>
                <?php
                include('connection.php');
                $resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
                if ($resultAcademic) {
                    echo '<select name="YearID" id="year_id">';
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
            <div class="btn-container">
                <a href="LogIn.php" class="reset">Back</a>
                <button type="submit">Sign in</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('adminForm').addEventListener('submit', function (event) {
            event.preventDefault();
            alert('Form submitted!');
        });
    </script>
</body>

</html>
