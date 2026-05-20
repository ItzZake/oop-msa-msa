<?php
session_start();
require_once '../Models/Database.php';

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
$parent_name = trim(htmlspecialchars($_POST['parent_name'] ?? ''));
$parent_email = filter_var(trim($_POST['parent_email'] ?? ''), FILTER_SANITIZE_EMAIL);
$parent_phone = trim(htmlspecialchars($_POST['parent_phone'] ?? ''));
$address = trim(htmlspecialchars($_POST['address'] ?? ''));
$child_name = trim(htmlspecialchars($_POST['child_name'] ?? ''));
$child_dob = trim($_POST['child_dob'] ?? '');
$child_gender = trim($_POST['child_gender'] ?? '');
$program = trim(htmlspecialchars($_POST['program'] ?? ''));
$start_date = trim($_POST['start_date'] ?? '');
$emergency_name = trim(htmlspecialchars($_POST['emergency_name'] ?? ''));
$emergency_phone = trim(htmlspecialchars($_POST['emergency_phone'] ?? ''));
$medical_info = trim(htmlspecialchars($_POST['medical_info'] ?? ''));
$comments = trim(htmlspecialchars($_POST['comments'] ?? ''));

// Validation
$required = [
    'parent_name' => $parent_name,
    'parent_email' => $parent_email,
    'parent_phone' => $parent_phone,
    'address' => $address,
    'child_name' => $child_name,
    'child_dob' => $child_dob,
    'child_gender' => $child_gender,
    'program' => $program,
    'start_date' => $start_date,
    'emergency_name' => $emergency_name,
    'emergency_phone' => $emergency_phone,
];
$fieldLabels = [
    'parent_name' => 'Parent name',
    'parent_email' => 'Parent email',
    'parent_phone' => 'Parent phone',
    'address' => 'Home address',
    'child_name' => 'Child name',
    'child_dob' => 'Child date of birth',
    'child_gender' => 'Child gender',
    'program' => 'Program',
    'start_date' => 'Preferred start date',
    'emergency_name' => 'Emergency contact name',
    'emergency_phone' => 'Emergency phone',
];
foreach ($required as $key => $field) {
    if (empty($field)) {
        $_SESSION['error'] = 'Please fill in all required fields. Missing: ' . $fieldLabels[$key];
        header('Location: enroll.php');
        exit;
    }
}

