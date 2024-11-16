<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../Pictures/Logo2-removebg.png" type="image/png"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Year Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #333;
            color: #fff;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #444;
            border-radius: 10px;
            box-shadow: 0 0 10px gold;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #555;
            color: #fff;
        }

        .form-group button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: gold;
            color: #000;
            cursor: pointer;
            font-family: 'Merriweather', serif;
        }

        .form-group button:disabled {
            background-color: #888;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1c1c1e;
            padding: 10px 20px;
            margin-bottom: 20px;
        }

        .navbar a {
            background-color: #FFD700;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            text-decoration: none;
            box-shadow: 0px 10px 20px rgba(255, 215, 0, 0.4);
            transition: transform 0.3s;
        }

        .navbar a:hover {
            background-color: #b8860b;
            transform: scale(1.05);
        }

        h1 {
            text-align: center;
            color: gold;
            font-family: 'Merriweather', serif;
            margin-bottom: 30px;
        }

        h2 {
            color: gold;
            font-family: 'Merriweather', serif;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="Settings.php">Back</a>
    </div>
    <div class="container">
        <h1>Academic Year Management</h1>
        <form id="academicYearForm" action="academicYearAction.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="start-date">Start Date</label>
                <input type="date" id="start-date" name="start-date">
            </div>
            <div class="form-group">
                <label for="end-date">End Date</label>
                <input type="date" id="end-date" name="end-date">
            </div>
            <div class="form-group">
                <button type="button" id="add-year-btn" name="add-year">Add Academic Year</button>
            </div>
            <div class="form-group">
                <label for="year_id">Academic Year</label>
                <?php
                include('connection.php');

                // Get academic years without associated data
                $sql = "SELECT a.YearID, a.startdate, a.enddate
                        FROM academicyear a
                        LEFT JOIN semister s ON a.YearID = s.YearID
                        LEFT JOIN course c ON a.YearID = c.YearID
                        LEFT JOIN schedule sc ON a.YearID = sc.YearID
                        LEFT JOIN exam e ON a.YearID = e.YearID
                        LEFT JOIN mark m ON a.YearID = m.YearID
                        LEFT JOIN student st ON a.YearID = st.YearID
                        LEFT JOIN additional_fees af ON a.YearID = af.YearID
                        LEFT JOIN enrollment en ON a.YearID = en.YearID
                        LEFT JOIN teaching t ON a.YearID = t.YearID
                        LEFT JOIN sections sec ON a.YearID = sec.YearID
                        LEFT JOIN lectures l ON a.YearID = l.YearID
                        LEFT JOIN lb_payment lb ON a.YearID = lb.YearID
                        LEFT JOIN registration r ON a.YearID = r.YearID
                        LEFT JOIN admin ad ON a.YearID = ad.YearID
                        LEFT JOIN classes cl ON a.YearID = cl.YearID
                        LEFT JOIN teachers th ON a.YearID = th.YearID
                        WHERE s.YearID IS NULL AND
                              c.YearID IS NULL AND
                              sc.YearID IS NULL AND
                              e.YearID IS NULL AND
                              m.YearID IS NULL AND
                              st.YearID IS NULL AND
                              af.YearID IS NULL AND
                              en.YearID IS NULL AND
                              t.YearID IS NULL AND
                              sec.YearID IS NULL AND
                              l.YearID IS NULL AND
                              lb.YearID IS NULL AND
                              r.YearID IS NULL AND
                              ad.YearID IS NULL AND
                              cl.YearID IS NULL AND
                              th.YearID IS NULL
                        ORDER BY a.startdate";
                
                $resultAcademic = mysqli_query($con, $sql);
                
                if ($resultAcademic && mysqli_num_rows($resultAcademic) > 0) {
                    echo '<select name="yearID" id="year_id">';
                    echo '<option value="">Select Year</option>';
                    while ($row = mysqli_fetch_assoc($resultAcademic)) {
                        $YearID = htmlspecialchars($row['YearID']);
                        $startDate = htmlspecialchars($row['startdate']);
                        $endDate = htmlspecialchars($row['enddate']);
                        echo "<option value=\"$YearID\">$startDate | $endDate</option>";
                    }
                    echo '</select>';
                } else {
                    echo '<p>No academic years available for deletion.</p>';
                }
                ?>
            </div>
            <div class="form-group">
                <button type="submit" name="delete-year" onclick="return confirmDelete()">Delete Academic Year</button>
                <button type="button" id="view-year">View Academic Year</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('view-year').addEventListener('click', function () {
            const yearSelect = document.getElementById('year_id');
            const yearId = yearSelect.value;
            if (!yearId) {
                alert('Please select a year.');
                return;
            }

            fetch(`academicYearAction.php?year_id=${yearId}`)
                .then(response => response.json())
                .then(data => {
                    alert(`Start Date: ${data.startdate}, End Date: ${data.enddate}`);
                });
        });

        document.getElementById('add-year-btn').addEventListener('click', function () {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            if (!startDate || !endDate) {
                alert('Please set both start and end dates.');
                return;
            }

            const formData = new FormData();
            formData.append('add-year', 'true');
            formData.append('start-date', startDate);
            formData.append('end-date', endDate);

            fetch('academicYearAction.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                window.location.href = 'academicYear.php';
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        function confirmDelete() {
            const yearSelect = document.getElementById('year_id');
            const yearId = yearSelect.value;
            if (!yearId) {
                alert('Please select a year.');
                return false;
            }

            return confirm('Are you sure you want to delete this academic year?');
        }
    </script>
</body>
</html>
