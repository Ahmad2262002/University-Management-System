<?php
session_start();
include "../dist/connection.php";

include "../dist/security.php";

// Check if the user is logged in and has a valid email in the session
if (!isset($_SESSION['email'])) {
   // Handle the case when the user is not logged in, e.g., redirect to a login page
   header("Location: logIn.php"); // Replace 'login.php' with your actual login page
   exit();
}

// Get the user's email from the session
$user = $_SESSION['email'];

$TeacherID = $_SESSION['teacherID']; // Retrieve teacherID from session

// Initialize $UserName and $profile_photo with default values
$UserName = "Default User";
$profile_photo = "Default Photo URL";

// Fetch the last entered user from the admin table
$sql = "SELECT name,photo_url FROM teachers WHERE teacherID  = $TeacherID";
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



$stmt = $con->prepare("
    SELECT s.name, s.major, s.photo_url
    FROM student s
    JOIN enrollment e ON s.students_id = e.students_id
    JOIN teaching t ON e.CourseID = t.CourseID AND e.YearID = t.YearID
    WHERE t.teacherID = ?");
$stmt->bind_param("i", $TeacherID);

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

$TeacherID = $_SESSION['teacherID']; // Retrieve teacherID from session

$stmtc = $con->prepare("
    SELECT c.name AS CourseName, d.dept_name AS CourseDepartment
    FROM teachers t
    JOIN teaching te ON t.teacherID = te.teacherID
    JOIN course c ON te.CourseID = c.CourseID
    JOIN departments d ON c.dept_id = d.dept_id
    WHERE t.teacherID = ?");
$stmtc->bind_param("i", $TeacherID);

if ($stmtc->execute()) {
    $resultc = $stmtc->get_result();
    
    $Courses = [];
    if ($resultc->num_rows > 0) {
        while ($row = $resultc->fetch_assoc()) {
            $Courses[] = $row;
        }
    } else {
        echo "0 results";
    }
} else {
    echo "Execute failed: (" . $stmtc->errno . ") " . $stmtc->error;
}

$stmtc->close();
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
   <title>teachers</title>
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
   
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
   
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
                        <h6>Teacher: <?php echo $UserName; ?></h6>
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
                  <li>
                     <a href="#element" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i
                           class="fa fa-diamond purple_color"></i> <span>Task Tracker</span></a>
                     <ul class="collapse list-unstyled" id="element">
                        <li><a href="../dist/lectures.php">> <span>Lectures</span></a></li>
                        <li><a href="../dist/Assignments.php">> <span>assignments</span></a></li>
                        <li><a href="../dist/Attendance.php">> <span>Attendance</span></a></li>

                     </ul>
                  </li>
                  <li><a href="viewSchedule.php"><i class="fa fa-table purple_color2"></i> <span>Schedules</span></a></li>


                  <li class="active">
                     <a href="#additional_page" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i
                           class="fa fa-clone yellow_color"></i> <span> Account Avengers</span></a>
                     <ul class="collapse list-unstyled" id="additional_page">
                        <li>
                           <a href="../dist/Teacherprofile.php">> <span>Profile</span></a>
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
                        <a href="TeacherCP.php"><img class="img-responsive" src="../Pictures\Logo2-removebg.png"
                              alt="logo" /></a>
                     </div>
                     <div class="right_topbar">
                        <div class="icon_info">
                           <ul>
                              <li><a href="newsIndex.php"><i class="fa fa-bell-o"></i><span class="badge"><?php
                              include 'connection.php';

                              $query = "SELECT COUNT(*) FROM articles where status =1 and category = 'Teachers'";

                              $result = mysqli_query($con, $query); // Execute the query
                              $count = mysqli_fetch_row($result)[0]; // Get the count
                              
                              echo $count; // Display the count
                              ?></span></a></li>

                           </ul>
                           <ul class="user_profile_dd">
                              <li>
                                 <a class="dropdown-toggle" data-toggle="dropdown"><img
                                       class="img-responsive rounded-circle" src="<?php echo $profile_photo; ?>"
                                       alt="#" /><span class="name_user"><?php echo $UserName; ?></span></a>
                                 <div class="dropdown-menu">
                                    <a class="dropdown-item" href="../dist/Teacherprofile.php">My Profile</a>
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
                     <div class="col-md-6">
                        <div class="dark_bg full margin_bottom_30">
                           <div class="full graph_head">
                              <div class="heading1 margin_0">
                                 <h2>Courses</h2>
                              </div>
                           </div>
                           <div class="full graph_revenue">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="content testimonial">
                                       <div id="testimonial_slider" class="carousel slide" data-ride="carousel">
                                          <!-- Wrapper for carousel items -->
                                          <div class="carousel-inner">
                                             <div class="item carousel-item active">
                                                <div class="msg_list_main">
                                                   <ul class="msg_list">
                                                      <?php foreach ($Courses as $course): ?>
                                                         <li>

                                                            <p class="testimonial"> <span>
                                                            <span
                                                            class="msg_course"><?php echo isset($course['CourseDepartment']) ? $course['CourseDepartment'] : ''; ?></span>

                                                               </span></p>
                                                            <p class="overview">
                                                            <span
                                                            class="name_course"><?php echo isset($course['CourseName']) ? $course['CourseName'] : ''; ?></span>
                                                            </p>
                                                         </li>
                                                      <?php endforeach; ?>
                                                   </ul>
                                                </div>
                                             </div>

                                          </div>
                                          <!-- Carousel controls -->
                                          <a class="carousel-control left carousel-control-prev"
                                             href="#testimonial_slider" data-slide="prev">
                                             <i class="fa fa-angle-left"></i>
                                          </a>
                                          <a class="carousel-control right carousel-control-next"
                                             href="#testimonial_slider" data-slide="next">
                                             <i class="fa fa-angle-right"></i>
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- end testimonial -->



                     <div class="col-md-6">
                        <div class="dash_blog">
                           <div class="dash_blog_inner">
                              <div class="dash_head">
                                 <h3><span><i class="fa fa-comments-o"></i> Students</span></h3>
                              </div>
                              <div class="list_cont">
                                 <p>Students</p>
                              </div>
                              <div class="msg_list_main">
                                 <ul class="msg_list">
                                    <?php foreach ($Students as $student): ?>
                                       <li>
                                          <span><img src="<?php echo $student['photo_url']; ?>" class="img-responsive"
                                                alt="#" />
                                          </span>
                                          <span>
                                             <span class="name_user"><?php echo $student['name']; ?></span>
                                             <span class="msg_user"><?php echo $student['major']; ?></span>
                                          </span>
                                       </li>
                                    <?php endforeach; ?>
                                 </ul>
                              </div>

                           </div>
                        </div>
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
     <script src="../js/jquery.min.js  "></script>
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