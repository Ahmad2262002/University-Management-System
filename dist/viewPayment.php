<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Tracker</title>
  <link rel="stylesheet" href="style.css">
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />
  <style>
    body {
      background-color: #f8f9fa;
      color: #333;
      font-family: 'Georgia', serif;
    }

    .container {
      padding: 20px;
    }

    .navbar {
      background-color: #2b3e50;
    }

    .navbar-brand {
      font-size: 1.5rem;
      font-weight: bold;
      color: #fff;
    }

    .alert {
      background-color: #e9ecef;
      color: #333;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
    }

    .alert-info {
      border-left: 5px solid #007bff;
    }

    .alert-warning {
      border-left: 5px solid #ffc107;
    }

    .alert div {
      font-size: 1.2rem;
    }

    .header-title {
      font-size: 2rem;
      font-weight: bold;
      color: #2b3e50;
      margin-bottom: 20px;
    }

    .nav-item a {
      color: #007bff;
      font-size: 1.1rem;
      margin-right: 15px;
    }

    .nav-item a:hover {
      color: #0056b3;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="StudentCP.php">Back</a>
  </nav>

  <div class="container mt-4">
    <div class="header-title">Pending Payment Details</div>

    <?php
    session_start();
    include "connection.php";

    $StudentID = $_SESSION['students_id'];

    // Fetch latest USD payment
    $usdQuery = "SELECT paid_amount, annual_amount, Total_amount, PaymentDate, RemainingAmount, annual_remaining 
             FROM additional_fees 
             WHERE students_id=?
             ORDER BY PaymentDate DESC
             LIMIT 1";
    $usdStmt = $con->prepare($usdQuery);
    $usdStmt->bind_param("i", $StudentID);
    $usdStmt->execute();
    $usdResult = $usdStmt->get_result();

    // Check if there are USD payments
    if ($usdRow = $usdResult->fetch_assoc()) {
      echo "<div class='alert alert-info payment-details' data-details='USD'>";
      echo "<div class='latest-details'>";
      echo "<div>Annual Paid: {$usdRow['annual_amount']}$</div>";
      echo "<div>Annual Remaining: {$usdRow['annual_remaining']}$</div>";
      echo "</div>";
      echo "<button class='btn btn-link view-more'>View More</button>";
      echo "<div class='more-details' style='display: none;'>";
      echo "<h5>USD Payments Details:</h5>";
      $usdPaymentsQuery = "SELECT paid_amount, PaymentDate, RemainingAmount 
                         FROM additional_fees 
                         WHERE students_id=?
                         ORDER BY PaymentDate DESC";
      $usdPaymentsStmt = $con->prepare($usdPaymentsQuery);
      $usdPaymentsStmt->bind_param("i", $StudentID);
      $usdPaymentsStmt->execute();
      $usdPaymentsResult = $usdPaymentsStmt->get_result();

      echo "<ul>";
      while ($payment = $usdPaymentsResult->fetch_assoc()) {
        echo "<li>";
        echo "<div>Paid paid: {$payment['paid_amount']}</div>";
        echo "<div>Payment Date: {$payment['PaymentDate']}</div>";
        echo "<div>Remaining Amount: {$payment['RemainingAmount']}</div>";
        echo "</li>";
      }
      echo "</ul>";
      echo "</div>"; // end .more-details
      echo "</div>"; // end .alert-info
    
      // Check if annual_remaining is 0.00
      if ($usdRow['annual_remaining'] == 0.00) {
        echo "<div class='alert alert-success'>";
        echo "<div>You have paid all pending USD payments for this Year.</div>";
        echo "</div>";
      }
    } else {
      echo "<div class='alert alert-warning'>";
      echo "<div>No USD payments found for the current Year.</div>";
      echo "</div>";
    }

    // Fetch latest LB payment
    $lbQuery = "SELECT paid_amount, annual_amount, Total_amount, PaymentDate, RemainingAmount, annual_remaining 
            FROM lb_payment 
            WHERE students_id=?
            ORDER BY PaymentDate DESC
            LIMIT 1";
    $lbStmt = $con->prepare($lbQuery);
    $lbStmt->bind_param("i", $StudentID);
    $lbStmt->execute();
    $lbResult = $lbStmt->get_result();

    // Check if there are LB payments
    if ($lbRow = $lbResult->fetch_assoc()) {
      echo "<div class='alert alert-info payment-details' data-details='LB'>";
      echo "<div class='latest-details'>";
      echo "<div>Annual Paid Amount: {$lbRow['annual_amount']}LPB</div>";
      echo "<div>Annual Remaining: {$lbRow['annual_remaining']}LPB</div>";
      echo "</div>";
      echo "<button class='btn btn-link view-more'>View More</button>";
      echo "<div class='more-details' style='display: none;'>";
      echo "<h5>LB Payments Details:</h5>";
      $lbPaymentsQuery = "SELECT paid_amount, PaymentDate, RemainingAmount 
                        FROM lb_payment 
                        WHERE students_id=?
                        ORDER BY PaymentDate DESC";
      $lbPaymentsStmt = $con->prepare($lbPaymentsQuery);
      $lbPaymentsStmt->bind_param("i", $StudentID);
      $lbPaymentsStmt->execute();
      $lbPaymentsResult = $lbPaymentsStmt->get_result();

      echo "<ul>";
      while ($payment = $lbPaymentsResult->fetch_assoc()) {
        echo "<li>";
        echo "<div>Paid Amount: {$payment['paid_amount']}</div>";
        echo "<div>Payment Date: {$payment['PaymentDate']}</div>";
        echo "<div>Remaining Amount: {$payment['RemainingAmount']}</div>";
        echo "</li>";
      }
      echo "</ul>";
      echo "</div>"; // end .more-details
      echo "</div>"; // end .alert-info
    
      // Check if annual_remaining is 0.00
      if ($lbRow['annual_remaining'] == 0.00) {
        echo "<div class='alert alert-success'>";
        echo "<div>You have paid all pending LB payments for this Year.</div>";
        echo "</div>";
      }
    } else {
      echo "<div class='alert alert-warning'>";
      echo "<div>No LB payments found for the current Year.</div>";
      echo "</div>";
    }
    ?>



  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function () {
      $('.view-more').click(function () {
        var details = $(this).siblings('.more-details');
        details.slideToggle();
      });
    });
  </script>
</body>

</html>