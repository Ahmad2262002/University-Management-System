<?php
session_start();

include "connection.php";

include "../dist/security.php";

// Check if the user is logged in and has a valid email in the session
if (!isset($_SESSION['email'])) {
   // Handle the case when the user is not logged in, e.g., redirect to a login page
   header("Location: logIn.php"); // Replace 'login.php' with your actual login page
   exit();
}

// Get the user's email from the session
$user = $_SESSION['email'];

$AdminID = $_SESSION['admin_id']; // Retrieve admin_id from session

// Initialize $UserName and $profile_photo with default values
$UserName = "Default User";
$profile_photo = "Default Photo URL";

// Fetch the last entered user from the admin table
$sql = "SELECT name,photo_url FROM admin WHERE admin_id = $AdminID";
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

$sql1 = "SELECT a.name, a.photo_url, s.dept_name  
         FROM admin a 
         JOIN departments s ON a.dept_id = s.dept_id 
         ORDER BY a.admin_id DESC 
         LIMIT 4";

$result1 = $con->query($sql1);

$admins = [];
if ($result1->num_rows > 0) {
   while ($row = $result1->fetch_assoc()) {
      $admins[] = $row;
   }
}
$con->close();
?>


<head>
   <!-- basic -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- mobile metas -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="viewport" content="initial-scale=1, maximum-scale=1">
   <!-- site metas -->
   <title>Admin</title>
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
   <link rel="stylesheet" href="../css/colors.css" />
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

   <style>
      /* General Styling */
      body {
         font-family: 'Roboto', sans-serif;
         background-color: #121212;
         /* Dark background */
         color: #f5f5f5;
         /* Light text for better contrast */
      }

      /* Dark Background Styling */
      .dark_bg {
         padding: 20px;
         border-radius: 10px;
         box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
         color: #fff;
      }

      /* Heading Styling */
      .full.graph_head .heading1 h2 {
         font-family: 'Merriweather', serif;
         /* More elegant font */
         font-weight: 700;
         color: #fff;
         margin-bottom: 20px;
         font-size: 28px;
         /* Larger font size for emphasis */
      }

      /* Carousel Styling */
      .carousel-inner .carousel-item {
         text-align: center;
         height: auto;
         /* Adjusted height to auto for dynamic content */
      }

      .carousel-inner .carousel-item .img-box {
         margin-bottom: 15px;
      }

      .carousel-inner .carousel-item img {
         border-radius: 10px;
         box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
         transition: transform 0.3s, box-shadow 0.3s;
         max-height: 300px;
         /* Limit image height */
         width: 100%;
         /* Ensure images fill their container */
      }

      .carousel-inner .carousel-item img:hover {
         transform: scale(1.05);
         box-shadow: 0 6px 25px rgba(0, 0, 0, 0.7);
      }

      .carousel-inner .carousel-item .testimonial {
         font-family: 'Roboto', sans-serif;
         font-size: 18px;
         font-weight: 500;
         margin-bottom: 10px;
      }

      .carousel-inner .carousel-item .overview {
         font-family: 'Roboto', sans-serif;
         font-size: 16px;
         margin-bottom: 20px;
      }

      /* Button Styling */
      .carousel-inner .carousel-item .btn-primary.read-more-btn {
         background-color: #007aff;
         border: none;
         color: #fff;
         padding: 10px 20px;
         border-radius: 5px;
         transition: background-color 0.3s, transform 0.3s;
         font-family: 'Merriweather', serif;
         /* Elegant font */
      }

      .carousel-inner .carousel-item .btn-primary.read-more-btn:hover {
         background-color: #ff4b3a;
         transform: scale(1.05);
      }
   </style>
</head>

