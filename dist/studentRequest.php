<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connection.php';

    $student_id = $_POST["student_id"];
    $major = $_POST["major"];
    $phone_number = $_POST["phone_number"];
    $email = $_POST["email"];
    $description = $_POST["description"];

    $sql = "INSERT INTO request (student_id, major, phone_number, email, description) VALUES ('$student_id', '$major', '$phone_number', '$email', '$description')";

    if ($con->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }

    $con->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            border: 2px solid #A51C30; /* Harvard Crimson */
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
            border-radius: 15px;
        }
        .popup.show {
            display: block;
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .overlay.show {
            display: block;
        }
        .btn-primary {
            background-color: #A51C30; /* Harvard Crimson */
            border-color: #A51C30;
        }
        .btn-primary:hover {
            background-color: #8B1724;
            border-color: #8B1724;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Student Request Form</h1>
        <form id="studentRequestForm" method="post">
            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" required>
            </div>
            <div class="mb-3">
                <label for="major" class="form-label">Major</label>
                <input type="text" class="form-control" id="major" name="major" required>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <p>Your request has been submitted!</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('studentRequestForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('studentRequest.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text())
              .then(data => {
                document.getElementById('popup').classList.add('show');
                document.getElementById('overlay').classList.add('show');
                setTimeout(() => {
                    window.location.href = 'StudentLogin.php';
                }, 2000);
              });
        });
    </script>
</body>
</html>
