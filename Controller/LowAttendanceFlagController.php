<?php
// Triggered by cron job — no session needed

require_once '/../Models/Database.php';
require_once '/../Models/Attendance.php';
require_once '/../Models/Flags.php';
require_once '/../Models/Settings.php';

$Settings  = new Settings();
$threshold = $Settings->Get('attendance_threshold'); // pulls from settings table

$Attendance = new Attendance();
$lowRecords = $Attendance->GetBelowThreshold($threshold);

if ($lowRecords) {
    $Flags = new Flags();

    foreach ($lowRecords as $record) {
        $Flags->SetFlag('low_attendance', $record->GetChildId(), $record->GetCourseId());
    }
    echo "Low attendance records flagged successfully.";
} else {
    echo "No low attendance records found.";
}
exit();