<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light background */
            color: #333; /* Dark text */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007aff; /* iOS 17 blue */
            color: white;
            font-weight: bold;
        }
        .card-body {
            padding: 20px;
        }
        .list-group-item {
            border: none;
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
    </style>
</head>
<body>
<nav class="navbar">
    <div class="container">
    <a class="nav-link" href="#" onclick="window.history.back();">Back</a>
    </div>
</nav>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                Help Desk Information
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>IT Number:</strong> Samer Tofayle, 70851849
                    </li>
                    <li class="list-group-item">
                        <strong>Internet Details:</strong> Iul Net, Password: 13941506 Iul<br>
                        <strong>Registration Employees:</strong>
                    </li>
                    <?php
                    include 'connection.php';

                    // Fetching data from the register table and departments table
                    $sql = "SELECT r.phone, s.name, d.dept_name 
                            FROM registration r 
                            JOIN departments d ON r.department_id = d.dept_id 
                            JOIN staff s ON r.staffID = s.staff_id";
                    $result = $con->query($sql);

                    if ($result === false) {
                        echo "<li class='list-group-item'>Error: " . $con->error . "</li>";
                    } else {
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<li class='list-group-item'>
                                        <strong>Name:</strong> " . $row["name"]. "<br>
                                        <strong>Phone:</strong> " . $row["phone"]. "<br>
                                        <strong>Department:</strong> " . $row["dept_name"]. "
                                      </li>";
                            }
                        } else {
                            echo "<li class='list-group-item'>No records found</li>";
                        }
                    }

                    $con->close();
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
