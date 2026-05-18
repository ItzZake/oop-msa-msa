<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: enroll.php');
    exit;
}

// CSRF validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Security validation failed';
    header('Location: enroll.php');
    exit;
}

// Sanitize inputs
$parent_name = htmlspecialchars($_POST['parent_name'] ?? '');
$parent_email = filter_var($_POST['parent_email'] ?? '', FILTER_SANITIZE_EMAIL);
$parent_phone = htmlspecialchars($_POST['parent_phone'] ?? '');
$address = htmlspecialchars($_POST['address'] ?? '');
$child_name = htmlspecialchars($_POST['child_name'] ?? '');
$child_dob = $_POST['child_dob'] ?? '';
$program = htmlspecialchars($_POST['program'] ?? '');
$start_date = $_POST['start_date'] ?? '';
$emergency_name = htmlspecialchars($_POST['emergency_name'] ?? '');
$emergency_phone = htmlspecialchars($_POST['emergency_phone'] ?? '');
$medical_info = htmlspecialchars($_POST['medical_info'] ?? '');
$comments = htmlspecialchars($_POST['comments'] ?? '');

// Validation
$required = [$parent_name, $parent_email, $parent_phone, $address, $child_name, $child_dob, $program, $start_date, $emergency_name, $emergency_phone];
foreach ($required as $field) {
    if (empty($field)) {
        $_SESSION['error'] = 'Please fill in all required fields';
        header('Location: enroll.php');
        exit;
    }
}

if (!filter_var($parent_email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address';
    header('Location: enroll.php');
    exit;
}

if (strlen($parent_name) < 2) {
    $_SESSION['error'] = 'Parent name must be at least 2 characters';
    header('Location: enroll.php');
    exit;
}

if (strlen($child_name) < 2) {
    $_SESSION['error'] = 'Child name must be at least 2 characters';
    header('Location: enroll.php');
    exit;
}

// Validate dates
$dob = DateTime::createFromFormat('Y-m-d', $child_dob);
$start = DateTime::createFromFormat('Y-m-d', $start_date);

if (!$dob || !$start) {
    $_SESSION['error'] = 'Invalid date format';
    header('Location: enroll.php');
    exit;
}

if ($dob >= $start) {
    $_SESSION['error'] = 'Start date must be after child\'s date of birth';
    header('Location: enroll.php');
    exit;
}

// Generate application ID
$app_id = 'APP-' . date('Y') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

// In production, save to database
// $stmt = $pdo->prepare("INSERT INTO enrollments (parent_name, parent_email, parent_phone, address, child_name, child_dob, program, start_date, emergency_name, emergency_phone, medical_info, comments, app_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
// $stmt->execute([$parent_name, $parent_email, $parent_phone, $address, $child_name, $child_dob, $program, $start_date, $emergency_name, $emergency_phone, $medical_info, $comments, $app_id]);

// Send confirmation email (mock)
$to = $parent_email;
$subject = "Enrollment Application Received - Wellucation Nursery";
$message = "Dear $parent_name,\n\nThank you for submitting an enrollment application for $child_name. Your application ID is $app_id.\n\nWe will review your application and contact you within 48 hours.\n\nBest regards,\nWellucation Nursery Team";
// mail($to, $subject, $message);

// Log the enrollment
error_log("Enrollment application submitted: $app_id - $child_name by $parent_email");

$_SESSION['message'] = "Enrollment application submitted successfully! Your application ID is $app_id. We'll contact you within 48 hours.";
header('Location: enroll.php');
exit;
?>
