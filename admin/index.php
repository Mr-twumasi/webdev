<?php
session_start();

// Simple authentication (in production, use proper authentication)
$admin_username = getenv('ADMIN_USERNAME') ?: 'admin';
$admin_password = getenv('ADMIN_PASSWORD') ?: 'securepassword';

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === $admin_username && $password === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
        } else {
            $error = 'Invalid credentials';
        }
    }

    if (!isset($_SESSION['admin_logged_in'])) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login - Word Liberty Chapel</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                .login-form { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
                input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
                button { width: 100%; padding: 10px; background: #2f502c; color: white; border: none; border-radius: 4px; cursor: pointer; }
                button:hover { background: #1e3a1e; }
                .error { color: red; text-align: center; }
            </style>
        </head>
        <body>
            <form method="post" class="login-form">
                <h2>Admin Login</h2>
                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </body>
        </html>
        <?php
        exit;
    }
}

require_once '../includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Word Liberty Chapel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        .header { background: #2f502c; color: white; padding: 20px; margin: -20px -20px 20px -20px; }
        .nav { margin-bottom: 20px; }
        .nav a { padding: 10px 15px; background: #3498db; color: white; text-decoration: none; margin-right: 10px; border-radius: 4px; }
        .nav a:hover { background: #2980b9; }
        table { width: 100%; border-collapse: collapse; background: white; margin-bottom: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f8f8; }
        .logout { float: right; }
        .logout a { color: white; text-decoration: none; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Word Liberty Chapel Admin Panel</h1>
        <div class="logout"><a href="?logout=1">Logout</a></div>
    </div>

    <div class="nav">
        <a href="#contacts">Contacts</a>
        <a href="#newsletter">Newsletter</a>
        <a href="#donations">Donations</a>
    </div>

    <h2 id="contacts">Contact Form Submissions</h2>
    <table>
        <tr><th>Name</th><th>Email</th><th>Phone</th><th>Message</th><th>Date</th></tr>
        <?php
        $contacts = get_contacts();
        while ($row = $contacts->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars(substr($row['message'], 0, 100)) . "...</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2 id="newsletter">Newsletter Subscribers</h2>
    <table>
        <tr><th>Email</th><th>Date Subscribed</th></tr>
        <?php
        $subscribers = get_newsletter_subscribers();
        while ($row = $subscribers->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . $row['date_subscribed'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2 id="donations">Donations</h2>
    <table>
        <tr><th>Amount</th><th>Payment Method</th><th>Date</th></tr>
        <?php
        $donations = get_donations();
        while ($row = $donations->fetch_assoc()) {
            echo "<tr>";
            echo "<td>GHC " . number_format($row['amount'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($row['payment_method']) . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
