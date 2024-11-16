<?php 
include "./security.php";
$sql = "SELECT * FROM categories";
$resultCategories = mysqli_query($con,$sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />

    <title>Add Article</title>
            <!-- Latest compiled and minified CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Latest compiled JavaScript -->
    <script
      defer
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    ></script>
</head>
<body>
  <div class="container">
    <h1 class="text-center">Add new Article</h1>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="post" action="AddArticleAction.php">
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
            <label for="category" class="form-label">Category</label>
            <?php
                if(mysqli_num_rows($resultCategories)){
                    echo '<select class="form-select" name="txtCategory" id="category">';
                    echo "<option value=>Select a category</option>";
                    while($row = mysqli_fetch_assoc($resultCategories)){
                        $catgoryname = $row['category_name'];
                        echo "<option value=".$catgoryname.">".$catgoryname."</option>";
                    }
                    echo "</select>";
                }
            ?>
          </div>
          <div class="d-grid gap-2">
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
