<?php
// FR-02 — Login & Secure Sessions
// Handles POST from View/login.php (login tab)
// Uses AuthService which calls SessionManager::Regenerate() to prevent fixation

session_start();

// Only accept POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/login.php");
    exit;
}

// ── Define variables ──────────────────────────────────────────────────────────
$email = $password = "";
$email_err = $password_err = "";

// ── Validate Email ────────────────────────────────────────────────────────────
$input_email = trim($_POST["email"] ?? "");
if (empty($input_email)) {
    $email_err = "Please enter your email address.";
} elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
    $email_err = "Please enter a valid email address.";
} else {
    $email = $input_email;
}

// ── Validate Password ─────────────────────────────────────────────────────────
$input_password = $_POST["password"] ?? "";
if (empty($input_password)) {
    $password_err = "Please enter your password.";
} else {
    $password = $input_password;
}

// ── If no errors, attempt login ───────────────────────────────────────────────
if (empty($email_err) && empty($password_err)) {

    require_once '../Models/AuthService.php';

    $authService = new AuthService();
    $user = $authService->login($email, $password);

    if ($user) {
        // FR-03: redirect based on role and login status
        $role = strtolower($_SESSION["user_role"] ?? "");
        $lastLoginAt = $user->getLastLoginAt();
        
        // Check if parent is logging in for the first time
        if ($role === "parent" && empty($lastLoginAt)) {
            // First time login for parent - redirect to enroll page
            header("Location: ../View/Index.php");
        } elseif ($role === "admin") {
            header("Location: ../View/dashboard.php");
        } elseif ($role === "teacher") {
            header("Location: ../View/Profiles.php");
        } else {
            // Parent returning user or other roles
            header("Location: ../View/Index.php");
        }
        exit;
    } else {
        $_SESSION["error"] = "Invalid email or password.";
        header("Location: ../View/login.php");
        exit;
    }
}

// ── Return errors to view ─────────────────────────────────────────────────────
$_SESSION["error"] = implode(" | ", array_filter([$email_err, $password_err]));
header("Location: ../View/login.php");
exit;
?>