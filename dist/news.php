<?php
include "connection.php";

$query = "SELECT * FROM news";
$result = mysqli_query($con, $query);

if ($result === false) {
    echo "Error: " . mysqli_error($con);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />

  <title>News</title>
  <link rel="stylesheet" href="./output.css">

  <!-- Latest compiled JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">News</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
          <?php
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_row($result)) {
              echo "<li class='nav-item'>
            <a class='nav-link' href='newsDetails.php?name=".$row[1]."'>".$row[1]."</a>
          </li>";
            }
          } else {
            echo "<li class='nav-item'><span class='nav-link'>No news available</span></li>";
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-3">
    <?php
    $latestArticleQuery = "SELECT * FROM news WHERE status = 1 ORDER BY NewsID DESC";
    $latestArticleResult = mysqli_query($con, $latestArticleQuery);

    if ($latestArticleResult === false) {
        echo "Error: " . mysqli_error($con);
        exit();
    }

    if (mysqli_num_rows($latestArticleResult) > 0) {
    ?>
      <div class="row">
        <?php
        while ($latestArticle = mysqli_fetch_assoc($latestArticleResult)) {
        ?>
          <div class="col-lg-6">
            <!-- Big Post -->
            <div class="card mb-4">
              <img src="./backend/news_images/<?php echo $latestArticle['image']; ?>" class="card-img-top" alt="Big Post Image">
              <div class="card-body">
                <h5 class="card-title"><?php echo $latestArticle['title']; ?></h5>
                <p class="card-text"><?php echo $latestArticle['article']; ?></p>
                <a href="newsDetails.php?id=<?php echo $latestArticle['NewsID']; ?>" class="btn btn-primary">Read More</a>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    <?php
    } else {
        echo "<p>No latest articles available.</p>";
    }
    ?>
  </div>
</body>

</html>
