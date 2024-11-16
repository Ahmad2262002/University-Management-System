<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
<body class="bg-light">
<nav class="navbar">
    <div class="container">
        <a class="nav-link" href="Staff.php">Back</a>
    </div>
</nav>
    <div class="container mt-5">
        <h1 class="mb-4">Registrations</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Year Start Date</th>
                        <th>Year End Date</th>
                        <th>Staff ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="registrationTable">
                    <?php
                    include '../dist/connection.php';
                    include '../dist/security.php';

                    $sql = "SELECT r.register_id, r.name, r.email, r.phone, d.dept_name, a.startdate, a.enddate, r.staffID
                            FROM registration r
                            JOIN departments d ON r.department_id = d.dept_id
                            JOIN academicyear a ON r.YearID = a.YearID";
                    $result = $con->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['register_id']}</td>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['email']}</td>";
                            echo "<td>{$row['phone']}</td>";
                            echo "<td>{$row['dept_name']}</td>";
                            echo "<td>{$row['startdate']}</td>";
                            echo "<td>{$row['enddate']}</td>";
                            echo "<td>{$row['staffID']}</td>";
                            echo "<td><button class='btn btn-danger btn-sm' onclick='deleteRegistration({$row['register_id']})'>Delete</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No registrations found</td></tr>";
                    }

                    $con->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function deleteRegistration(id) {
            if (confirm("Are you sure you want to delete this registration?")) {
                fetch('delete_registration.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ register_id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registration deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting registration');
                    }
                });
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
