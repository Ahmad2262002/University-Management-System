<?php
include "../dist/connection.php";
include "../dist/security.php";

if (!isset($_SESSION['email'])) {
   header("Location: logIn.php"); 
   exit();
}

$user = $_SESSION['admin_id'];
$AdminID = $_SESSION['admin_id']; 

// Prepare and execute the SQL query
$query = "SELECT * FROM articles WHERE operator ='$user'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>View Articles</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        .navbar {
            background-color: #2c3e50;
            padding: 15px;
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .nav-link {
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 15px;
            margin: 0 5px;
            border-radius: 5px;
            font-family: 'Merriweather', serif;
        }

        .nav-link:hover {
            background-color: #34495e;
        }

        h2 {
            text-align: center;
            color: #34495e;
            font-family: 'Merriweather', serif;
            margin-bottom: 30px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #34495e;
            color: #ecf0f1;
        }

        td a {
            text-decoration: none;
            color: #34495e;
        }

        td a:hover {
            text-decoration: underline;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

<nav class="navbar">
    <a class="nav-link" href="AdminCP.php">Back</a>
    <a class="nav-link" href="AddArticle.php">Add Articles</a>
    <a class="nav-link" href="AddCategories.php">Add Categories</a>
</nav>

<div class="container">
    <h2>View Articles</h2>
    <div class="center">
        <table>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Date</th>
                <th>Source</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td><a href='articlDetails.php?id=" . $row['id'] . "'>" . $row["title"] . "</a></td>
                        <td>" . $row['category'] . "</td>
                        <td>" . $row['date'] . "</td>
                        <td>" . $row['source'] . "</td>
                        <td>";
                if ($row['status'] == 1) {
                    echo "<a href='status.php?id=" . $row['id'] . "&status=U'>UnPublish</a>";
                } else {
                    echo "<a href='status.php?id=" . $row['id'] . "&status=P'>Publish</a>";
                }
                echo "</td>
                        <td><a href='status.php?id=" . $row['id'] . "&status=d'>Delete</a>
                         <a href='editArticle.php?id=" . $row['id'] . "'>Edit</a></td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>

<?php
mysqli_close($con);
?>
