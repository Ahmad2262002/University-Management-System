<?php
include "connection.php";
$query = "SELECT * FROM categories";
$result = mysqli_query($con, $query);
$name = $_GET['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />


    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
        <div class="container-fluid">
        <a class="navbar-brand" href="#" onclick="window.history.back();">News</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <?php
                    if (mysqli_num_rows($result)) {
                        while ($row = mysqli_fetch_row($result)) {
                            echo "<li class=nav-item>
            <a class=nav-link href=categoryDetails.php?name=" . $row[1] . ">" . $row[1] . "</a>
          </li>";
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-3">
        <?php
        $latestArticleQuery = "SELECT * FROM articles WHERE status = 1 AND category ='$name'  ORDER BY id DESC";
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
                        <img src="<?php echo $latestArticle['photo_url']; ?>" class="card-img-top" alt="Big Post Image">

                            <div class="card-body">
                                <h5 class="card-title"><?php echo $latestArticle['title']; ?></h5>
                                <p class="card-text"><?php echo $latestArticle['article']; ?></p>
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