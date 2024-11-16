<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IUL</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .custom-heading {
      color: #9F7944;
      /* Darker gold color */
      font-family: 'Times New Roman', Times, serif;
      text-shadow: 1px 1px 2px #000;
      font-size: 3rem;
      /* Adjusted to be significantly larger */
    }

    .text-container {
      height: 100%;
      display: flex;
      align-items: center;
    }

    .img-fluid {
      max-height: 100%;
    }

    .col-md-6 {
      display: flex;
      align-items: center;
    }

    body {
      background-color: darkgrey;
      color: #f5f5f5;
    }

    .card {
      background-color: #444;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    /* Override anchor tag styles within card body */
    /* Add these styles */
    .card-body {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .card-body a {
      color: inherit;
      /* Make the link text color same as normal text */
      text-decoration: none;
      /* Remove underline from link text */
    }

    .card-body a:hover {
      color: inherit;
      /* Keep the link text color same on hover */
      text-decoration: none;
      /* Remove underline from link text on hover */
    }

    .carousel-caption {
      background-color: rgba(0, 0, 0, 0.7);
      /* Black background with 70% opacity */
      border-radius: 10px;
      /* Rounded corners */
    }

    .carousel-inner {
      height: 100%;
      /* Adjust this value to change the height of the carousel */
      width: 100%;
      /* Full width */
    }

    .carousel-inner img,
    .carousel-inner video {
      object-fit: cover;
      /* This will make sure your images and videos cover the entire space of the carousel */
    }

    .card1 {
      width: 12rem;
      /* Adjust this value to change the width of the cards */
      height: 10rem;
      /* Adjust this value to change the height of the cards */
      margin-bottom: 2rem;
    }

    .card1-img-top {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .title {
      text-align: center;
      font-size: 2rem;
      margin-bottom: 1rem;
      color: #333;
      font-family: 'Times New Roman', Times, serif;
      /* This will give your text a classic, sophisticated look */
      font-weight: bold;
      /* This will make your text stand out more */
    }

    .container {
            width: 100%;
            margin: 5rem 0;
        }
        h2 {
            text-align: center;
            color: #9F7944;
      /* Darker gold color */
      font-family: 'Times New Roman', Times, serif;
      
      font-size: 3rem;
        }


        /* Footer Styles */
    .footer {
      background-color: #0d3e63; /* Dark blue background */
      color: #f0f0f0; /* Light gray text */
      padding: 2rem 0;
      text-align: center;
      border-radius: 10px;
    }

    .footer p {
      font-size: 1rem;
    }

    .footer a {
      color: #64D2FF; /* Blue link color */
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline; /* Underline on hover */
    }
  </style>
</head>

<body>
  <div class="container text-center my-5">
    <div class="row align-items-center">
      <div class="col-md-6 text-start">
        <a href="https://www.iul.edu.lb/" style="color: inherit; text-decoration: none;"><img
            src="../Pictures/IUL-removebg-preview (1).png" alt="Logo" class="img-fluid"></a>
      </div>
      <div class="col-md-6 text-end">
        <div class="text-container">
          <h1 class="custom-heading">ISLAMIC UNIVERSITY OF LEBANON</h1>
        </div>
      </div>
    </div>
  </div>


  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <a href="LogIn.php" class="text-decoration-none">
          <div class="card">
            <img src="../Pictures/Online test-rafiki.png" class="card-img-top" alt="Admin">
            <div class="card-body">
              <h5 class="card-title">Admin</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="StudentLogin.php" class="text-decoration-none">
          <div class="card">
            <img src="../Pictures/Learning-rafiki.png" class="card-img-top" alt="Student">
            <div class="card-body">
              <h5 class="card-title">Students</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="LogIn.php" class="text-decoration-none">
          <div class="card">
            <img src="../Pictures/Online learning-amico.png" class="card-img-top" alt="Teacher">
            <div class="card-body">
              <h5 class="card-title">Teachers</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="RegLogIn.php" class="text-decoration-none">
          <div class="card">
            <img src="../Pictures/college students-rafiki.png" class="card-img-top" alt="Registration">
            <div class="card-body">
              <h5 class="card-title">Registration</h5>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
  <div class="container my-5">
    <h2>Latest News</h2>
    <?php
    include "../dist/connection.php";
    require "../dist/outNews.php";
    ?>
  </div>

  <div class="container my-5">
    <div class="row">
      <div class="col-md-6">
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <video class="d-block w-100" autoplay loop muted>
                <source src="../Pictures/IUL.mp4" type="video/mp4">
              </video>
              <div class="carousel-caption d-none d-md-block">
                <p class="description">IUL is a non-profit private institution affiliated to the Supreme Islamic Shiite
                  Council established by Law no. 72/67, and granted educational, administrative and financial autonomy.
                  Initiated by the desire of Imam Sayyed Moussa EL-SADER.
                  IUL today, under the slogan “A University for all country citizens and the Arab world,” endeavors at
                  promoting and developing higher education. It is characterized by its regional and international
                  relationships, and embraces students, professors and administrators all over Lebanon and the Arab and
                  European world.</p>
              </div>
            </div>

            <div class="carousel-item">
              <img src="../Pictures/Islamic University of Lebanon_files/WhatsApp-Image-2021-03-01-at-1.24.32-PM.jpeg"
                class="d-block w-100" alt="iul">

            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
            data-bs-slide="prev" style="display: none;">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
            data-bs-slide="next" style="display: none;">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden"></span>
          </button>

        </div>
      </div>
      <div class="col-md-6">
        <div class="card bg-dark text-white h-100">
          <div class="card-body">
            <p class="card-text">
              The Islamic University of Lebanon was granted a five-year unreserved accreditation by the High Council
              for Evaluation of Research and Higher Education (HCERES).

              HCERES is the French independent administrative authority responsible for evaluating all higher
              education and research organisations under the aegis of the French Ministry of Higher Education.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <


  <div class="container-fluid text-center">
    <h1 class="title">Partners</h1>
    <img src="../Pictures/Islamic University of Lebanon_files/Partners.png" class="mx-auto d-block w-20"
      style="padding: 20px;" alt="iul center">
  </div>



  <!-- Carousel -->
  <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel"
    style="background: rgba(0, 0, 0, 0.5); padding: 10px; overflow-x: auto; white-space: nowrap;">
    <?php
    $dir = "../Pictures/uni/";
    $images = glob($dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    $i = 0;
    foreach ($images as $image): ?>
      <div class="carousel-item <?php echo ($i == 0) ? 'active' : ''; ?>"
        style="display: inline-block; width: 200px; height: 200px; margin-right: 10px;">
        <div class="card">
          <img src="<?php echo $image; ?>" class="card-img-top" alt="Card image"
            style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
        </div>
      </div>
      <?php
      $i++;
    endforeach; ?>
  </div>



  </div>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
</body>

<!-- Footer -->
<!-- Footer -->
<footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col">
          <h5>Contact Info</h5>
          <p>Call Now:<br>
            +961 (5) 807 711-16<br>
            +961 (5) 807 719<br>
            <a href="mailto:info@iul.edu.lb">info@iul.edu.lb</a><br>
            Mon - Fri 08:00-17:00
          </p>
        </div>
        <div class="col">
          <h5>We’d love to hear from you.</h5>
          <p>This is the place to start your ambition Journey, find the answers you need from our support team.</p>
        </div>
      </div>
    </div>
  </footer>



</html>