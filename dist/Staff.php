<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png">

    <title>IUL</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add a dark background color */
        body {
            background-color: #1c1c1e;
            color: #f5f5f5; /* Light text color */
            padding-top: 20px; /* Space from top */
            font-family: Arial, sans-serif; /* Font family */
        }

        /* Navbar style */
        .navbar {
            background-color: #1c1c1e;
            padding: 10px;
            margin-bottom: 20px; /* Space below navbar */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Subtle shadow */
            display: flex; /* Flex container */
            justify-content: center; /* Center content horizontally */
        }

        .navbar a {
            background-color: #444; /* Dark background for links */
            color: white; /* Text color */
            padding: 10px 20px;
            border: none;
            border-radius: 15px; /* Rounded corners */
            text-decoration: none; /* Remove underline */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Button shadow */
            transition: background-color 0.3s, transform 0.2s; /* Smooth transition */
        }

        .navbar a:hover {
            background-color: #333; /* Darken background on hover */
        }

        /* Card styling */
        .card {
            background-color: #333; /* Dark card background */
            box-shadow: 0 8px 16px rgba(128, 0, 128, 0.7); /* Purple shadow */
            transition: transform 0.2s, box-shadow 0.2s; /* Smooth transitions */
        }

        .card:hover {
            transform: scale(1.05); /* Scale up on hover */
            box-shadow: 0 8px 20px rgba(128, 0, 128, 0.7); /* Intensify purple shadow on hover */
        }

        /* Card image and body styles */
        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            text-align: center; /* Center text in card body */
        }

        .card-title {
            margin-top: 10px; /* Space above card title */
            font-size: 1.2rem; /* Larger font size */
        }

        /* Flexbox container for cards */
        .container {
            display: flex;
            justify-content: center;
            gap: 20px; /* Space between cards */
            flex-wrap: wrap;
        }

        .col-md-3 {
            max-width: 300px; /* Limit card width */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="AdminCP.php" class="mx-2">Back</a>
        <a href="Teachers.php" class="mx-2">View Teachers</a>
        <a href="Registrations.php" class="mx-2">View Registrations</a>
    </nav>

    <!-- Card container -->
    <div class="container">
        <!-- Teacher card -->
        <div class="col-md-3">
            <a href="../dist/TeacherSign.php" class="text-decoration-none">
                <div class="card">
                    <img src="../Pictures/Online learning-amico.png" class="card-img-top" alt="Teacher">
                    <div class="card-body">
                        <h5 class="card-title">Teachers</h5>
                    </div>
                </div>
            </a>
        </div>

        <!-- Registration card -->
        <div class="col-md-3">
            <a href="../dist/RegistrationSignIn.php" class="text-decoration-none">
                <div class="card">
                    <img src="../Pictures/college students-rafiki.png" class="card-img-top" alt="Registration">
                    <div class="card-body">
                        <h5 class="card-title">Registration</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
