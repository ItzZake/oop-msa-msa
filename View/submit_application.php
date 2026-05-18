<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: application.php');
    exit;
}

// CSRF validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Security validation failed';
    header('Location: application.php');
    exit;
}

// Sanitize inputs
$parent_name = htmlspecialchars($_POST['parent_name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$child_name = htmlspecialchars($_POST['child_name'] ?? '');
$program = htmlspecialchars($_POST['program'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

// Validation
if (!$parent_name || !$email || !$child_name || !$program) {
    $_SESSION['error'] = 'Please fill in all required fields';
    header('Location: application.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address';
    header('Location: application.php');
    exit;
}

if (strlen($parent_name) < 2) {
    $_SESSION['error'] = 'Parent name must be at least 2 characters';
    header('Location: application.php');
    exit;
}

if (strlen($child_name) < 2) {
    $_SESSION['error'] = 'Child name must be at least 2 characters';
    header('Location: application.php');
    exit;
}

// In production, save to database
// $stmt = $pdo->prepare("INSERT INTO applications (parent_name, email, child_name, program, message) VALUES (?, ?, ?, ?, ?)");
// $stmt->execute([$parent_name, $email, $child_name, $program, $message]);

// Send confirmation email (mock)
$to = $email;
$subject = "Program Application Received - Wellucation Nursery";
$email_body = "Dear $parent_name,\n\nThank you for applying to the $program program for $child_name. We will review your application and contact you soon.\n\nBest regards,\nWellucation Nursery Team";
// mail($to, $subject, $email_body);

// Log the application
error_log("Program application submitted: $parent_name - $child_name for $program");

$_SESSION['message'] = "Your application for the $program program has been submitted successfully!";
header('Location: application.php');
exit;
?>
