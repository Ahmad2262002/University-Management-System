<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />

  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-image: url('/USM_CODE/Pictures/login-151.jpg');
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      color: #333;
    }

    .wrapper {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 320px;
      text-align: center;
    }

    .form-title {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #003366;
    }

    .form-field {
      margin-bottom: 20px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    .btn-primary {
      background-color: #003366;
      color: #fff;
      border: none;
      padding: 12px;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
      font-weight: bold;
    }

    .btn-primary:hover {
      background-color: #00509e;
    }

    .link {
      text-align: center;
      margin-top: 10px;
      color: #003366;
    }

    .link a {
      color: #003366;
      text-decoration: none;
    }

    .link a:hover {
      text-decoration: underline;
    }

    img {
      width: 180px;
      height: 220px;
      object-fit: cover;
      object-position: center;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <!-- Include a header or navigation bar -->
    <?php require "nav.php"; ?>

    <!-- Replace with your Harvard-like logo -->
    <img src="../Pictures/IUL-removebg-preview (1).png" alt="Company Logo">

    <div class="form-title">Login Details</div>

    <?php
    if (isset($_GET['error']) && $_GET['error'] == '1') {
        echo '<div class="alert alert-danger" role="alert">Invalid credentials! Please check your email and password.</div>';
    }
    ?>

    <form action="loginAction.php" method="post" onsubmit="startTimer()">
      <div class="form-field">
        <input type="text" id="email" name="email" placeholder="Email Address" required>
      </div>
      <div class="form-field">
        <input type="password" id="password" name="password" placeholder="Password" required>
      </div>
      <div class="form-field">
        <button class="btn btn-primary" type="submit">Login</button>
      </div>
    </form>
  </div>

  <!-- JavaScript for login timer -->
  <script>
    var timerElement = document.getElementById('timer');
    var timerId;

    function startTimer() {
        localStorage.setItem('loginTime', Date.now());
        timerId = setInterval(function () {
            var loginTime = localStorage.getItem('loginTime');
            var timeSinceLogin = (Date.now() - loginTime) / 1000 / 60; // convert milliseconds to minutes
            timerElement.textContent = timeSinceLogin.toFixed(2) + " minutes";
        }, 1000);
    }
  </script>
</body>

</html>
