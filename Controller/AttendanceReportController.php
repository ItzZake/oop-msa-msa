<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Attendance.php';

$child_id   = (int) ($_GET['child_id']   ?? 0);
$date_from  = htmlspecialchars($_GET['date_from'] ?? '');
$date_to    = htmlspecialchars($_GET['date_to']   ?? '');

if ($child_id && $date_from && $date_to) {
    $Attendance = new Attendance();
    $report     = $Attendance->GetReportByChildAndDateRange($child_id, $date_from, $date_to);

    if ($report) {
        $_SESSION['attendance_report'] = $report;
        $_SESSION['child_id']          = $child_id;
        $_SESSION['date_from']         = $date_from;
        $_SESSION['date_to']           = $date_to;

        header("Location: /AttendanceReport.php?status=loaded");
    } else {
        header("Location: /AttendanceReport.php?error=no_records");
    }
} else {
    header("Location: /AttendanceReport.php?error=missing_data");
}
exit();