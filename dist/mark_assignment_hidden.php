<?php
session_start();

// Retrieve assignment_id from POST data
$data = json_decode(file_get_contents("php://input"), true);
$assignmentId = $data['assignment_id'] ?? null;

// Initialize hidden assignments array in session if not already set
if (!isset($_SESSION['hidden_assignments'])) {
    $_SESSION['hidden_assignments'] = [];
}

// Add assignment_id to hidden assignments array if not already present
if ($assignmentId !== null && !in_array($assignmentId, $_SESSION['hidden_assignments'])) {
    $_SESSION['hidden_assignments'][] = $assignmentId;
}

// Send response
http_response_code(200);

if ($assignmentId !== null) {
    // Mark assignment as hidden
    error_log("Marked assignment_id $assignmentId as hidden");
} else {
    error_log("No assignment_id provided");
}
?>
