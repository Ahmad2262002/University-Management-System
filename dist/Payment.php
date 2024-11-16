
<!DOCTYPE html>
<html>
<head>
    <title>Payment Form</title>
    <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #1c1c1e; 
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            width: 90%;
            max-width: 500px;
            background-color: #2c2c2e; 
            border-radius: 12px;
            margin-top: 10pc;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); 
        }
        .form-field {
            margin-bottom: 20px;
        }
        .form-field label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500; 
        }
        .form-field input, select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px; 
            background-color: #6a6a6c; 
            color: white;
        }
        .form-field input[type="submit"] {
            background-color: #007aff; 
            color: white;
            cursor: pointer;
            transition: background-color 0.3s; 
        }
        .form-field input[type="submit"]:hover {
            background-color: #005cbf; 
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1c1c1e;
            padding: 10px 20px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2); 
        }
        .navbar a {
            background-color: #007aff;
            color: white;
            padding: 10px 20px;
            border-radius: 15px;
            text-decoration: none;
            transition: background-color 0.3s; 
        }
        .navbar a:hover {
            background-color: #005cbf; 
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="AdminCP.php">Back</a>
    </div>
    <div class="form-container">
        <form action="submit_payment.php" method="post">
            <div class="form-field">
                <label for="student_id">Student ID</label>
                <input type="text" id="student_id" name="student_id">
            </div>
            <div class="form-field">
    <label for="semester">Semester</label>
    <select id="semester" name="semester" class="form-control" required>
        <option value="">Select a semester </option>
        <?php
        include 'connection.php';

        $sql = "SELECT s.SemesterID, s.SemesterName, cy.name 
        FROM semister s
        JOIN curriculum_year cy ON s.curriculum_year_id = cy.curriculum_year_id";


        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Combine SemesterName and curriculum year name in the same option
                echo '<option value="' . $row["SemesterID"] . '-' . $row["curriculum_year_id"] . '">'
                     . $row["SemesterName"] . ' - ' . $row["name"] . '</option>';
            }
        }
        ?>
    </select>
</div>

            <div class="form-field">
                <label for="method">Payment Method</label>
                <input type="text" id="method" name="method">
            </div>
            <div class="form-field">
                <label for="amount">Amount</label>
                <input type="text" id="amount" name="amount">
            </div>
            <div class="form-field">
                <label for="currency">Currency</label>
                <select id="currency" name="currency">
                    <option value="USD">USD</option>
                    <option value="LB">LB</option>
                </select>
            </div>
            <div class="form-field">
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

            


            <div class="form-field">
                <label for="date">Payment Date</label>
                <input type="date" id="date" name="date">
            </div>
            
          

            <div class="form-field">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
