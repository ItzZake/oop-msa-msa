<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$fullname = htmlspecialchars($_POST['fullname'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$redirect = basename($_POST['redirect'] ?? 'login.php');
if (!in_array($redirect, ['login.php', 'register.php'], true)) {
    $redirect = 'login.php';
}

// Validation
if (!$fullname || !$email || !$password) {
    $_SESSION['error'] = 'All fields are required';
    header('Location: ' . $redirect);
    exit;
}

if (strlen($fullname) < 2) {
    $_SESSION['error'] = 'Full name must be at least 2 characters';
    header('Location: ' . $redirect);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address';
    header('Location: ' . $redirect);
    exit;
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = 'Passwords do not match';
    header('Location: ' . $redirect);
    exit;
}

if (strlen($password) < 6) {
    $_SESSION['error'] = 'Password must be at least 6 characters';
    header('Location: ' . $redirect);
    exit;
}

// In production, insert into database
// $hashed = password_hash($password, PASSWORD_DEFAULT);
// $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password_hash, role) VALUES (?, ?, ?, 'parent')");
// $stmt->execute([$fullname, $email, $hashed]);

$redirect = basename($_POST['redirect'] ?? 'login.php');
if (!in_array($redirect, ['login.php', 'register.php'], true)) {
    $redirect = 'login.php';
}

$_SESSION['message'] = 'Account created successfully! Please login.';
header('Location: ' . $redirect);
exit;
?>
