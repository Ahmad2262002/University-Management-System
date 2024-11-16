<?php
include('connection.php');
include("security.php");
$upload_url = 'uploads/lectures';

// Validate and move the uploaded file to the desired directory
if(isset($_FILES['Profile_photo'])){
    $imgName = pathinfo($_FILES['Profile_photo']['name'], PATHINFO_FILENAME);
    $extension = pathinfo($_FILES['Profile_photo']['name'], PATHINFO_EXTENSION);
    $upload_path= $upload_url . "/" . $imgName . '.' . $extension;

    if($_FILES['Profile_photo']['size'] > 500000) {
        echo "File is too large.";
        exit;
    } else {
        $fileType = strtolower($extension);
        if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif" ) {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            exit;
        } else {
            if(!move_uploaded_file($_FILES['Profile_photo']['tmp_name'], $upload_path)){
                echo "File upload failed.";
                exit;
            }
        }
    }
}

$title = $_POST['txtName'];
$date = $_POST['txtDate'];
$source = $_POST['txtSource'];
$author = $_POST['txtAuthor'];
$article = $_POST['txtArticle'];
$category = $_POST['txtCategory'];
$operator = $_SESSION['admin_id'];

// Check if form is submitted
if(isset($_POST['submit'])){
    // Insert values into the database
    $query = "INSERT INTO articles (title, date, source, author, article, photo_url, photo_name, category, operator) 
              VALUES ('$title', '$date', '$source', '$author', '$article', '$upload_path', '$imgName', '$category', '$operator')";
    if ($con->query($query)) {
        header("Location: viewArticles.php");
    } else {
        echo "Error: " . $query . "<br>" . $con->error;
    }
}
?>
