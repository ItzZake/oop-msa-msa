<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

// In production, fetch from database
// For demo purposes, hardcoded credentials
$valid_users = [
    'admin@wellucation.edu' => ['password' => 'admin123', 'role' => 'admin', 'name' => 'Admin User'],
    'teacher@wellucation.edu' => ['password' => 'teacher123', 'role' => 'teacher', 'name' => 'Teacher User'],
    'parent@wellucation.edu' => ['password' => 'parent123', 'role' => 'parent', 'name' => 'Parent User']
];

if (isset($valid_users[$email]) && $valid_users[$email]['password'] === $password) {
    $_SESSION['user_id'] = $email;
    $_SESSION['role'] = $valid_users[$email]['role'];
    $_SESSION['name'] = $valid_users[$email]['name'];
    $_SESSION['message'] = 'Login successful! Welcome back.';
    
    // Redirect based on role
    if ($_SESSION['role'] == 'admin') {
        header('Location: dashboard.php');
    } elseif ($_SESSION['role'] == 'teacher') {
        header('Location: attendance.php');
    } else {
        header('Location: profiles.php');
    }
    exit;
} else {
    $_SESSION['error'] = 'Invalid email or password';
    header('Location: login.php');
    exit;
}
?>
