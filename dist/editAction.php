<?php
include "../dist/security.php";

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

$id = $_POST['id'];
$title = $_POST['txtName'];
$date = $_POST['txtDate'];
$source = $_POST['txtSource'];
$author = $_POST['txtAuthor'];
$article = $_POST['txtArticle'];
$category = $_POST['txtCategory'];

$query = "UPDATE articles SET title = '$title', date = '$date', source = '$source', author = '$author', article = '$article', photo_url='$upload_path',photo_name='$imgName', category = '$category' WHERE id = $id";
mysqli_query($con, $query);

header("Location: ../dist/ViewArticles.php");
?>
