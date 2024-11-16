<?php
session_start();
include "connection.php";

$upload_url = 'uploads';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $major = $_POST['major'];
    $password = $_POST['password'];
    $yearId = $_POST['year_id'];
    $Phone = $_POST['phone'];

    if (isset($_POST['add-student'])) {
        $imgName = pathinfo($_FILES['profile-image']['name'], PATHINFO_FILENAME);
        $extension = pathinfo($_FILES['profile-image']['name'], PATHINFO_EXTENSION);
        $upload_path = $upload_url . "/" . $imgName . '.' . $extension;

        if ($_FILES['profile-image']['size'] > 500000) {
            echo "File is too large.";
        } else {
            $fileType = strtolower(pathinfo($_FILES['profile-image']['name'], PATHINFO_EXTENSION));
            if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") {
                echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                if (move_uploaded_file($_FILES['profile-image']['tmp_name'], $upload_path)) {
                    echo "File uploaded successfully.";

                    $sql = "INSERT INTO student (name, email, phone_number, major, password, YearID, photo_url) VALUES (?, ?,?, ?, ?, ?, ?, ?)";
                    $stmt = $con->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("ssssiss", $name, $email, $Phone, $major, $password, $yearId, $upload_path);
                        $stmt->execute();
                        echo "Student added successfully!";
                        header("location: Student.php");

                        $studentID = $con->insert_id;

                        if (isset($_POST['courses'])) {
                            foreach ($_POST['courses'] as $courseId) {
                                $sql = "INSERT INTO enrollment (students_id, CourseID, YearID) VALUES (?, ?, ?)";
                                $stmt = $con->prepare($sql);
                                if ($stmt) {
                                    $stmt->bind_param("iii", $studentID, $courseId, $yearId);
                                    $stmt->execute();
                                } else {
                                    echo "Error preparing statement: " . $con->error;
                                }
                            }
                        }
                    } else {
                        echo "Error preparing statement: " . $con->error;
                    }
                } else {
                    echo "File upload failed.";
                }
            }
        }
    } elseif (isset($_POST['delete-student']) || isset($_POST['update-student']) || isset($_POST['view-student'])) {
        $studentId = $_POST['student-id'];

        if (isset($_POST['delete-student'])) {
            $sql = "DELETE FROM enrollment WHERE students_id = ?";
            $stmt = $con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $studentId);
                $stmt->execute();
            }

            $sql = "DELETE FROM student WHERE students_id = ?";
            $stmt = $con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $studentId);
                $stmt->execute();
            }
        } elseif (isset($_POST['update-student'])) {
            if (isset($_FILES['profile-image']) && $_FILES['profile-image']['size'] > 0) {
                $imgName = pathinfo($_FILES['profile-image']['name'], PATHINFO_FILENAME);
                $extension = pathinfo($_FILES['profile-image']['name'], PATHINFO_EXTENSION);
                $upload_path = $upload_url . "/" . $imgName . '.' . $extension;

                if ($_FILES['profile-image']['size'] > 500000) {
                    echo "File is too large.";
                } else {
                    $fileType = strtolower(pathinfo($_FILES['profile-image']['name'], PATHINFO_EXTENSION));
                    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") {
                        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
                    } else {
                        if (move_uploaded_file($_FILES['profile-image']['tmp_name'], $upload_path)) {
                            echo "File uploaded successfully.";

                            $sql = "UPDATE student SET name = ?, email = ?, phone_number=?,  major = ?, password = ?, YearID = ?, photo_url = ? WHERE students_id = ?";
                            $stmt = $con->prepare($sql);
                            if ($stmt) {
                                $stmt->bind_param("ssissisi", $name, $email, $Phone, $major, $password, $yearId, $upload_path, $studentId);
                                $stmt->execute();
                                echo "Student updated successfully!";
                                header("Location:Student.php");
                            } else {
                                echo "Error preparing statement: " . $con->error;
                            }
                        } else {
                            echo "File upload failed.";
                        }
                    }
                }
            } else {
                $sql = "UPDATE student SET name = ?, email = ?, phone_number=?, major = ?, password = ?, YearID = ? WHERE students_id = ?";
                $stmt = $con->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ssisssi", $name, $email, $Phone, $major, $password, $yearId, $studentId);
                    $stmt->execute();
                    echo "Student updated successfully!";
                } else {
                    echo "Error preparing statement: " . $con->error;
                }
            }
        } elseif (isset($_POST['view-student'])) {
            $sql = "SELECT * FROM student WHERE students_id = ?";
            $stmt = $con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $studentId);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    // Start the session (if not already started)
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    // Store the student data in the session
                    $_SESSION['students_id'] = $studentId;

                    // Redirect back to the form
                    header("Location: viewStudent.php");
                    exit;
                } else {
                    echo "Error executing statement: " . $stmt->error;
                }
            } else {
                echo "Error preparing statement: " . $con->error;
            }
        }
    }
}
?>
