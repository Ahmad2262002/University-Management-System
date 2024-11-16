<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      color: #343a40;
      font-family: 'Arial', sans-serif;
    }

    .container {
      margin-top: 50px;
    }

    .table thead th {
      background-color: #00274d;
      color: white;
    }

    .table tbody tr {
      background-color: #ffffff;
    }

    .table tbody tr:hover {
      background-color: #e0e0e0;
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
      <a class="nav-link" href="AdminCP.php">Back</a>
    </div>
  </nav>
  <div class="container">
    <h1 class="text-center mb-4">Student Requests</h1>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Request ID</th>
            <th>Student ID</th>
            <th>Major</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          include 'connection.php';
          $sql = "SELECT ReqID, student_id, major, phone_number, email, description FROM request";
          $result = $con->query($sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
              <tr>
                <td><?php echo $row["ReqID"]; ?></td>
                <td><?php echo $row["student_id"]; ?></td>
                <td><?php echo $row["major"]; ?></td>
                <td><?php echo $row["phone_number"]; ?></td>
                <td><?php echo $row["email"]; ?></td>
                <td><?php echo $row["description"]; ?></td>
                <td>
                  <form action="deleteRequest.php" method="post" style="display:inline;">
                    <input type="hidden" name="ReqID" value="<?php echo $row['ReqID']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                  </form>
                </td>
              </tr>
            <?php }
          } else { ?>
            <tr>
              <td colspan="7" class="text-center">No requests found</td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
