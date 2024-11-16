<?php 
include "./security.php";
$sql = "SELECT * FROM categories";
$resultCategories = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
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

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #34495e;
            font-family: 'Merriweather', serif;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #34495e;
            border-color: #34495e;
        }

        .btn-primary:hover {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }

        .btn-secondary {
            background-color: #7f8c8d;
            border-color: #7f8c8d;
        }

        .btn-secondary:hover {
            background-color: #95a5a6;
            border-color: #95a5a6;
        }

        .d-grid .btn {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a class="nav-link" href="AdminCP.php">Admin Control Panel</a>
</nav>

<div class="container">
    <h1>Add New Article</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" action="AddArticleAction.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="txtName" class="form-control" id="title" placeholder="Enter the title">
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" name="txtDate" id="date" placeholder="Select the date">
                </div>
                <div class="mb-3">
                    <label for="source" class="form-label">Source</label>
                    <input type="text" class="form-control" id="source" name="txtSource" placeholder="Enter the source">
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" class="form-control" id="author" name="txtAuthor" placeholder="Enter the author">
                </div>
                <div class="mb-3">
                    <label for="article" class="form-label">Article</label>
                    <textarea class="form-control" id="article" rows="5" name="txtArticle" placeholder="Enter the article"></textarea>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Profile Photo</label>
                    <input type="file" id="photo" name="Profile_photo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <?php
                        if(mysqli_num_rows($resultCategories)){
                            echo '<select class="form-select" name="txtCategory" id="category">';
                            echo "<option value=''>Select a category</option>";
                            while($row = mysqli_fetch_assoc($resultCategories)){
                                $categoryName = $row['category_name'];
                                echo "<option value='$categoryName'>$categoryName</option>";
                            }
                            echo "</select>";
                        }
                    ?>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <a href="viewArticles.php" class="btn btn-primary">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
