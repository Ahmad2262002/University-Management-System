<?php
// Ensure no output before this point
include 'connection.php';

// Start the session if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Last request was more than 30 minutes ago
    session_unset();     // Unset $_SESSION variable for the run-time 
    session_destroy();   // Destroy session data in storage
    session_start();     // Restart the session
}

$_SESSION['last_activity'] = time(); // Update last activity time stamp

// Regenerate session ID to prevent fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true); // Regenerate session ID
    $_SESSION['initiated'] = true;
}

// Add your security checks or other code here
?>
