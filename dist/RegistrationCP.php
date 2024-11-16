<?php
session_start();
$RegID = $_SESSION['register_id'];
$user = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IUL</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="../css/responsive.css" />
  <link rel="stylesheet" href="../css/colors.css" />
  <link rel="stylesheet" href="../css/bootstrap-select.css" />
  <link rel="stylesheet" href="../css/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../css/custom.css" />

  <style>
    body {
      background-color: #f8f9fa;
      color: #333;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    .navbar {
      background-color: #003366;
      padding: 15px;
    }

    .navbar a {
      color: white;
      padding: 10px 20px;
      border-radius: 25px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .navbar a:hover {
      background-color: #00509e;
    }

    .container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 50px;
    }

    .card {
      border: none;
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin: 15px;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-img-top {
      border-bottom: 1px solid #e0e0e0;
    }

    .card-body {
      padding: 15px;
      text-align: center;
    }

    .card-title {
      margin-bottom: 10px;
      font-size: 1.25rem;
      font-weight: bold;
      color: #003366;
    }

    .text-decoration-none {
      color: inherit;
      text-decoration: none;
    }

    .text-decoration-none:hover {
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="navbar">
    <a href="logout.php" class="fa fa-sign-out"> Logout</a>
    <a href="regProfile.php" class="fa fa-credit-card"> Profile</a>

  </div>

  <div class="container">
    <div class="col-md-3">
      <a href="../dist/Student.php" class="text-decoration-none">
        <div class="card">
          <img src="../Pictures/Online learning-amico.png" class="card-img-top" alt="Teacher">
          <div class="card-body">
            <h5 class="card-title">Enrollment</h5>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a href="../dist/OpenFile.php" class="text-decoration-none">
        <div class="card">
          <img src="../Pictures/college students-rafiki.png" class="card-img-top" alt="Registration">
          <div class="card-body">
            <h5 class="card-title">New Student</h5>
          </div>
        </div>
      </a>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
