<?php
include "../dist/connection.php";
$query = "SELECT * FROM categories";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News</title>
  <!-- Latest compiled and minified CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />


  <!-- Latest compiled JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .custom-heading {
      color: #9F7944;
      /* Darker gold color */
      font-family: 'Times New Roman', Times, serif;
      text-shadow: 1px 1px 2px #000;
      font-size: 3rem;
      /* Adjusted to be significantly larger */
    }

    .text-container {
      height: 100%;
      display: flex;
      align-items: center;
    }

    .img-fluid {
      max-height: 100%;
    }

    .col-md-6 {
      display: flex;
      align-items: center;
    }

    body {
      background-color: darkgrey;
      color: #f5f5f5;
    }

    .card {
      background-color: #444;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    /* Override anchor tag styles within card body */
    /* Add these styles */
    .card-body {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .card-body a {
      color: inherit;
      /* Make the link text color same as normal text */
      text-decoration: none;
      /* Remove underline from link text */
    }

    .card-body a:hover {
      color: inherit;
      /* Keep the link text color same on hover */
      text-decoration: none;
      /* Remove underline from link text on hover */
    }

    .carousel-caption {
      background-color: rgba(0, 0, 0, 0.7);
      /* Black background with 70% opacity */
      border-radius: 10px;
      /* Rounded corners */
    }

    .carousel-inner {
      height: 100%;
      /* Adjust this value to change the height of the carousel */
      width: 100%;
      /* Full width */
    }

    .carousel-inner img,
    .carousel-inner video {
      object-fit: cover;
      /* This will make sure your images and videos cover the entire space of the carousel */
    }

    .card1 {
      width: 12rem;
      /* Adjust this value to change the width of the cards */
      height: 10rem;
      /* Adjust this value to change the height of the cards */
      margin-bottom: 2rem;
    }

    .card1-img-top {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .title {
      text-align: center;
      font-size: 2rem;
      margin-bottom: 1rem;
      color: #333;
      font-family: 'Times New Roman', Times, serif;
      /* This will give your text a classic, sophisticated look */
      font-weight: bold;
      /* This will make your text stand out more */
    }

    /* Style for the "Go Back" button */
    button {
            background-color: #333;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #A9A9A9;
        }
  </style>
</head>

<body style="background-color: #A9A9A9;">
 
  <div class="container mt-3">
    <?php
$latestArticleQuery = "SELECT * FROM articles WHERE status = 1 AND category = 'Academic ' ORDER BY id DESC";
$latestArticleResult = mysqli_query($con, $latestArticleQuery);

    if (mysqli_num_rows($latestArticleResult) > 0) {
      ?>
      <div class="row">
        <?php
        while ($latestArticle = mysqli_fetch_assoc($latestArticleResult)) {
          ?>
          <div class="col-lg-6">
            <!-- Big Post -->
            <div class="card mb-4">
            <img src="<?php echo $latestArticle['photo_url']; ?>" class="card-img-top" alt="Big Post Image" width="300" height="500">

             
              <div class="card-body">
                <h5 class="card-title"><?php echo $latestArticle['title']; ?></h5>
                <div class="card-text" style="width: 300px; height: 100px;">
    <?php echo $latestArticle['article']; ?>
</div>
                <a href="artDetails.php?id=<?php echo $latestArticle['id']; ?>" class="btn btn-primary">Read More</a>
              </div>
            </div>
          </div>
          <?php
        }
        ?>
      </div>
      <?php
    }
    ?>
  </div>
</body>

</html>