<?php
session_start();
require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Parent.php';
require_once __DIR__ . '/../Models/Child.php';
require_once __DIR__ . '/../Models/Application.php';

// Check authentication - must be logged in and be a parent
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'You must be logged in to enroll';
    header('Location: enroll.php');
    exit;
}

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Parent') {
    $_SESSION['error'] = 'Only parents can submit enrollment applications';
    header('Location: enroll.php');
    exit;
}

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

// Get logged-in parent
$parentId = $_SESSION['user_id'];
$db = Database::getInstance();

// Verify parent exists in database
$parentCheck = $db->fetchOne("SELECT userID FROM parent WHERE userID = ?", [$parentId]);
if (!$parentCheck) {
    $_SESSION['error'] = 'Parent record not found. Please contact support.';
    header('Location: enroll.php');
    exit;
}

// Sanitize inputs
$child_name = htmlspecialchars($_POST['child_name'] ?? '');
$child_dob = $_POST['child_dob'] ?? '';
$child_gender = htmlspecialchars($_POST['child_gender'] ?? '');
$program = htmlspecialchars($_POST['program'] ?? '');
$start_date = $_POST['start_date'] ?? '';
$emergency_name = htmlspecialchars($_POST['emergency_name'] ?? '');
$emergency_phone = htmlspecialchars($_POST['emergency_phone'] ?? '');
$medical_info = htmlspecialchars($_POST['medical_info'] ?? '');
$comments = htmlspecialchars($_POST['comments'] ?? '');

// Validation - child required fields
$required = [$child_name, $child_dob, $child_gender, $program, $start_date, $emergency_name, $emergency_phone];
foreach ($required as $field) {
    if (empty($field)) {
        $_SESSION['error'] = 'Please fill in all required child fields';
        header('Location: enroll.php');
        exit;
    }
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

try {
    // Create child record
    $sql_child = "INSERT INTO child (parentID, name, dateOfBirth, gender, emergencyContact, medicalNotes, enrollmentStatus)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_child = $db->query($sql_child, [
        $parentId,
        $child_name,
        $child_dob,
        $child_gender,
        $emergency_name . ' - ' . $emergency_phone,
        $medical_info,
        'Pending'
    ]);

    if (!$stmt_child || $stmt_child->rowCount() === 0) {
        throw new Exception('Failed to create child record');
    }

    // Get the newly created child ID
    $childId = $db->query("SELECT LAST_INSERT_ID() as id", [])->fetch(PDO::FETCH_ASSOC)['id'];

    // Submit application for the child
    $sql_app = "INSERT INTO application (parentID, childID, status, submittedAt, reviewedAt, rejectionReason, documents)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $documents = json_encode([
        'program' => $program,
        'startDate' => $start_date,
        'comments' => $comments,
        'medicalInfo' => $medical_info
    ]);

    $stmt_app = $db->query($sql_app, [
        $parentId,
        $childId,
        'Pending',
        date('Y-m-d H:i:s'),
        null,
        null,
        $documents
    ]);

    if (!$stmt_app || $stmt_app->rowCount() === 0) {
        throw new Exception('Failed to create application');
    }

    // Log success
    error_log("Enrollment submitted - Parent: $parentId, Child: $child_name ($childId), Program: $program");

    $_SESSION['message'] = "Enrollment application submitted successfully! We'll review your application for $child_name and contact you within 48 hours.";
    
} catch (Exception $e) {
    error_log("Enrollment error: " . $e->getMessage());
    $_SESSION['error'] = 'An error occurred while processing your enrollment. Please try again.';
}

header('Location: enroll.php');
exit;
?>
