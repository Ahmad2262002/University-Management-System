<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Management</title>
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
        <h1 class="mb-4">Teachers</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="teacherTable">
                    <?php
                    include '../dist/connection.php';
                    include '../dist/security.php';

                    // Fetch teachers with department name
                    $sql = "SELECT t.teacherID, t.name, t.email, t.phone_number, t.photo_url, d.dept_name 
                            FROM teachers t
                            JOIN departments d ON t.department = d.dept_id";
                    $result = $con->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['teacherID']}</td>";
                            echo "<td><img src='{$row['photo_url']}' alt='Photo' style='width: 50px; height: 50px;'></td>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['email']}</td>";
                            echo "<td>{$row['phone_number']}</td>";
                            echo "<td>{$row['dept_name']}</td>";
                            echo "<td><button class='btn btn-danger btn-sm' onclick='deleteTeacher({$row['teacherID']})'>Delete</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No teachers found</td></tr>";
                    }

                    $con->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function deleteTeacher(id) {
            if (confirm("Are you sure you want to delete this teacher?")) {
                fetch('delete_teacher.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ teacherID: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Teacher deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting teacher');
                    }
                });
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
