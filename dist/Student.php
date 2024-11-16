<?php
include 'connection.php';
include "../dist/security.php";
$sql = "SELECT * FROM departments";
$resultDepartment = mysqli_query($con, $sql);

$resultCurriculumYear = mysqli_query($con, "SELECT * FROM curriculum_year");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />
    <title>Student Management</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.7/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.7/dist/sweetalert2.all.min.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #1c1c1e;
            color: #fff;
            padding: 20px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 1000px;
            margin: 20px; /* Added margin for spacing */
            padding: 20px;
            background-color: #2c2c2e;
            border-radius: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            text-align: center;
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

        .form-group input::placeholder {
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

        h1,
        h2 {
            margin-bottom: 20px;
        }
    </style>

<script>
    $(document).ready(function () {
        $('#department, #Curriculum').on('change', function () {
            var departmentID = $('#department').val();
            var curriculumID = $('#Curriculum').val();

            // Check if both department and curriculum year are selected
            if (departmentID && curriculumID) {
                $.ajax({
                    type: 'POST',
                    url: 'fetch_semesters.php',
                    data: {
                        departmentID: departmentID,
                        curriculumID: curriculumID
                    },
                    success: function (response) {
                        var semesters = JSON.parse(response);
                        var semesterSelect = $('#semester');
                        semesterSelect.empty(); // Clear any existing options

                        if (semesters.length > 0) {
                            $('#studentManagementSection').show(); // Show the student management section
                            $('#initialSelectionContainer').hide(); // Hide the initial selection container
                            semesterSelect.append('<option value="">Select a semester</option>'); // Add default option
                            $.each(semesters, function (index, semester) {
                                semesterSelect.append('<option value="' + semester.SemesterID + '">' + semester.SemesterName + ' - ' + semester.Startdate + ' to ' + semester.Enddate + ' (' + semester.departmentName + ')</option>');
                            });
                        } else {
                            $('#studentManagementSection').hide(); // Hide the student management section
                            $('#initialSelectionContainer').show(); // Show the initial selection container
                            Swal.fire({
                                icon: 'info',
                                title: 'No Semesters Available',
                                text: 'There are no semesters available for the selected department and curriculum year.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: " + status + ": " + error);
                    }
                });
            } else {
                $('#studentManagementSection').hide(); // Hide the student management section if not both selected
                $('#courses').html('');
            }
        });
    });
</script>

</head>

<body>
    <?php
    include "../dist/security.php";

    // Ensure the session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if either admin_id or register_id is set in the session
    if (isset($_SESSION['admin_id']) || isset($_SESSION['register_id'])) {
        $AdminID = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
        $RegID = isset($_SESSION['register_id']) ? $_SESSION['register_id'] : null;

        // Build the query to fetch the user role
        $query = "SELECT s.description
              FROM staff AS s
              LEFT JOIN admin AS a ON s.staff_id = a.staffID
              LEFT JOIN registration AS r ON s.staff_id = r.staffID
              WHERE 1=1";

        // Add conditions only if the respective IDs are not null
        if ($AdminID !== null) {
            $query .= " AND a.admin_id = $AdminID";
        }
        if ($RegID !== null) {
            $query .= " AND r.register_id = $RegID";
        }

        $resultUser = mysqli_query($con, $query);

        if ($resultUser) {
            $row = mysqli_fetch_assoc($resultUser);

            if ($row) {
                $user_role = $row['description'];

                // Include the appropriate header based on user role
                if ($user_role == 'admin') {
                    include 'header1.php';
                } elseif ($user_role == 'registration') {
                    include 'header2.php';
                }
            } else {
                // Handle case when no matching rows are found
                echo "No matching user role found.";
            }
        } else {
            echo "Error executing query: " . mysqli_error($con);
        }
    } else {
        // Redirect to login page or show an error message
        header("Location: login.php");
        exit;
    }
    ?>

    <div id="initialSelectionContainer" class="container">
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
            <label for="Curriculum" class="form-label">Curriculum Year</label>
            <?php
            if (mysqli_num_rows($resultCurriculumYear)) {
                echo '<select class="form-select" name="curriculum" id="Curriculum" required>';
                echo "<option value=''>Select a Curriculum Year</option>";
                while ($row = mysqli_fetch_assoc($resultCurriculumYear)) {
                    $CurriculumYearID = htmlspecialchars($row['curriculum_year_id']);
                    $CurriculumYearName = htmlspecialchars($row['name']);
                    echo "<option value='$CurriculumYearID'>$CurriculumYearName</option>";
                }
                echo "</select>";
            } else {
                echo '<p>No Years found.</p>';
            }
            ?>
        </div>
    </div>

    <div id="studentManagementSection" class="container" style="display: none;">
        <form action="studentAction.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <h1>Student Management</h1>
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="number" id="student_id" name="student_id" placeholder="Enter Student ID" style="width: 300px;" required>
            </div>

            <div class="form-group">
                <label for="semester">Semester</label>
                <select id="semester" name="semester">
                    <option value="">Select a semester</option>
                    <?php
                    $sql = "SELECT SemesterID, SemesterName FROM semister";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row["SemesterID"] . '">' . $row["SemesterName"] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div id="courses"></div>
            <script>
                $(document).ready(function () {
                    $('#semester').on('change', function () {
                        var semesterID = $(this).val();
                        if (semesterID) {
                            $.ajax({
                                type: 'POST',
                                url: 'fetch_courses.php',
                                data: 'semesterID=' + semesterID,
                                success: function (html) {
                                    $('#courses').html(html);
                                }
                            });
                        } else {
                            $('#courses').html('');
                        }
                    });
                });
            </script>

            <div class="form-group">
                <label for="year_id">Academic Year</label>
                <?php
                include('connection.php');
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

            <div class="form-group button-group">
                <button type="submit" name="enroll_student">Enroll Student</button>
                <button type="submit" name="view_student">View Student</button>
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            var studentId = document.getElementById("student_id").value;
            if (!/^\d+$/.test(studentId)) {
                alert("Student ID must be an integer.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
