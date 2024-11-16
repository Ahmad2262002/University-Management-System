<?php
include ('connection.php');

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action == 'add') {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $year_id = isset($_POST['year_id']) ? $_POST['year_id'] : '';
    $dept_id = isset($_POST['major']) ? $_POST['major'] : '';

    // Check if email already exists
    $emailCheckSql = "SELECT email FROM student WHERE email = ?";
    $stmt = $con->prepare($emailCheckSql);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo "<script>alert('This email is already in use. Please use a different email.'); window.location.href='OpenFile.php';</script>";
            exit();
        }
    } else {
        echo "Error preparing email check query: " . $con->error;
        exit();
    }

    // Proceed with file upload and insert if email is unique
    $upload_url = 'uploads/students';
    $imgName = pathinfo($_FILES['photo']['name'], PATHINFO_FILENAME);
    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $upload_path = $upload_url . "/" . $imgName . '.' . $extension;

    if ($_FILES['photo']['size'] > 500000) {
        echo "File is too large.";
    } else {
        $fileType = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                echo "File uploaded successfully.";

                $sql = "INSERT INTO student (name, email, phone_number, photo_url, photo_name, password, YearID, major) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $con->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ssssssss", $name, $email, $phone_number, $upload_path, $imgName, $password, $year_id, $dept_id);
                    if ($stmt->execute()) {
                        header("Location: OpenFile.php");
                    } else {
                        echo "Error executing query: " . $stmt->error;
                    }
                } else {
                    echo "Error preparing query: " . $con->error;
                }
            } else {
                echo "Error moving uploaded file.";
            }
        }
    }
} elseif ($action == 'update') {
    $student_id = isset($_POST['students_id']) ? $_POST['students_id'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
    $year_id = isset($_POST['year_id']) ? $_POST['year_id'] : '';
    $dept_id = isset($_POST['dept_id']) ? $_POST['dept_id'] : '';

    if ($_FILES['photo']['name']) {
        $upload_url = 'uploads';
        $imgName = pathinfo($_FILES['photo']['name'], PATHINFO_FILENAME);
        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $upload_path = $upload_url . "/" . $imgName . '.' . $extension;

        move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path);
    } else {
        $upload_path = isset($_POST['photo_url']) ? $_POST['photo_url'] : '';
        $imgName = isset($_POST['photo_name']) ? $_POST['photo_name'] : '';
    }

    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        $sql = "UPDATE student SET name=?, email=?, phone_number=?, photo_url=?, photo_name=?, password=?, yearID=?, major=? WHERE students_id=?";
    } else {
        $sql = "UPDATE student SET name=?, email=?, phone_number=?, photo_url=?, photo_name=?, YearID=?, major=? WHERE students_id=?";
    }

    $stmt = $con->prepare($sql);
    if ($stmt) {
        if (isset($_POST['password'])) {
            $stmt->bind_param("ssssssssi", $name, $email, $phone_number, $upload_path, $imgName, $password, $year_id, $dept_id, $student_id);
        } else {
            $stmt->bind_param("sssssssi", $name, $email, $phone_number, $upload_path, $imgName, $year_id, $dept_id, $student_id);
        }
        if ($stmt->execute()) {
            header("Location: OpenFile.php");
        } else {
            echo "Error executing query: " . $stmt->error;
        }
    } else {
        echo "Error preparing query: " . $con->error;
    }
} elseif ($action == 'delete') {
    $student_id = isset($_POST['students_id']) ? $_POST['students_id'] : '';
    $sql = "DELETE FROM student WHERE students_id=?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $student_id);
        if ($stmt->execute()) {
            header("Location: OpenFile.php");
        } else {
            echo "Error executing query: " . $stmt->error;
        }
    } else {
        echo "Error preparing query: " . $con->error;
    }
}

$con->close();
?>
