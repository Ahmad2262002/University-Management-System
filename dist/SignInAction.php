<?php
include('connection.php');
$upload_url = 'uploads/admins'; 

$name = mysqli_real_escape_string($con, $_POST['name']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$password = $_POST['password']; // Hash the password
$department = mysqli_real_escape_string($con, $_POST['department']);
$description = 'admin';
$role_id = '1';
$YearID = $_POST['YearID']; 

$imgName = pathinfo($_FILES['Profile_photo']['name'], PATHINFO_FILENAME);
$extension = pathinfo($_FILES['Profile_photo']['name'], PATHINFO_EXTENSION);
$upload_path= $upload_url ."/" . $imgName.'.'. $extension ;

// Validate and move the uploaded file to the desired directory
if($_FILES['Profile_photo']['size'] > 500000) {
    echo "File is too large.";
} else {
    $fileType = strtolower(pathinfo($_FILES['Profile_photo']['name'],PATHINFO_EXTENSION));
    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif" ) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
    } else {
        if(move_uploaded_file($_FILES['Profile_photo']['tmp_name'], $upload_path)){
            echo "File uploaded successfully.";
        } else {
            echo "File upload failed.";
        }
    }
}

$now = date('Y-m-d H:i:s');

$stmt = $con->prepare("SELECT * FROM academicyear WHERE YearID = ? AND ? BETWEEN startdate AND enddate");
$stmt->bind_param("is", $YearID, $now);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt = $con->prepare("INSERT INTO staff(name, email, password, description, role_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $description, $role_id);
    if($stmt->execute()){
        echo "Record inserted successfully into staff table";

        $staffID = $con->insert_id;

        $stmt2 = $con->prepare("INSERT INTO admin(staffID, name, email, password, department, YearID) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("issssi", $staffID, $name, $email, $password, $department, $YearID);
        if($stmt2->execute()){
            echo "Record inserted successfully into admin table";

            $adminID = $con->insert_id;

            $stmt3 = $con->prepare("UPDATE admin SET photo_name = ?, photo_url = ? WHERE admin_id = ?");
            $stmt3->bind_param("ssi", $imgName, $upload_path, $adminID);
            if($stmt3->execute()){
                echo "Record updated successfully in admin table";
                header("location:logIn.php");
            }
            else{
                echo "ERROR: Could not execute query: " . $stmt3->error;
            }
        }
        else{
            echo "ERROR: Could not execute query: " . $stmt2->error;
        }
    }
    else{
        echo "ERROR: Could not execute query: " . $stmt->error;
    }
} else {
    echo "The YearID does not exist in the academicyear table or the current date and time is not within the academic year range";
}
?>
