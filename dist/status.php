<?php
include "../dist/security.php";
$id = $_GET['id'];
$status = $_GET['status'];
if($status == "U"){
    $query = "UPDATE articles SET status = 0 WHERE id = $id";
}
if($status == "P"){
    $query = "UPDATE articles SET status = 1 WHERE id = $id";
}
if($status == "d"){
    $query = "DELETE FROM articles WHERE id = $id";
}
mysqli_query($con,$query);
header("location:ViewArticles.php");