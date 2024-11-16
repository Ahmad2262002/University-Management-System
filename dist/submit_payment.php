<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $method = $_POST['method'];
    $amount = $_POST['amount'];
    $date = date('Y-m-d', strtotime($_POST['date']));
    $year_id = $_POST['year_id'];
    $currency = $_POST['currency'];
    $semester_id = $_POST['semester'];

    // Check if the 'semister_courses' table exists
    $result = $con->query("SHOW TABLES LIKE 'semister_courses'");
    if ($result->num_rows == 0) {
        die("Error: Table 'semister_courses' doesn't exist.");
    }

    // Create temporary table for credit calculations
    $sql1 = "
    CREATE TEMPORARY TABLE IF NOT EXISTS temp_total_credit_price AS
    SELECT 
        sc.SemesterID,
        SUM(c.credit_price * c.credits) AS total_credit_price
    FROM 
        semister_courses sc
    JOIN 
        course c ON sc.CourseID = c.CourseID
    GROUP BY 
        sc.SemesterID;
    ";

    if ($con->query($sql1) === TRUE) {
        // Update remaining amount in payment table
        $sql2 = "
        UPDATE 
            " . ($currency == 'USD' ? "additional_fees" : "lb_payment") . " lp
        JOIN 
            temp_total_credit_price ttp ON lp.semester_id = ttp.SemesterID
        SET 
            lp.RemainingAmount = ttp.total_credit_price;
        ";

        if ($con->query($sql2) === TRUE) {
            $con->query("DROP TEMPORARY TABLE IF EXISTS temp_total_credit_price;");
        } else {
            echo "Error updating records: " . $con->error;
        }
    } else {
        echo "Error creating temporary table: " . $con->error;
    }

    // Default values
    $default_annual_amount = ($currency == 'USD') ? 600 : 36000000;
    $default_remaining_amount = ($currency == 'USD') ? 300 : 18000000;

    // Fetch existing payment details for the student
    $stmt1 = $con->prepare("SELECT semester_id, paid_amount, Total_amount, annual_amount, RemainingAmount, Total_remaining, annual_remaining FROM " . ($currency == 'USD' ? "additional_fees" : "lb_payment") . " WHERE students_id = ?");
    $stmt1->bind_param("i", $student_id);
    $stmt1->execute();
    $stmt1->bind_result($existing_semester_id, $new_paid_amount, $old_total_amount, $old_annual_amount, $old_remaining_amount, $old_total_remaining, $old_annual_remaining);

    // Initialize variables
    $total_amount = $amount;
    $remaining_amount = $default_remaining_amount - $amount;
    $annual_amount = $amount;
    $total_remainingAmount = $default_remaining_amount - $remaining_amount;
    $annual_remainingAmount = $default_annual_amount - $annual_amount;

    while ($stmt1->fetch()) {
        if ($existing_semester_id != $semester_id) {
            // Different semester, update annual values
            $annual_amount = $old_annual_amount + $amount;
            $total_remainingAmount = $old_total_remaining - $amount;
            $annual_remainingAmount = $old_annual_remaining - $amount;
        } else {
            // Same semester, accumulate values
            $total_amount += $new_paid_amount;
            $remaining_amount = $old_remaining_amount - $amount;
            $total_remainingAmount = $old_total_remaining - $amount;
            $annual_remainingAmount = $old_annual_remaining - $amount;
        }
    }
    $stmt1->close();

    // Insert new payment record
    $stmt2 = $con->prepare("INSERT INTO " . ($currency == 'USD' ? "additional_fees" : "lb_payment") . " (students_id, Method, paid_amount, Total_amount, annual_amount, PaymentDate, YearID, RemainingAmount, Total_remaining, annual_remaining, semester_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("isssisiiiii", $student_id, $method, $amount, $total_amount, $annual_amount, $date, $year_id, $remaining_amount, $total_remainingAmount, $annual_remainingAmount, $semester_id);
    if ($stmt2->execute()) {
        echo "Payment recorded successfully!";
        header("location: Payment.php");
        exit();
    } else {
        echo "Error executing statement: " . $stmt2->error;
    }
    $stmt2->close();
}
?>
