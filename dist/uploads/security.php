// security.php
<?php
include "connection.php";

// Check if session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if session is not registered or is empty
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("location:LogIn.php?error=403");
    exit(); // Ensure the script stops executing
} else {
    // Regenerate session ID to prevent session fixation attacks
    session_regenerate_id(true);

    // Use htmlspecialchars to prevent XSS attacks
    echo 'Welcome, ' . htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') . "! <br>";
}
?>
