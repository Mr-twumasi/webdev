<?php
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get and sanitize email
$email = sanitize_input($_POST['ne'] ?? $_POST['email'] ?? '');

// Validate email
if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email address is required']);
    exit;
}

if (!is_valid_email($email)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

// Check if email already exists
global $conn;
$stmt = $conn->prepare("SELECT id FROM newsletter WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'This email is already subscribed to our newsletter']);
    $stmt->close();
    exit;
}
$stmt->close();

// Insert into database
$stmt = $conn->prepare("INSERT INTO newsletter (email) VALUES (?)");
$stmt->bind_param("s", $email);

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
} {
    // Replace all the HTML output here with this:
    header("Location: thank_you.html");
    exit();
}
?>
