<?php
// FR-42: Staff Profiles & Roles
// Admin creates staff profiles via a PHP form; records saved to MySQL staff table with role;
// login credentials issued.

session_start();
require_once __DIR__ . '/../Models/userRepository.php';

// Admin only
$userRole = $_SESSION['user_role'] ?? $_SESSION['role'] ?? null;
if ($userRole !== 'admin') {
    http_response_code(403);
    header("location: ../index.php");
    exit("Access denied. Admins only.");
}

$full_name = $email = $role = $phone = "";
$full_name_err = $email_err = $role_err = $phone_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate full name
    $input_name = trim($_POST["full_name"]);
    if (empty($input_name)) {
        $full_name_err = "Please enter the staff member's full name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[a-zA-Z\s\-']+$/"]])) {
        $full_name_err = "Full name contains invalid characters.";
    } elseif (strlen($input_name) > 100) {
        $full_name_err = "Name must not exceed 100 characters.";
    } else {
        $full_name = htmlspecialchars($input_name);
    }

    // Validate email
    $input_email = trim($_POST["email"]);
    if (empty($input_email)) {
        $email_err = "Please enter an email address.";
    } elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = strtolower($input_email);
    }

    // Validate role
    $input_role = trim($_POST["role"]);
    $allowed_roles = ["admin", "teacher", "assistant", "coordinator"];
    if (empty($input_role)) {
        $role_err = "Please select a role.";
    } elseif (!in_array(strtolower($input_role), $allowed_roles)) {
        $role_err = "Invalid role selected.";
    } else {
        $role = strtolower($input_role);
    }

    // Validate phone (optional)
    $input_phone = trim($_POST["phone"] ?? "");
    if (!empty($input_phone)) {
        if (!preg_match('/^\+?[0-9\s\-]{7,20}$/', $input_phone)) {
            $phone_err = "Please enter a valid phone number.";
        } else {
            $phone = $input_phone;
        }
    }

    // If no errors, create staff record and issue credentials
    if (empty($full_name_err) && empty($email_err) && empty($role_err) && empty($phone_err)) {
        // Generate a temporary password
        $tempPassword = bin2hex(random_bytes(6)); // 12-char hex string
        $hashedPassword = password_hash($tempPassword, PASSWORD_BCRYPT);

        // Staff profile creation would be handled by appropriate model/service
        // For now, validate and redirect
        header("location: ../index.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id']) && isset($_GET['name'])) {
    $userId = filter_var($_GET['user_id'], FILTER_VALIDATE_INT);
    $fullName = trim($_GET['name']);

    header('Content-Type: application/json');

    if ($userId === false || $userId <= 0 || $fullName === '') {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID or name.']);
        exit();
    }

    $repo = new UserRepository();
    $user = $repo->findByIdAndName($userId, $fullName);

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Profile not found.']);
        exit();
    }

    echo json_encode(['success' => true, 'profile' => $user->toArray()]);
    exit();
}
?>