<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connection.php';

    $ReqID = $_POST["ReqID"];

    $sql = "DELETE FROM request WHERE ReqID='$ReqID'";

    if ($con->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }

    $con->close();
    header("Location: Request.php");
    exit;
}
?>
