<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <style>
    /* Basic styling */
    body {
      font-family: Arial, sans-serif;
      background-image: url('/USM_CODE/Pictures/login-151.jpg');
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .wrapper {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      width: 300px;
      display: flex;
      flex-direction: column;
      align-items: center;
      /* Center horizontally */
    }

    .form-title {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    .form-field {
      margin-bottom: 15px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    input[type="submit"] {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 3px;
      cursor: pointer;
      width: 100%;
    }

    input[type="submit"]:hover {
      background-color: #0a0e14;
    }

    .link {
      text-align: center;
    }

    .link a {
      color: #007bff;
      text-decoration: none;
    }

    .button {
      background-color: #007bff;
      color: black;
      border: 2px solid #e7e7e7;
      width: 100%;
      border-radius: 3px;
      padding: 10px;
    }

    img {
      width: 170px;
      height: 220px;
      object-fit: cover;
      object-position: center;
      margin-bottom: 20px;
      /* Adjust margin for spacing */
    }  </style>
</head>
<body>
<?php require "nav.php";?>

  <div class="wrapper">
    <img src="/USM_CODE/Pictures/LOGO.jpg" alt="Company Logo">
    <div class="form-title">Login Details</div>
    <form action="loginAction.php" method="post">
      <div class="form-field">
        <input type="text" id="email" name="email" placeholder="Email Address" required>
      </div>
      <div class="form-field">
        <input type="password" id="password" name="password" placeholder="Password" required>
      </div>
      <div class="form-field">
        <button class="button" type="submit">Login</button>
      </div>
      <div class="link">Not a member? <a href="Signup.html" title="Sign up now">Signup now</a>
    </div>
    </form>
  </div>
</body>

</html>
