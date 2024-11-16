<?php
include ('connection.php');

// Define the SQL query
$sql = "SELECT students_id, name, email, phone_number, photo_url, YearID, major  FROM student";

// Execute the query
$result = $con->query($sql);

// Check if the query was successful
if ($result === false) {
    echo "Error: Could not execute the query: " . $con->error;
} else {
    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $student_id = isset($row['students_id']) ? $row['students_id'] : '';
            $name = isset($row['name']) ? $row['name'] : '';
            $email = isset($row['email']) ? $row['email'] : '';
            $phone_number = isset($row['phone_number']) ? $row['phone_number'] : '';
            $photo_url = isset($row['photo_url']) ? $row['photo_url'] : '';
            $year_id = isset($row['year_id']) ? $row['year_id'] : '';
            $dept_id = isset($row['dept_id']) ? $row['dept_id'] : '';
            
            echo "<tr>
                    <td>{$student_id}</td>
                    <td>{$name}</td>
                    <td>{$email}</td>
                    <td>{$phone_number}</td>
                    <td><img src='{$photo_url}' width='50'></td>
                    <td>{$year_id}</td>
                    <td>{$dept_id}</td>
                    <td>
                        <a href='update_student.php?students_id={$student_id}' class='btn btn-primary'>Edit</a>
                        <form action='student_action.php' method='post' style='display:inline;'>
                            <input type='hidden' name='action' value='delete'>
                            <input type='hidden' name='students_id' value='{$student_id}'>
                            <button type='submit' class='btn btn-danger'>Delete</button>
                        </form>
                    </td>
                </tr>";
        }
    } else {
        echo "0 results";
    }
}

// Close the connection
$con->close();
?>
