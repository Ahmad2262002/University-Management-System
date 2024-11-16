<?php
session_start();
include "connection.php";

if (!isset($_SESSION['students_id'])) {
   die('Student ID not set.');
}

$studentId = $_SESSION['students_id'];

$UserName = "Default User";
$IMG_URl = "Default Photo URL";
$phone = "Default Phone";
$email = "Default Email";
$Major = "Unknown";
$Password = "Unknown";
$ID = "Unknown";

$result = null;

if ($studentId !== null) {
   $stmt = $con->prepare("SELECT students_id, name, email, major, photo_url, password, phone_number FROM student WHERE students_id = ?");
   $stmt->bind_param("i", $studentId);
   $stmt->execute();
   $result = $stmt->get_result();
}

if (!$result || $result->num_rows === 0) {
   die('Invalid query: ' . mysqli_error($con));
}

if ($result->num_rows > 0) {
   $row = $result->fetch_assoc();
   $UserName = $row["name"];
   $Major = $row["major"];
   $phone = $row["phone_number"];
   $Password = $row["password"];
   $IMG_URl = $row["photo_url"];
   $email = $row["email"];
   $ID = $row["students_id"];

   $stmt->close();

   // Fetch the department name
   $query = "
        SELECT d.dept_name 
        FROM departments d
        WHERE d.dept_id = ?
    ";
   $stmt = $con->prepare($query);
   $stmt->bind_param("i", $Major);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $Major = $row["dept_name"];
   } else {
      echo "No department found for the given major.";
   }

   $stmt->close();
} else {
   echo "0 results";
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <title>Profile</title>
   <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png" />
   <link rel="stylesheet" href="../css/bootstrap.min.css" />
   <link rel="stylesheet" href="../css/styles.css" />
   <link rel="stylesheet" href="../css/responsive.css" />
   <link rel="stylesheet" href="../css/bootstrap-select.css" />
   <link rel="stylesheet" href="../css/custom.css" />
   <style>
      .navbar {
         background-color: rgba(255, 255, 255, 0.8);
         box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
      }

      .profile-card {
         max-width: 600px;
         margin: auto;
         background: #fff;
         border-radius: 15px;
         box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
         overflow: hidden;
         position: relative;
         transition: transform 0.3s ease;
         height: auto;
         /* Adjust the height as needed */
      }

      .profile-body {
         padding: 20px;
         position: relative;
         max-height: 250px;
         /* Set a maximum height for the profile body */
      }


      .profile-card:hover {
         transform: scale(1.05);
      }

      .profile-header {
         display: flex;
         align-items: center;
         padding: 20px;
         background: #f8f9fa;
      }

      .profile-header img {
         border-radius: 50%;
         width: 100px;
         height: 100px;
         object-fit: cover;
         margin-right: 20px;
      }

      .profile-header .profile-info {
         flex: 1;
      }

      .profile-header .profile-info h3 {
         margin-bottom: 10px;
         font-size: 20px;
         font-weight: bold;
      }

      .profile-header .profile-info p {
         margin-bottom: 5px;
         font-size: 14px;
         color: #666;
      }

      .profile-body .profile-about {
         margin-bottom: 15px;
      }

      .profile-body .profile-about h4 {
         margin-bottom: 10px;
         font-size: 16px;
         font-weight: bold;
      }

      .profile-body .profile-about p {
         white-space: pre-line;
         font-size: 14px;
         color: #666;
      }

      .profile-body .contact-info {
         list-style: none;
         padding: 0;
         font-size: 14px;
         color: #666;
      }

      .profile-body .contact-info li {
         margin-bottom: 10px;
      }

      .profile-body .contact-info i {
         margin-right: 10px;
         color: #007bff;
      }

      .profile-body img.bottom-right {
         position: absolute;
         top: -60px;
         right: 2px;
         max-width: 50%;
         max-height: 2000%;
         border-radius: 0;
         object-fit: contain;
         margin-right: 20px;
         padding-bottom: 100%;
      }

      .footer {
         text-align: center;
         padding: 20px;
         background: #f8f9fa;
         border-top: 1px solid #e9ecef;
      }

      .footer p {
         margin: 0;
         font-size: 14px;
         color: #666;
      }

      .download-button {
         margin-right: 10px;
      }
   </style>
   <script>
      function downloadCard() {
         html2canvas(document.querySelector(".profile-card")).then(canvas => {
            var link = document.createElement('a');
            link.href = canvas.toDataURL();
            link.download = 'profile_card.png';
            link.click();
         });
      }
   </script>
</head>

<body class="inner_page profile_page">
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
         <a class="navbar-brand">IUL</a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
               <li class="nav-item">
                  <button class="btn btn-primary download-button" onclick="downloadCard()">Download Card</button>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="javascript:history.back()">Back</a>
               </li>
            </ul>
         </div>
      </div>
   </nav>
   <div class="container">
      <div class="profile-card">
         <div class="profile-header">
            <img src="<?php echo $IMG_URl; ?>" alt="Profile Image">
            <div class="profile-info">
               <h3><?php echo $UserName; ?></h3>
               <p> <?php echo $Major;
               echo " - Bachelor Degree"; ?></p>
            </div>
         </div>

         <div class="profile-body">
            <div class="profile-about">
               <p><?php echo "Faculty of Science and Arts\n"; ?>
                  <?php echo "Phone: " . htmlspecialchars($phone);
                  echo " ID: " . htmlspecialchars($ID);
                  echo " Password: " . htmlspecialchars($Password) . "\n"; ?>
               </p>
            </div>
            <img class="bottom-right" src="../Pictures/IUL-removebg-preview (1).png" alt="IUL">
            <ul class="contact-info">
               <li><i class="fa fa-envelope-o"></i> <?php echo $email; ?></li>
               <li><i class="fa fa-globe"></i> www.iul.edu.lb - iul@iul.edu.lb</li>
               <li><i class="fa fa-phone"></i> Tel.: +961 5 807711-16</li>
            </ul>
         </div>
      </div>
   </div>
   <div class="footer">
      <p>Copyright © 2024 Designed by AZ. All rights reserved.</p>
   </div>
   <script src="../js/jquery.min.js"></script>
   <script src="../js/popper.min.js"></script>
   <script src="../js/bootstrap.min.js"></script>
   <script src="../js/animate.js"></script>
   <script src="../js/bootstrap-select.js"></script>
   <script src="../js/owl.carousel.js"></script>
   <script src="../js/Chart.min.js"></script>
   <script src="../js/Chart.bundle.min.js"></script>
   <script src="../js/utils.js"></script>
   <script src="../js/analyser.js"></script>
   <script src="../js/perfect-scrollbar.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js"></script>
</body>

</html>