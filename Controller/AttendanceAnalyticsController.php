<?php
session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Attendance.php';

$admin_id = (int) ($_GET['admin_id'] ?? 0);

if ($admin_id) {
    $Attendance = new Attendance();
    $analytics  = $Attendance->GetAttendanceRatesPerClass();

    if ($analytics) {
        $_SESSION['attendance_analytics'] = $analytics;

        header("Location: /AttendanceAnalytics.php?status=loaded");
    } else {
        header("Location: /AttendanceAnalytics.php?error=no_data");
    }
} else {
    header("Location: /AttendanceAnalytics.php?error=unauthorized");
}
exit();
