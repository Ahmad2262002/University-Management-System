<!DOCTYPE html>
<html>

<head>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .menu {
            background-color: white;
            color: #fff;
            padding: 15px;
        }

        .menu ul {
            list-style: none;
            padding: 0;
        }

        .menu ul li {
            display: inline;
            margin-right: 10px;
        }

        .menu ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px; /* Add padding */
            border-radius: 5px; /* Add rounded corners */
        }

        .menu ul li a:hover {
            color: #ddd;
            background-color: #007BFF;
        }
    </style>
</head>

<body>
    <nav>
        <div class="menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <!-- Add more navigation links here -->
            </ul>
        </div>
    </nav>

    <script src="script.js"></script>
    <script>
    // Define a function to handle the "back" button click
    function goBack() {
        window.history.back();
    }
    </script>
</body>

</html>
