<?php
session_start();

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    http_response_code(403);
    exit('Forbidden - Teacher access required');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: attendance.php');
    exit;
}

// CSRF validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Security validation failed';
    header('Location: attendance.php');
    exit;
}

$date = $_POST['date'] ?? date('Y-m-d');
$attendance = $_POST['attendance'] ?? [];

if (empty($attendance)) {
    $_SESSION['error'] = 'No attendance data submitted';
    header('Location: attendance.php');
    exit;
}

// In production, save to database
// foreach ($attendance as $student_id => $status) {
//     $stmt = $pdo->prepare("INSERT INTO attendance (student_id, date, status, marked_by) VALUES (?, ?, ?, ?)");
//     $stmt->execute([(int)$student_id, $date, $status, $_SESSION['user_id']]);
// }

// Log the activity
error_log("Teacher {$_SESSION['user_id']} submitted attendance for " . date('Y-m-d'));

$_SESSION['message'] = 'Attendance submitted successfully for ' . date('F j, Y');
header('Location: attendance.php');
exit;
?>
