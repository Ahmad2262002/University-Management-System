<!DOCTYPE html>
<html>

<head>
    <title>Web Page with Clock</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../Pictures\Logo2-removebg.png" type="image/png" />


    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1c1c1e;
            /* Dark background */
            color: white;
            text-align: center;
            padding: 50px;
        }

        #clock {
            font-size: 2em;
            margin-bottom: 50px;
        }

        .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card {
            background-color: #2c2c2e;
            /* Slightly lighter than the background */
            border-radius: 15px;
            padding: 20px;
            width: 200px;
            height: 200px;
            margin: 20px;
            transition: transform 0.3s;
            box-shadow: 0px 10px 20px rgba(255, 215, 0, 0.4);
            /* Gold shadow */
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card a {
            color: white;
            /* Same color as the card text */
            text-decoration: none;
            /* Removes underline */
            display: block;
            /* Makes the entire card clickable */
            height: 100%;
        }

        .back-button {
            background-color: #2c2c2e;
            /* Slightly lighter than the background */
            border-radius: 15px;
            padding: 20px;
            width: 200px;
            height: 50px;
            margin: 20px auto;
            display: block;
            transition: transform 0.3s;
            box-shadow: 0px 10px 20px rgba(255, 215, 0, 0.4);
            /* Gold shadow */
            cursor: pointer;
            color: white;
            /* Same color as the card text */
            text-decoration: none;
            /* Removes underline */
            text-align: center;
        }

        .back-button:hover {
            transform: scale(1.05);
        }
    </style>

</head>

<body>
    <div id="clock"></div>
    <div class="card-container">
        <div class="card">
            <a href="../dist/Course.php">
                <img src="../Pictures\Course app-rafiki.png" class="card-img-top" alt="course">
                <div class="card-body">
                    <h5 class="card-title">Courses</h5>
                </div>
            </a>
        </div>

        <div class="card">
            <a href="../dist/sections.php">
                <img src="../Pictures/Classroom-rafiki.png" class="card-img-top" alt="Section">
                <div class="card-body">
                    <h5 class="card-title">Sections</h5>
                </div>
            </a>
        </div>

        <div class="card">
            <a href="../dist/academicYear.php">
                <img src="../Pictures/college project-bro.png" class="card-img-top" alt="Year">
                <div class="card-body">
                    <h5 class="card-title">Academic Years</h5>
                </div>
            </a>
        </div>
    </div>
    <div class="back-button" onclick="window.location.href='AdminCP.php'">Back</div>

    <script>
        function updateClock() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            if (hours < 10) hours = "0" + hours;
            if (minutes < 10) minutes = "0" + minutes;
            if (seconds < 10) seconds = "0" + seconds;
            document.getElementById('clock').textContent = hours + ":" + minutes + ":" + seconds;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>

</html>
