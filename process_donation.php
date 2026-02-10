<?php
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get and sanitize form data
$amount = sanitize_input($_POST['amount'] ?? '');
$method = sanitize_input($_POST['method'] ?? '');

// Validate required fields
if (empty($amount) || empty($method)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

// Validate amount
if (!is_numeric($amount) || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid donation amount']);
    exit;
}

// Insert into database
global $conn;
$stmt = $conn->prepare("INSERT INTO donations (amount, payment_method) VALUES (?, ?)");
$stmt->bind_param("ds", $amount, $method);

if ($stmt->execute()) {
    // Send confirmation email (you can add donor email if collected)
    $to = 'info@wordlibertychapel.org';
    $subject = 'New Donation Received - Word Liberty Chapel';
    $email_message = "
    <html>
    <head>
        <title>New Donation Received</title>
    </head>
    <body>
        <h2>New Donation Received</h2>
        <p><strong>Amount:</strong> GHC $amount</p>
        <p><strong>Payment Method:</strong> $method</p>
        <p><strong>Date:</strong> " . date('Y-m-d H:i:s') . "</p>
        <hr>
        <p>Please process this donation according to the selected payment method.</p>
    </body>
    </html>
    ";

    send_email($to, $subject, $email_message);

    // Log activity
    log_activity('donation', "Donation of GHC $amount via $method");

    echo json_encode(['success' => true, 'message' => "Thank you for your generous donation of GHC $amount! We will process your $method payment shortly."]);
} else {
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error processing your donation. Please try again.']);
}

$stmt->close();
$conn->close();
?>
