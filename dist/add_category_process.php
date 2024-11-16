<?php
include("connection.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_name = $_POST["category_name"];

    // Insert the category into the database using prepared statement
    $sql = "INSERT INTO categories (category_name) VALUES (?)"; // Use "?" as a placeholder
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $category_name); // "s" represents a string, adjust if necessary

        if ($stmt->execute()) {
            echo "Category added successfully!";
        } else {
            echo "Error: " . $stmt->$error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $con->$error;
    }
}

$con->close();
?>
