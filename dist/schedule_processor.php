<?php
// Connect to database
// ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Capture form data
  $courseID = $_POST['courseID'];
  // Repeat for other fields

  // Prepare SQL statement
  $sql = "INSERT INTO schedule (CourseID, TeacherID, ...) VALUES (?, ?, ...)";
  
  // Execute SQL statement
  // ...
}

?>
