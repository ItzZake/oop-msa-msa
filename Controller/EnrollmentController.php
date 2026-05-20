<?php
session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Child.php';
require_once __DIR__ . '/../Models/Course.php';
require_once __DIR__ . '/../Models/Enrollment.php';
require_once __DIR__ . '/../Models/Waitlist.php';
require_once __DIR__ . '/../Models/AuthService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../View/enroll.php');
    exit;
}

$authService = new AuthService();
$user = $authService->getAuthenticatedUser();
if (!$user || strtolower($user->getRole() ?? '') !== 'parent') {
    $_SESSION['enroll_error'] = 'Please sign in as a parent before enrolling.';
    header('Location: ../View/login.php');
    exit;
}

$child_id  = (int) ($_POST['child_id']  ?? 0);   // 0 means "create new child"
$course_id = (int) ($_POST['course_id'] ?? 0);
$newChildName = trim($_POST['new_child_name'] ?? '');
$newChildDob = trim($_POST['new_child_dob'] ?? '');
$newChildGender = trim($_POST['new_child_gender'] ?? '');
$newChildAllergies = trim($_POST['new_child_allergies'] ?? '');
$newChildMedicalNotes = trim($_POST['new_child_medical_notes'] ?? '');
$newChildEmergencyContact = trim($_POST['new_child_emergency_contact'] ?? '');

if ($course_id <= 0) {
    $_SESSION['enroll_error'] = 'Please select a program.';
    header('Location: ../View/enroll.php');
    exit;
}

$parentRow = Database::getInstance()->fetchOne('SELECT parentID FROM Parent WHERE userID = ?', [$user->getId()]);
if (!$parentRow) {
    // If the parent profile row is missing, create it as a fallback.
    Database::getInstance()->query(
        'INSERT INTO Parent (userID, phone, address, notifPreferences) VALUES (?, ?, ?, ?)',
        [$user->getId(), null, null, null]
    );
    $parentRow = Database::getInstance()->fetchOne('SELECT parentID FROM Parent WHERE userID = ?', [$user->getId()]);
}

if (!$parentRow) {
    $_SESSION['enroll_error'] = 'Parent profile not found. Please complete your profile first.';
    header('Location: ../View/enroll.php');
    exit;
}

$parentId = (int) $parentRow['parentID'];
$Course = new Course();
$course = $Course->GetCourseById($course_id);

if (!$course) {
    $_SESSION['enroll_error'] = 'Selected program is invalid.';
    header('Location: ../View/enroll.php');
    exit;
}

if ($child_id <= 0) {
    if (empty($newChildName) || empty($newChildDob) || empty($newChildGender) || empty($newChildEmergencyContact)) {
        $_SESSION['enroll_error'] = 'Please provide the new child details to enroll.';
        header('Location: ../View/enroll.php');
        exit;
    }

    if (!in_array($newChildGender, ['M', 'F'], true)) {
        $_SESSION['enroll_error'] = 'Please select a valid child gender.';
        header('Location: ../View/enroll.php');
        exit;
    }

    $newChildData = [
        'parentID' => $parentId,
        'Name' => $newChildName,
        'DateOfBirth' => $newChildDob,
        'Gender' => $newChildGender,
        'allergies' => $newChildAllergies ?: null,
        'MedicalNotes' => $newChildMedicalNotes ?: null,
        'EmergencyContact' => $newChildEmergencyContact,
        'enrollmentStatus' => 'Pending',
    ];

    $newChildId = Child::AddChild($newChildData);
    if (!$newChildId) {
        $_SESSION['enroll_error'] = 'Could not create the child profile. Please try again.';
        header('Location: ../View/enroll.php');
        exit;
    }

    $child_id = (int) $newChildId;
}

$Child = new Child();
$child = $Child->GetChildById($child_id);

if (!$child) {
    $_SESSION['enroll_error'] = 'Invalid child selection.';
    header('Location: ../View/enroll.php');
    exit;
}

if ($child->GetParentID() !== $parentId) {
    $_SESSION['enroll_error'] = 'Selected child does not belong to your account.';
    header('Location: ../View/enroll.php');
    exit;
}

if ($child->GetAge() < $course->GetMinAge() || $child->GetAge() > $course->GetMaxAge()) {
    $_SESSION['enroll_error'] = 'This child is not age-eligible for the chosen program.';
    header('Location: ../View/enroll.php');
    exit;
}

$Enrollment = new Enrollment();

// Prevent duplicate enrollment / waitlist entries.
if ($Enrollment->IsAlreadyEnrolled($child_id, $course_id)) {
    $_SESSION['enroll_error'] = 'This child is already enrolled in the selected program.';
    header('Location: ../View/enroll.php');
    exit;
}

$availableSeats = $course->GetCurrentEnrollment($course_id);
$maxCapacity = $course->GetMaxCapacity($course_id);

if ($availableSeats < $maxCapacity) {
    if ($Enrollment->Enroll($child_id, $course_id)) {
        $_SESSION['enroll_message'] = 'Enrollment successful!';
    } else {
        $_SESSION['enroll_error'] = 'Could not save enrollment. Please try again.';
    }
    header('Location: ../View/enroll.php');
    exit;
}

$Waitlist = new Waitlist();
if ($Waitlist->AssignWaitlist($course_id, $child_id, $parentId)) {
    $_SESSION['enroll_message'] = 'Program is currently full. Your child has been added to the waitlist.';
} else {
    $_SESSION['enroll_error'] = 'Could not add to waitlist. Please contact support.';
}

header('Location: ../View/enroll.php');
exit;