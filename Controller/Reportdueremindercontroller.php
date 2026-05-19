<?php
// FR-40: Report Due Reminder for Teachers
// PHP cron job that checks for overdue reports in MySQL
// and sends a reminder email to the assigned teacher.

// Only allow CLI or internal cron execution
if (php_sapi_name() !== 'cli' && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
    http_response_code(403);
    exit("Access denied.");
}

include_once '../Models/ProgressReport.php';
include_once '../Models/Notification.php';

$reportModel       = new ProgressReport();
$notificationModel = new Notification();

$today = date('Y-m-d');

// Fetch all reports that are overdue (past due date and still Draft/Pending Review)
$overdueReports = $reportModel->getOverdueReports($today);

if (empty($overdueReports)) {
    echo "No overdue reports found for $today.\n";
    exit();
}

foreach ($overdueReports as $report) {
    $report_id  = $report['report_id'];
    $teacher_id = $report['teacher_id'];
    $child_id   = $report['child_id'];
    $due_date   = $report['due_date'];

    // Validate required fields
    if (empty($report_id) || !ctype_digit((string)$report_id)) {
        echo "Warning: Invalid report ID encountered, skipping.\n";
        continue;
    }

    if (empty($teacher_id) || !ctype_digit((string)$teacher_id)) {
        echo "Warning: Invalid teacher ID for report $report_id, skipping.\n";
        continue;
    }

    // Send reminder email to the assigned teacher
    $emailSent = $notificationModel->sendEmailReminder(
        $teacher_id,
        "Reminder: Progress report for child ID $child_id (Report #$report_id) was due on $due_date and is still pending submission."
    );

    if ($emailSent) {
        echo "Report ID $report_id: Reminder sent to teacher ID $teacher_id.\n";
    } else {
        echo "Report ID $report_id: Failed to send reminder to teacher ID $teacher_id.\n";
    }
}
?>