<body class="dashboard dashboard_1">
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
                     <div class="user_img"><img class="img-responsive" src="<?php echo $profile_photo; ?>" alt="user" />
                     </div>
                     <div class="user_info">
                        <h6><?php echo $UserName; ?></h6>
                        <p><span class="online_animation"></span> Online</p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="sidebar_blog_2">
               <h4>General</h4>
               <ul class="list-unstyled components">


                  <li>
                     <a href="#element" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i
                           class="fa fa-object-group blue2_color"></i> <span>Educational Operations</span></a>
                     <ul class="collapse list-unstyled" id="element">
                        <li><a href="../dist/Classes.php">> <span>Classes</span></a></li>

                        <li><a href="../dist/exam.php">> <span>Exams</span></a></li>
                        <li><a href="../dist/studentsMark.php">> <span>Exams Grade</span></a></li>
                        <li><a href="../dist/Semisters.php">> <span>Semesters</span></a></li>

                        <li><a href="../dist/Student.php">> <span>Students</span></a></li>
                        <li><a href="../dist/Payment.php">> <span>Payments</span></a></li>
                        <li><a href="../dist/Staff.php">> <span>New Staff</span></a></li>
                     </ul>
                  </li>
                  <li><a href="Schedules.php"><i class="fa fa-table purple_color2"></i> <span>Schedules</span></a></li>

                  <li>
                     <a href="../dist/viewArticles.php">
                        <i class="fa fa-paper-plane red_color"></i> <span>Edit news</span></a>
                  </li>
                  <li class="active">
                     <a href="#additional_page" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i
                           class="fa fa-clone yellow_color"></i> <span>Account Avengers</span></a>
                     <ul class="collapse list-unstyled" id="additional_page">
                        <li>
                           <a href="../dist/profile.php">> <span>Profile</span></a>
                        </li>


                        <li>
                           <a href="../dist/logout.php">> <span>Log out</span></a>
                        </li>
                     </ul>
                  </li>
                  <li><a href="../dist/Settings.php"><i class="fa fa-cog yellow_color"></i> <span>Academic
                           Framework</span></a>
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
                        <a href="../dist/AdminCP.php"><img class="img-responsive" src="../Pictures\Logo2-removebg.png"
                              alt="logo" /></a>
                     </div>
                     <div class="right_topbar">
                        <div class="icon_info">
                        <ul>
                              <li><a href="Request.php"><i class="fa fa-bell-o"></i><span class="badge"><?php
                              include 'connection.php';

                              $query = "SELECT COUNT(*) FROM request ";

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
                                    <a class="dropdown-item" href="../dist/profile.php">My Profile</a>
                                    <a class="dropdown-item" href="../dist/Settings.php">Academic Framework</a>
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
                        <div class="full counter_section margin_bottom_30">
                           <div class="couter_icon">
                              <div>
                                 <i class="fa fa-user yellow_color"></i>
                              </div>
                           </div>
                           <div class="counter_no">
                              <div>
                                 <p class="head_couter"><span><strong>Welcome</strong></span> <?php echo $UserName; ?>
                                 </p>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="col-md-6 col-lg-3">
                        <div class="full counter_section margin_bottom_30">
                           <div class="couter_icon">
                              <div>
                                 <i class="fa fa-clock-o blue1_color"></i>
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
                              var timeSinceLogin = (Date.now() - loginTime) / 1000 / 60;
                              timerElement.textContent = timeSinceLogin.toFixed(2) + " minutes";
                           }, 1000);
                        }

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
                     <!-- testimonial -->
                     <div class="col-md-6">
                        <div class="dark_bg full margin_bottom_30">
                           <div class="full graph_head">
                              <div class="heading1 margin_0">
                                 <h2>Latest News</h2>
                              </div>
                           </div>
                           <div class="full graph_revenue">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="content testimonial">
                                       <div id="testimonial_slider" class="carousel slide" data-ride="carousel"
                                          data-interval="3000">
                                          <!-- Wrapper for carousel items -->
                                          <div class="carousel-inner">
                                             <?php
                                             include 'connection.php';

                                             $latestArticleQuery = "SELECT * FROM articles WHERE status = 1 ORDER BY id DESC";
                                             $latestArticleResult = mysqli_query($con, $latestArticleQuery);

                                             if (mysqli_num_rows($latestArticleResult) > 0) {
                                                $isFirst = true;
                                                while ($latestArticle = mysqli_fetch_assoc($latestArticleResult)) {
                                                   ?>
                                                   <div class="carousel-item <?php echo $isFirst ? 'active' : ''; ?>">
                                                      <div class="img-box">
                                                         <img
                                                            src="<?php echo htmlspecialchars($latestArticle['photo_url']); ?>"
                                                            class="card-img-top" alt="Big Post Image">
                                                      </div>
                                                      <p class="testimonial">
                                                         <?php echo htmlspecialchars($latestArticle['title']); ?></p>
                                                      <p class="testimonial">
                                                         <?php echo htmlspecialchars($latestArticle['article']); ?></p>
                                                      <p class="overview">
                                                         <a href="artDetails.php?id=<?php echo htmlspecialchars($latestArticle['id']); ?>"
                                                            class="btn btn-primary read-more-btn">Read More</a>
                                                      </p>
                                                   </div>
                                                   <?php
                                                   $isFirst = false;
                                                }
                                             } else {
                                                echo '<p>No articles found.</p>';
                                             }
                                             ?>
                                          </div>
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
                                 <h3><span><i class="fa fa-comments-o"></i> Admins</span></h3>
                              </div>
                              <div class="list_cont">
                                 <p>Admins</p>
                              </div>
                              <div class="msg_list_main">
                                 <ul class="msg_list">
                                    <?php if (!empty($admins)): ?>
                                       <?php foreach ($admins as $admin): ?>
                                          <li>
                                             <span><img src="<?php echo htmlspecialchars($admin['photo_url']); ?>"
                                                   class="img-responsive" alt="#" /></span>
                                             <span>
                                                <span class="name_user"><?php echo htmlspecialchars($admin['name']); ?></span>
                                                <span
                                                   class="msg_user"><?php echo htmlspecialchars($admin['dept_name']); ?></span>
                                             </span>
                                          </li>
                                       <?php endforeach; ?>
                                    <?php else: ?>
                                       <li>No results found</li>
                                    <?php endif; ?>
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
   <script src="../js/chart_custom_style1.js"></script>
   <script src="../js/custom.js"></script>
   <!--<script>
      $(document).ready(function () {
         $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
         });
      });
   </script>-->
</body>

</html>