if (!filter_var($parent_email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address';
    header('Location: enroll.php');
    exit;
}

if (!in_array($child_gender, ['M', 'F'], true)) {
    $_SESSION['error'] = 'Please select a valid child gender';
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

$app_id = 'APP-' . date('Y') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
$db = Database::getInstance();

try {
    $db->beginTransaction();

    $user = $db->fetchOne('SELECT * FROM `User` WHERE email = ? LIMIT 1', [$parent_email]);

    if ($user) {
        if ($user['Role'] !== 'Parent') {
            throw new RuntimeException('The provided email is already registered with a different account type.');
        }

        $parentRow = $db->fetchOne('SELECT * FROM `Parent` WHERE userID = ? LIMIT 1', [$user['userID']]);
        if (!$parentRow) {
            $db->query('INSERT INTO `Parent` (`userID`, `phone`, `address`) VALUES (?, ?, ?)', [$user['userID'], $parent_phone, $address]);
            $parentId = $db->lastInsertId();
        } else {
            $parentId = $parentRow['parentID'];
            if (empty($parentRow['phone']) || empty($parentRow['address'])) {
                $db->query('UPDATE `Parent` SET phone = ?, address = ? WHERE parentID = ?', [$parent_phone, $address, $parentId]);
            }
        }
    } else {
        $passwordHash = password_hash('Welcome@123', PASSWORD_DEFAULT);
        $nameParts = preg_split('/\s+/', $parent_name, 2, PREG_SPLIT_NO_EMPTY);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        $db->query('INSERT INTO `User` (`email`, `passwordHash`, `firstname`, `Lastname`, `Role`, `preferredLanguage`, `createdAt`, `isActive`) VALUES (?, ?, ?, ?, ?, ?, NOW(), 1)', [$parent_email, $passwordHash, $firstName, $lastName, 'Parent', 'EN']);
        $userId = $db->lastInsertId();

        if (!$userId) {
            throw new RuntimeException('Unable to create parent account.');
        }

        $db->query('INSERT INTO `Parent` (`userID`, `phone`, `address`) VALUES (?, ?, ?)', [$userId, $parent_phone, $address]);
        $parentId = $db->lastInsertId();
    }

    if (!$parentId) {
        throw new RuntimeException('Unable to resolve parent profile.');
    }

    $emergency_contact = $emergency_name . ' - ' . $emergency_phone;
    $childSql = 'INSERT INTO `Child` (`parentID`, `name`, `dateOfBirth`, `gender`, `allergies`, `medicalNotes`, `emergencyContact`, `enrollmentStatus`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)' ;
    $childParams = [$parentId, $child_name, $child_dob, $child_gender, $medical_info ?: null, $comments ?: null, $emergency_contact, 'Pending'];
    $childStmt = $db->query($childSql, $childParams);

    if (!$childStmt || $childStmt->rowCount() === 0) {
        throw new RuntimeException('Failed to create the child profile.');
    }

    $childId = $db->lastInsertId();

    $course = $db->fetchOne('SELECT * FROM `course` WHERE name = ? AND isActive = 1 LIMIT 1', [$program]);
    if (!$course) {
        $course = $db->fetchOne('SELECT * FROM `course` WHERE name LIKE ? AND isActive = 1 LIMIT 1', ['%' . trim(str_replace(['(', ')'], '', $program)) . '%']);
    }

    if (!$course) {
        $courseTypeMap = [
            'Nursery (Ages 2-3)' => ['ageMin' => 2, 'ageMax' => 3],
            'Kindergarten 1 (Ages 3-4)' => ['ageMin' => 3, 'ageMax' => 4],
            'Kindergarten 2 (Ages 4-5)' => ['ageMin' => 4, 'ageMax' => 5],
        ];
        $courseMeta = $courseTypeMap[$program] ?? ['ageMin' => 2, 'ageMax' => 5];

        $insertCourseSql = 'INSERT INTO `course` (`name`, `description`, `ageMin`, `ageMax`, `maxCapacity`, `currentEnrollment`, `price`, `schedule`, `isActive`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $insertCourseParams = [$program, $program, $courseMeta['ageMin'], $courseMeta['ageMax'], 20, 0, 0.00, null, 1];
        $db->query($insertCourseSql, $insertCourseParams);
        $courseId = $db->lastInsertId();

        if (!$courseId) {
            throw new RuntimeException('Unable to create the selected program in the system.');
        }

        $course = $db->fetchOne('SELECT * FROM `course` WHERE courseID = ? LIMIT 1', [$courseId]);
        if (!$course) {
            throw new RuntimeException('Unable to resolve the newly created program.');
        }
    }

    $enrollmentSql = 'INSERT INTO `enrollment` (`childID`, `courseID`, `enrolledAt`, `status`, `isWaitlisted`) VALUES (?, ?, ?, ?, ?)';
    $enrollmentParams = [$childId, $course['courseID'], date('Y-m-d H:i:s'), 'Active', 0];
    $enrollmentStmt = $db->query($enrollmentSql, $enrollmentParams);
    if (!$enrollmentStmt || $enrollmentStmt->rowCount() === 0) {
        throw new RuntimeException('Failed to save enrollment information.');
    }

    $applicationData = [
        'applicationId' => $app_id,
        'parentName' => $parent_name,
        'parentEmail' => $parent_email,
        'parentPhone' => $parent_phone,
        'parentAddress' => $address,
        'childName' => $child_name,
        'childGender' => $child_gender,
        'childDob' => $child_dob,
        'program' => $program,
        'courseId' => $course['courseID'],
        'startDate' => $start_date,
        'emergencyName' => $emergency_name,
        'emergencyPhone' => $emergency_phone,
        'medicalInfo' => $medical_info,
        'comments' => $comments,
    ];

    $db->query('INSERT INTO `Application` (`parentID`, `childID`, `status`, `reviewedAt`, `rejectionReason`, `documents`) VALUES (?, ?, ?, ?, ?, ?)', [$parentId, $childId, 'Pending', null, null, json_encode($applicationData)]);

    $db->commit();

    error_log("Enrollment application submitted: $app_id - $child_name by $parent_email");
    $_SESSION['message'] = "Enrollment application submitted successfully! Your application ID is $app_id. We'll contact you within 48 hours.";
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    error_log('Enrollment error: ' . $e->getMessage());
    $_SESSION['error'] = 'There was a problem submitting your application. Please try again or contact support.';
}

header('Location: enroll.php');
exit;
?>
