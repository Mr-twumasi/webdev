<?php
require_once __DIR__.'/../config/database.php';

// Sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Send email function
function send_email($to, $subject, $message, $headers = '') {
    $headers = $headers ?: "From: noreply@wordlibertychapel.org\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return @mail($to, $subject, $message, $headers);
}

// Log activity
function log_activity($action, $details = '') {
    global $conn;
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $stmt = $conn->prepare("INSERT INTO activity_log (action, details, ip_address, user_agent) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $action, $details, $ip, $user_agent);
    $stmt->execute();
    $stmt->close();
}

// Get all contacts
function get_contacts() {
    global $conn;
    $result = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC");
    return $result;
}

// Get all newsletter subscribers
function get_newsletter_subscribers() {
    global $conn;
    $result = $conn->query("SELECT * FROM newsletter ORDER BY date_subscribed DESC");
    return $result;
}

// Get all donations
function get_donations() {
    global $conn;
    $result = $conn->query("SELECT * FROM donations ORDER BY created_at DESC");
    return $result;
}
?>
