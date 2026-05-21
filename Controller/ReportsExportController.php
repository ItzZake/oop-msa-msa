<?php
session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Attendance.php';
require_once __DIR__ . '/../Models/Payment.php';
require_once __DIR__ . '/../Models/Enrollment.php';

$report_type = htmlspecialchars($_POST['report_type'] ?? '');
$export_type = htmlspecialchars($_POST['export_type'] ?? ''); // 'csv' or 'pdf'
$date_from   = htmlspecialchars($_POST['date_from']   ?? '');
$date_to     = htmlspecialchars($_POST['date_to']     ?? '');
$admin_id    = (int) ($_POST['admin_id'] ?? 0);

if ($report_type && $export_type && $date_from && $date_to && $admin_id) {

    // Fetch correct report based on filter
    switch ($report_type) {
        case 'attendance':
            $Report = new Attendance();
            $data   = $Report->GetReportByDateRange($date_from, $date_to);
            break;
        case 'payment':
            $Report = new Payment();
            $data   = $Report->GetReportByDateRange($date_from, $date_to);
            break;
        case 'enrollment':
            $Report = new Enrollment();
            $data   = $Report->GetReportByDateRange($date_from, $date_to);
            break;
        default:
            header("Location: /Reports.php?error=invalid_type");
            exit();
    }

    if ($data) {
        $_SESSION['report_data']  = $data;
        $_SESSION['export_type']  = $export_type;
        $_SESSION['report_type']  = $report_type;

        header("Location: /Reports.php?status=ready&export=" . urlencode($export_type));
    } else {
        header("Location: /Reports.php?error=no_data");
    }
} else {
    header("Location: /Reports.php?error=missing_data");
}
exit();
