<?php
// FR-01 — Registration with Strategy Pattern
// Handles POST from View/login.php (register tab)
// Uses strategy pattern for role-specific registration logic

session_start();

// Only accept POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/login.php");
    exit;
}

// ── Require strategy classes ──────────────────────────────────────────────────
require_once '../Models/RegistrationContext.php';
require_once '../Models/ParentRegistrationStrategy.php';
require_once '../Models/TeacherRegistrationStrategy.php';

// ── Define variables ──────────────────────────────────────────────────────────
$firstName = $lastName = $email = $password = $confirmPassword = $role = "";
$phoneNumber = $address = $qualifications = $department = "";
$allErrors = [];

// ── Get input values ──────────────────────────────────────────────────────────
$firstName = trim($_POST["firstName"] ?? "");
$lastName = trim($_POST["lastName"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$confirmPassword = $_POST["confirm_password"] ?? "";
$role = trim($_POST["role"] ?? "");
$phoneNumber = trim($_POST["phone_number"] ?? "");
$address = trim($_POST["address"] ?? "");
$qualifications = trim($_POST["qualifications"] ?? "");
$department = trim($_POST["department"] ?? "");

// ── Validate common fields ───────────────────────────────────────────────────
if (empty($firstName)) {
    $allErrors[] = "First name is required.";
} elseif (strlen($firstName) < 2) {
    $allErrors[] = "First name must be at least 2 characters.";
}

if (empty($lastName)) {
    $allErrors[] = "Last name is required.";
} elseif (strlen($lastName) < 2) {
    $allErrors[] = "Last name must be at least 2 characters.";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $allErrors[] = "Please enter a valid email address.";
}

if (empty($password) || strlen($password) < 8) {
    $allErrors[] = "Password must be at least 8 characters.";
}

if ($password !== $confirmPassword) {
    $allErrors[] = "Passwords do not match.";
}

if (empty($role) || !in_array($role, ['parent', 'teacher'])) {
    $allErrors[] = "Please select a valid account type.";
}

// ── If common validation passes, use appropriate strategy ─────────────────────
if (empty($allErrors)) {
    try {
        $registrationContext = null;

        // Create the appropriate strategy based on role
        if ($role === 'parent') {
            $registrationContext = RegistrationContext::createParentStrategy(
                $email,
                $password,
                $firstName,
                $lastName,
                $phoneNumber ?: null,
                $address ?: null
            );
        } elseif ($role === 'teacher') {
            $registrationContext = RegistrationContext::createTeacherStrategy(
                $email,
                $password,
                $firstName,
                $lastName,
                $qualifications ?: null,
                $department ?: null
            );
        }

        if (!$registrationContext) {
            throw new RuntimeException("Could not create registration strategy.");
        }

        // Validate using the strategy
        $strategyErrors = $registrationContext->validate();
        if (!empty($strategyErrors)) {
            $allErrors = array_merge($allErrors, $strategyErrors);
            throw new RuntimeException(implode(" | ", $allErrors));
        }

        // Register using the strategy
        $registrationContext->register($email, $password, $firstName, $lastName);

        // Set success message
        $_SESSION["message"] = "Account created successfully! Please sign in.";
        
        // Redirect based on strategy
        $redirectUrl = $registrationContext->getRedirectUrl();
        header("Location: " . $redirectUrl);
        exit;

    } catch (RuntimeException $e) {
        $_SESSION["error"] = $e->getMessage();
        header("Location: ../View/login.php");
        exit;
    } catch (Exception $e) {
        $_SESSION["error"] = "Registration failed: " . $e->getMessage();
        header("Location: ../View/login.php");
        exit;
    }
}

// ── Return errors to view ─────────────────────────────────────────────────────
$_SESSION["error"] = implode(" | ", $allErrors);
header("Location: ../View/login.php");
exit;
?>