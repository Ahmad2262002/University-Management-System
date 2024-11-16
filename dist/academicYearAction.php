<?php
session_start();
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add-year'])) {
        $startDate = $_POST['start-date'];
        $endDate = $_POST['end-date'];

        if ($startDate && $endDate) {
            $sql = "INSERT INTO academicyear (startdate, enddate) VALUES (?, ?)";
            $stmt = $con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ss", $startDate, $endDate);

                if ($stmt->execute()) {
                    echo "Academic year added successfully.";
                    exit();
                } else {
                    echo "Error executing SQL: " . $stmt->error;
                    exit();
                }
            } else {
                echo "Error preparing statement: " . $con->error;
                exit();
            }
        } else {
            echo "Start date or end date not provided.";
            exit();
        }
    } elseif (isset($_POST['delete-year'])) {
        $yearID = isset($_POST['yearID']) ? $_POST['yearID'] : null;

        if ($yearID !== null) {
            // Check if there is any associated data in related tables
            $tablesToCheck = [
                'semister' => 'YearID',
                'course' => 'YearID',
                'schedule' => 'YearID',
                'exam' => 'YearID',
                'mark' => 'YearID',
                'student' => 'YearID',
                'additional_fees' => 'YearID',
                'enrollment' => 'YearID',
                'teaching' => 'YearID',
                'sections' => 'YearID',
                'lectures' => 'YearID',
                'lb_payment' => 'YearID',
                'registration' => 'YearID',
                'admin' => 'YearID',
                'classes' => 'YearID',
                'teachers' => 'YearID'
            ];

            $hasData = false;
            foreach ($tablesToCheck as $table => $column) {
                $sql = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
                $stmt = $con->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("i", $yearID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    if ($row['count'] > 0) {
                        $hasData = true;
                        break;
                    }
                    $stmt->close();
                } else {
                    echo "Error preparing statement for table $table: " . $con->error . "<br>";
                }
            }

            if ($hasData) {
                echo "<script>alert('This academic year cannot be deleted as it has associated data.');</script>";
            } else {
                // Delete the academic year if no associated data is found
                $tables = array_merge(array_keys($tablesToCheck), ['academicyear']);

                foreach ($tables as $table) {
                    $sql = "DELETE FROM $table WHERE YearID = ?";
                    $stmt = $con->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("i", $yearID);

                        if ($stmt->execute()) {
                            // echo "Records deleted successfully from $table.<br>";
                        } else {
                            echo "Error deleting records from $table: " . $stmt->error . "<br>";
                        }
                        $stmt->close();
                    } else {
                        echo "Error preparing statement for table $table: " . $con->error . "<br>";
                    }
                }
                header("Location: academicYear.php");
            }
        } else {
            echo "YearID not provided.";
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['year_id'])) {
    $yearId = $_GET['year_id'];

    $sql = "SELECT * FROM academicyear WHERE YearID = ?";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $yearId);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        header('Content-Type: application/json');
        echo json_encode($row);
        exit();
    } else {
        echo "Error preparing statement: " . $con->error;
        exit();
    }
}
?>
