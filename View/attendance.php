<?php
session_start();
$pageTitle = "Attendance – Wellucation Nursery";
$currentPage = "attendance";
$pageCss = 'attendance.css';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'header.php';
include 'navbar.php';

// Mock students data (in production, fetch from database)
$students = [
    ['id' => 1, 'name' => 'Emma Johnson', 'emoji' => '👧', 'class' => 'KG1'],
    ['id' => 2, 'name' => 'Noah Williams', 'emoji' => '👦', 'class' => 'KG1'],
    ['id' => 3, 'name' => 'Sophia Brown', 'emoji' => '👧', 'class' => 'KG1'],
    ['id' => 4, 'name' => 'Liam Davis', 'emoji' => '👦', 'class' => 'KG1'],
    ['id' => 5, 'name' => 'Olivia Miller', 'emoji' => '👧', 'class' => 'KG1'],
    ['id' => 6, 'name' => 'Mason Wilson', 'emoji' => '👦', 'class' => 'KG1'],
    ['id' => 7, 'name' => 'Ava Chen', 'emoji' => '👧', 'class' => 'KG1'],
    ['id' => 8, 'name' => 'James Park', 'emoji' => '👦', 'class' => 'KG1'],
];
?>

<?php include 'partials/partial_attendance.php'; ?>

<?php include 'footer.php'; ?>
