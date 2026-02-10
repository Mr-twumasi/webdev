<?php
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get and sanitize form data
$name = sanitize_input($_POST['name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$message = sanitize_input($_POST['message'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

// Validate email
if (!is_valid_email($email)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

// Insert into database
global $conn;
$stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $phone, $message);

if ($stmt->execute()) 
    // Send email notification to church staff
if ($stmt->execute()) 
    // Send welcome email
    try {
    // This is your original Line 45, but now protected by "try"
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Thank you for subscribing!"]);
    }
}   catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        // Redirect back to index with a 'duplicate' flag
        header("Location: index.html?status=duplicate");
    } else {
        // Redirect back with a generic error flag
        header("Location: index.html?status=error");
    }
    exit(); 

    {
    // Replace all the HTML output here with this:
    header("Location: thank_you.html");
    exit();
}
}
?>
