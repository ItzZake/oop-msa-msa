<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

// CSRF validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Security validation failed';
    header('Location: contact.php');
    exit;
}

// Sanitize inputs
$name = htmlspecialchars($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars($_POST['phone'] ?? '');
$user_type = htmlspecialchars($_POST['user_type'] ?? '');
$subject = htmlspecialchars($_POST['subject'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

// Validation
if (!$name || !$email || !$subject || !$message) {
    $_SESSION['error'] = 'Please fill in all required fields';
    header('Location: contact.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address';
    header('Location: contact.php');
    exit;
}

if (strlen($name) < 2) {
    $_SESSION['error'] = 'Name must be at least 2 characters';
    header('Location: contact.php');
    exit;
}

if (strlen($subject) < 3) {
    $_SESSION['error'] = 'Subject must be at least 3 characters';
    header('Location: contact.php');
    exit;
}

// In production, send email or save to database
$to = "hello@wellucation.edu";
$email_subject = "Contact Form: $subject";
$email_body = "Name: $name\nEmail: $email\nPhone: $phone\nUser Type: $user_type\n\nMessage:\n$message";
$headers = "From: $email\r\nReply-To: $email";

// mail($to, $email_subject, $email_body, $headers);

// Save to database (mock)
// $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, user_type, subject, message) VALUES (?, ?, ?, ?, ?, ?)");
// $stmt->execute([$name, $email, $phone, $user_type, $subject, $message]);

// Log the message
error_log("Contact form submitted by $name ($email) with subject: $subject");

$_SESSION['message'] = 'Thank you for your message! We will reply within 24 hours.';
header('Location: contact.php');
exit;
?>
