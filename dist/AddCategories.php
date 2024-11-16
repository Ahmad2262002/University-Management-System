<?php 
include "../dist/security.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Awesome News Categories</title>
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
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

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #34495e;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #34495e;
            font-family: 'Merriweather', serif;
            margin-bottom: 30px;
        }

        h2 {
            color: #34495e;
            font-family: 'Merriweather', serif;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"], button[type="button"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #34495e;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Roboto', sans-serif;
        }

        button[type="submit"]:hover, button[type="button"]:hover {
            background-color: #2c3e50;
        }

        button[type="button"] {
            margin-top: 10px;
            font-size: 14px;
            padding: 8px;
        }
    </style>
</head>
<body>
    <h1>News Categories</h1>

    <nav class="navbar">
        <div class="container">
            <a class="nav-link" href="viewArticles.php">Back</a>
            <a class="nav-link" href="AddArticle.php">Add Article</a>
            <a class="nav-link" href="ViewArticles.php">Manage Article</a>
        </div>
    </nav>
    
    <div class="container">
        <h2>Add Category</h2>
        <form action="add_category_process.php" method="POST">
            <label for="category_name" class="form-label">Category Name:</label>
            <input type="text" name="category_name" required>
            <button type="submit">Add Category</button>
        </form>
    </div>
</body>
</html>
