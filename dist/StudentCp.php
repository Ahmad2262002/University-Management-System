<?php
session_start();
include "../dist/connection.php";

include "../dist/security.php";

if (!isset($_SESSION['email'])) {
   header("Location: StudentLogin.php");
   exit();
}

$user = $_SESSION['email'];

$StudentID = $_SESSION['students_id'];

$UserName = "Default User";
$profile_photo = "Default Photo URL";

$sql = "SELECT name,photo_url FROM student WHERE students_id   = $StudentID";
$result = $con->query($sql);
if (!$result) {
   die('Invalid query: ' . mysqli_error($con));
}

if ($result->num_rows > 0) {
   // Output data of each row
   while ($row = $result->fetch_assoc()) {
      $UserName = $row["name"];
      $profile_photo = $row["photo_url"];
   }
} else {
   echo "0 results";
}



$stmt = $con->prepare("SELECT  s.name, s.major, s.photo_url
FROM student s
JOIN enrollment e ON s.students_id = e.students_id
JOIN teaching t ON e.CourseID = t.CourseID AND e.YearID = t.YearID
WHERE t.teacherID = ? ");

$stmt->bind_param("i", $StudentID);
$stmt->execute();
$result1 = $stmt->get_result();
if (!$result) {
   die('Invalid query: ' . mysqli_error($con));
}
$Students = [];
if ($result1->num_rows > 0) {
   // output data of each row
   while ($row = $result1->fetch_assoc()) {
      $Students[] = $row;
   }
} else {
   echo "0 results";
}


$stmt1 = $con->prepare("
    SELECT c.title AS CourseTitle, c.department AS CourseDepartment
    FROM teachers t
    JOIN teaching te ON t.teacherID = te.teacherID
    JOIN course c ON te.CourseID = c.CourseID
    WHERE t.teacherID = ?;
");

//$stmt1->bind_param("i", $TeacherID);
//$stmt1->execute();
//$result1 = $stmt1->get_result();

if (!$result1) {
   die('Invalid query: ' . mysqli_error($con));
}

$Courses = [];
if ($result1->num_rows > 0) {
   // Output data of each row
   while ($row = $result1->fetch_assoc()) {
      $Courses[] = $row;
   }
} else {
   echo "0 results";
}

$con->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <!-- basic -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- mobile metas -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="viewport" content="initial-scale=1, maximum-scale=1">
   <!-- site metas -->
   <title>Students Dashboard</title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">
   <!-- site icon -->
   <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />
   <!-- bootstrap css -->
   <link rel="stylesheet" href="../css/bootstrap.min.css" />
   <!-- site css -->
   <link rel="stylesheet" href="../css/styles.css" />
   <!-- responsive css -->
   <link rel="stylesheet" href="../css/responsive.css" />
   <!-- color css -->
   <link rel="stylesheet" href="../css/color_2.css" />
   <!-- select bootstrap -->
   <link rel="stylesheet" href="../css/bootstrap-select.css" />
   <!-- scrollbar css -->
   <link rel="stylesheet" href="../css/perfect-scrollbar.css" />
   <!-- custom css -->
   <link rel="stylesheet" href="../css/custom.css" />
   <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
</head>

<body class="dashboard dashboard_2">
   <div class="full_container">
      <div class="inner_container">
         <!-- Sidebar  -->
         <nav id="sidebar">
            <div class="sidebar_blog_1">
               <div class="sidebar-header">

               </div>
               <div class="sidebar_user_info">
                  <div class="icon_setting"></div>
                  <div class="user_profle_side">
                     <div class="user_img"><img class="img-responsive" src="<?php echo $profile_photo; ?>" alt="user">
                     </div>
                     <div class="user_info">
                        <h6>Student: <?php echo $UserName; ?></h6>
                        <p><span class="online_animation"></span> Online</p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="sidebar_blog_2">
               <h4>General</h4>
               <ul class="list-unstyled components">
                  <li class="active">


                  </li>

                  <li><a href="Course_Table.php"><i class="fa fa-object-group blue2_color"></i> <span>Courses
                           Enroled</span></a></li>

                  <li><a href="viewPayment.php"><i class="fa fa-briefcase blue1_color"></i> <span>Pending
                           Payment</span></a></li>

                  <li><a href="viewSchedule.php"><i class="fa fa-table purple_color2"></i> <span>Schedules</span></a></li>


                  <li class="active">
                     <a href="#additional_page" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i
                           class="fa fa-clone yellow_color"></i> <span> Account Avengers</span></a>
                     <ul class="collapse list-unstyled" id="additional_page">
                        <li>
                           <a href="../dist/viewStudent.php">> <span>Profile</span></a>
                        </li>

                        <li>
                           <a href="../dist/logout.php">> <span>Log out</span></a>
                        </li>

                     </ul>
                  </li>
               </ul>
            </div>
         </nav>
         <!-- end sidebar -->
         <!-- right content -->
         <div id="content">
            <!-- topbar -->
            <div class="topbar">
               <nav class="navbar navbar-expand-lg navbar-light">
                  <div class="full">
                     <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i
                           class="fa fa-bars"></i></button>
                     <div class="logo_section">
                        <a href="StudentCp.php"><img class="img-responsive" src="../Pictures\Logo2-removebg.png"
                              alt="logo" /></a>
                     </div>
                     <div class="right_topbar">
                        <div class="icon_info">
                           <ul>
                              <li><a href="newsIndex.php"><i class="fa fa-bell-o"></i><span class="badge"><?php
                              include 'connection.php';

                              $query = "SELECT COUNT(*) FROM articles where status =1 and category = 'students'";

                              $result = mysqli_query($con, $query);
                              $count = mysqli_fetch_row($result)[0];

                              echo $count;
                              ?></span></a></li>

                           </ul>
                           <ul class="user_profile_dd">
                              <li>
                                 <a class="dropdown-toggle" data-toggle="dropdown"><img
                                       class="img-responsive rounded-circle" src="<?php echo $profile_photo; ?>"
                                       alt="#" /><span class="name_user"><?php echo $UserName; ?></span></a>
                                 <div class="dropdown-menu">
                                    <a class="dropdown-item" href="../dist/viewStudent.php">My Profile</a>
                                    <a class="dropdown-item" href="../dist/help.php">Help</a>
                                    <a class="dropdown-item" href="../dist/logout.php"><span>Log Out</span> <i
                                          class="fa fa-sign-out"></i></a>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </nav>
            </div>
            <!-- end topbar -->
            <!-- dashboard inner -->
            <div class="midde_cont">
               <div class="container-fluid">
                  <div class="row column_title">
                     <div class="col-md-12">
                        <div class="page_title">
                           <h2>Dashboard</h2>
                        </div>
                     </div>
                  </div>
                  <div class="row column1">
                     <div class="col-md-6 col-lg-3">
                        <div class="full counter_section margin_bottom_30 yellow_bg">
                           <div class="couter_icon">
                              <div>
                                 <i class="fa fa-user"></i>
                              </div>
                           </div>
                           <div class="counter_no">
                              <div>
                                 <p class="head_couter"><span><strong>Welcome</strong></span> <?php echo $UserName; ?>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6 col-lg-3">
                        <div class="full counter_section margin_bottom_30 blue1_bg">
                           <div class="couter_icon">
                              <div>
                                 <i class="fa fa-clock-o"></i>
                              </div>
                           </div>
                           <div class="counter_no">
                              <div>
                                 <p id="timer" class="total_no">0.00</p>
                                 <p class="head_couter">Average Time</p>
                              </div>
                           </div>
                        </div>
                     </div>
                     <script>
                        var timerElement = document.getElementById('timer');
                        var timerId;

                        function startTimer() {
                           var loginTime = localStorage.getItem('loginTime');
                           timerId = setInterval(function () {
                              var timeSinceLogin = (Date.now() - loginTime) / 1000 / 60; // convert milliseconds to minutes
                              timerElement.textContent = timeSinceLogin.toFixed(2) + " minutes";
                           }, 1000);
                        }

                        // Start the timer if the user is already logged in
                        if (localStorage.getItem('loginTime')) {
                           startTimer();
                        }
                     </script>








                     <div class="col-md-6 col-lg-3">
                        <div class="full socile_icons fb margin_bottom_30">
                           <div class="social_icon">
                              <a href="https://www.facebook.com/share/m1fTA4kFiL5WVmCc/?mibextid=K35XfP"><i
                                    class="fa fa-facebook"></i></a>
                           </div>
                           <div class="social_cont">
                              <ul>
                                 <li>
                                    <span><strong>15k</strong></span>
                                    <span>Members</span>
                                 </li>

                              </ul>
                           </div>
                        </div>
                     </div>

                     <div class="col-md-6 col-lg-3">
                        <div class="full socile_icons linked margin_bottom_30">
                           <div class="social_icon">
                              <a href="https://www.linkedin.com/company/islamicuniversityoflebanoniul/"><i
                                    class="fa fa-linkedin"></i></a>
                           </div>
                           <div class="social_cont">
                              <ul>
                                 <li>
                                    <span><strong>374</strong></span>
                                    <span>Members</span>
                                 </li>
                                 <li>
                                    <span><strong>1000</strong></span>
                                    <span>Employees</span>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>

                  </div>

                  <div class="row column3">
                  <div class="col-md-12">
                                 
                                <?php 
                                include '../dist/connection.php';
                                include '../dist/StudentCourses.php'; ?>
                            </div>

                  </div>
               </div>
               <!-- footer -->
               <div class="container-fluid">
                  <div class="footer">
                     <p>Copyright © 2024 Designed by AZ. All rights reserved.<br><br>
                     </p>
                  </div>
               </div>
            </div>
            <!-- end dashboard inner -->
         </div>
      </div>
   </div>
   <!-- jQuery -->
   <script src="../js/jquery.min.js"></script>
   <script src="../js/popper.min.js"></script>
   <script src="../js/bootstrap.min.js"></script>
   <!-- wow animation -->
   <script src="../js/animate.js"></script>
   <!-- select country -->
   <script src="../js/bootstrap-select.js"></script>
   <!-- owl carousel -->
   <script src="../js/owl.carousel.js"></script>
   <!-- chart js -->
   <script src="../js/Chart.min.js"></script>
   <script src="../js/Chart.bundle.min.js"></script>
   <script src="../js/utils.js"></script>
   <script src="../js/analyser.js"></script>
   <!-- nice scrollbar -->
   <script src="../js/perfect-scrollbar.min.js"></script>
   <script>
      var ps = new PerfectScrollbar('#sidebar');
   </script>
   <!-- custom js -->
   <script src="../js/custom.js"></script>
   <script src="../js/chart_custom_style2.js"></script>
   <!--<script>
      $(document).ready(function () {
         $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
         });
      });
   </script>-->
</body>

</html>