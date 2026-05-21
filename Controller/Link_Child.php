<?php
// FR-04 — Child Profile Linking
// Handles POST from View/profiles.php (add-child form)
// Parent links one or more children: DOB, allergies, medical notes stored in Child table

$allowed_roles = ["parent"];
require_once __DIR__ . '/Guard.php';   // FR-03 gate

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/profiles.php");
    exit;
}

// ── Define variables ──────────────────────────────────────────────────────────
$childName = $dateOfBirth = $gender = $allergies = $medicalNotes = $emergencyContact = "";
$childName_err = $dateOfBirth_err = $gender_err = "";

// ── Validate Child Name ───────────────────────────────────────────────────────
$input_childName = trim($_POST["child_name"] ?? "");
if (empty($input_childName)) {
    $childName_err = "Please enter the child's name.";
} elseif (strlen($input_childName) < 2) {
    $childName_err = "Child name must be at least 2 characters.";
} else {
    $childName = htmlspecialchars($input_childName);
}

// ── Validate Date of Birth ────────────────────────────────────────────────────
$input_dob = trim($_POST["date_of_birth"] ?? "");
if (empty($input_dob)) {
    $dateOfBirth_err = "Please enter the date of birth.";
} elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input_dob)) {
    $dateOfBirth_err = "Date of birth must be in YYYY-MM-DD format.";
} elseif (strtotime($input_dob) >= strtotime("today")) {
    $dateOfBirth_err = "Date of birth must be in the past.";
} else {
    $dateOfBirth = $input_dob;
}

// ── Validate Gender ───────────────────────────────────────────────────────────
$input_gender = trim($_POST["gender"] ?? "");
if (empty($input_gender)) {
    $gender_err = "Please select a gender.";
} elseif (!in_array($input_gender, ["M", "F"], true)) {
    $gender_err = "Gender must be M or F.";
} else {
    $gender = $input_gender;
}

// ── Optional fields (sanitise only) ──────────────────────────────────────────
$allergies        = htmlspecialchars(trim($_POST["allergies"]         ?? "")) ?: null;
$medicalNotes     = htmlspecialchars(trim($_POST["medical_notes"]     ?? "")) ?: null;
$emergencyContact = htmlspecialchars(trim($_POST["emergency_contact"] ?? "")) ?: null;

// ── If no errors, call Parents::AddChild() ────────────────────────────────────
if (empty($childName_err) && empty($dateOfBirth_err) && empty($gender_err)) {

    require_once '../Models/AuthService.php';
    require_once '../Models/userRepository.php';

    $authService    = new AuthService();
    $userRepository = new UserRepository();

    $user   = $authService->getAuthenticatedUser();
    $userId = $user->getId();

    // Fetch the parentID from the Parent table (FK to User)
    require_once '../Models/Database.php';
    $parentRow = Database::getInstance()->fetchOne(
        "SELECT parentID FROM Parent WHERE userID = ?",
        [$userId]
    );

    if (!$parentRow) {
        $_SESSION["error"] = "Parent profile not found. Please contact support.";
        header("Location: ../View/profiles.php");
        exit;
    }

    require_once '../Models/Parent.php';

    // Build a minimal Parents object so we can call AddChild()
    $parent = new Parents(
        $userId,
        $user->getEmail(),
        "",                        // password not needed here
        $user->getPreferredLanguage(),
        $user->getCreatedAt(),
        $user->getLastLoginAt(),
        $user->getRole(),
        $user->getFirstName(),
        $user->getLastName()
    );

    $result = $parent->AddChild([
        "Name"             => $childName,
        "DateOfBirth"      => $dateOfBirth,
        "Gender"           => $gender,
        "allergies"        => $allergies,
        "MedicalNotes"     => $medicalNotes,
        "EmergencyContact" => $emergencyContact,
    ]);

    if ($result) {
        $_SESSION["message"] = "Child profile for {$childName} created successfully.";
    } else {
        $_SESSION["error"] = "Failed to save child profile. Please try again.";
    }

    header("Location: ../View/profiles.php");
    exit;
}

// ── Return errors to view ─────────────────────────────────────────────────────
$_SESSION["error"] = implode(" | ", array_filter([
    $childName_err, $dateOfBirth_err, $gender_err
]));
header("Location: ../View/profiles.php");
exit;
?>