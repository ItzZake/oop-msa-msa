<?php
// FR-01 — Parent Registration & Profile
// Handles POST from View/login.php (register tab)

session_start();

// Only accept POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/login.php");
    exit;
}

// ── Define variables ──────────────────────────────────────────────────────────
$firstName = $lastName = $email = $password = $confirmPassword = "";
$firstName_err = $lastName_err = $email_err = $password_err = $confirmPassword_err = "";

// ── Validate First Name ───────────────────────────────────────────────────────
$input_firstName = trim($_POST["firstName"] ?? "");
if (empty($input_firstName)) {
    $firstName_err = "Please enter your first name.";
} elseif (!filter_var($input_firstName, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[a-zA-Z\s]+$/"]])) {
    $firstName_err = "First name may only contain letters.";
} elseif (strlen($input_firstName) < 2) {
    $firstName_err = "First name must be at least 2 characters.";
} else {
    $firstName = $input_firstName;
}

// ── Validate Last Name ────────────────────────────────────────────────────────
$input_lastName = trim($_POST["lastName"] ?? "");
if (empty($input_lastName)) {
    $lastName_err = "Please enter your last name.";
} elseif (!filter_var($input_lastName, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[a-zA-Z\s]+$/"]])) {
    $lastName_err = "Last name may only contain letters.";
} else {
    $lastName = $input_lastName;
}

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
    $password_err = "Please enter a password.";
} elseif (strlen($input_password) < 8) {
    $password_err = "Password must be at least 8 characters.";
} else {
    $password = $input_password;
}

// ── Validate Confirm Password ─────────────────────────────────────────────────
$input_confirm = $_POST["confirm_password"] ?? "";
if (empty($input_confirm)) {
    $confirmPassword_err = "Please confirm your password.";
} elseif ($input_confirm !== $password) {
    $confirmPassword_err = "Passwords do not match.";
} else {
    $confirmPassword = $input_confirm;
}

// ── If no errors, call AuthService to register ───────────────────────────────
if (empty($firstName_err) && empty($lastName_err) && empty($email_err)
    && empty($password_err) && empty($confirmPassword_err)) {

    require_once '../Models/AuthService.php';

    $authService = new AuthService();

    try {
        $authService->register($email, $password, "parent", $firstName, $lastName);
        $_SESSION["message"] = "Account created successfully! Please sign in.";
        header("Location: ../View/login.php");
        exit;
    } catch (RuntimeException $e) {
        // Email already registered or DB error
        $_SESSION["error"] = $e->getMessage();
        header("Location: ../View/login.php");
        exit;
    }
}

// ── Return errors to view ────────────────────────────────────────────────────
$_SESSION["error"] = implode(" | ", array_filter([
    $firstName_err, $lastName_err, $email_err, $password_err, $confirmPassword_err
]));
header("Location: ../View/login.php");
exit;
?>