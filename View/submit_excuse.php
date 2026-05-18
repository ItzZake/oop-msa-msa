<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: excuse.php');
    exit;
}

// CSRF validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Security validation failed';
    header('Location: excuse.php');
    exit;
}

// Sanitize inputs
$child_name = htmlspecialchars($_POST['child_name'] ?? '');
$absence_date = $_POST['absence_date'] ?? '';
$reason = htmlspecialchars($_POST['reason'] ?? '');
$details = htmlspecialchars($_POST['details'] ?? '');

// Validation
if (!$child_name || !$absence_date || !$reason) {
    $_SESSION['error'] = 'Please fill in all required fields';
    header('Location: excuse.php');
    exit;
}

if (strlen($child_name) < 2) {
    $_SESSION['error'] = 'Child name must be at least 2 characters';
    header('Location: excuse.php');
    exit;
}

// Validate date
$date = DateTime::createFromFormat('Y-m-d', $absence_date);
if (!$date) {
    $_SESSION['error'] = 'Invalid date format';
    header('Location: excuse.php');
    exit;
}

// In production, save to database
// $stmt = $pdo->prepare("INSERT INTO excuses (child_name, absence_date, reason, details) VALUES (?, ?, ?, ?)");
// $stmt->execute([$child_name, $absence_date, $reason, $details]);

// Log the excuse
error_log("Absence excuse submitted for $child_name on $absence_date: $reason");

$_SESSION['message'] = "Absence excuse for $child_name has been submitted successfully!";
header('Location: excuse.php');
exit;
?>
