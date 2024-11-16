<?php
include "../dist/security.php";
$sql = "SELECT * FROM departments";
$resultDepartment = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title> Sign-In</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap');
        

        body {
            font-family: 'Roboto', sans-serif;
            background: #878684;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* Changed from height to min-height */
            margin: 0;
            color: #000;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
            animation: slide-up 1s ease;
        }

        @keyframes slide-up {
            0% {
                transform: translateY(100%);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .form-container h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #666;
            margin-bottom: 10px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        .form-group input:focus {
            border-color: #007BFF;
        }

        .form-group input[type="file"] {
            padding: 3px;
        }

        .form-group button {
            width: 100%;
            padding: 15px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
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

        .btn-container button {
            color: #fff;
            background-color: #007BFF;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .btn-container button:hover {
            background-color: #0056b3;
        }

        .reset {
            color: #007BFF;
            text-decoration: none;
            background-color: #fff;
            border: 2px solid #007BFF;
            padding: 10px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .reset:hover {
            color: #fff;
            background-color: #007BFF;
            text-decoration: none;
        }

        /* Added style for toggle button */
        .toggle-courses-btn {
            color: #fff;
            background-color: #007BFF;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
            margin-bottom: 20px;
            /* Adjust margin as needed */
        }

        .toggle-courses-btn:hover {
            background-color: #0056b3;
        }

        .courses-section {
            display: none;
        }

        .courses-section.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: #666;
            margin-bottom: 5px;
        }

        .form-select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        .form-select:focus {
            border-color: #007BFF;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Registration Sign-In</h2>
        <form id="adminForm" action="RegisterSignAction.php" method="post" >
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

            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone_number" name="phone_number"
                    placeholder="e.g., 123-45-678" required>
            </div>
          

            <div class="form-group">
            <label for="department" class="form-label">Department</label>
            <?php
            if (mysqli_num_rows($resultDepartment)) {
                echo '<select class="form-select" name="department" id="department" required>';
                echo "<option value=''>Select a department</option>";

                while ($row = mysqli_fetch_assoc($resultDepartment)) {
                    $DepartmentID = $row['dept_id'];
                    $DepartmentName = $row['dept_name'];
                    // Store department ID in a session variable
                    $_SESSION['department_id'] = $department;
                    echo "<option value='$DepartmentID'>$DepartmentName</option>";
                }

                echo "</select>";
            }
            ?>

        </div>
           
            <div class="form-group">
                <label for="year-id" class="form-label">Academic Year</label>
                <?php
                include ('connection.php');
                $resultAcademic = mysqli_query($con, "SELECT * FROM academicyear");
                if ($resultAcademic) {
                    echo '<select class="form-select" name="year-id" id="year-id" required>';
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
                <a href="Staff.php" class="reset">Back</a>
                <button type="submit">Sign in</button>
            </div>
        </form>

    </div>
    
</body>

</html>
