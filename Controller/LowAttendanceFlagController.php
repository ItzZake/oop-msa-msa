<?php
// Triggered by cron job — no session needed

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Attendance.php';
require_once __DIR__ . '/../Models/Flag.php';
require_once __DIR__ . '/../Models/Settings.php';

$Settings  = new Settings();
$threshold = $Settings->Get('attendance_threshold'); // pulls from settings table
if (!is_numeric($threshold) || $threshold <= 0) {
    $threshold = 3;
}

$Attendance = new Attendance();
$lowRecords = $Attendance->GetBelowThreshold((int) $threshold);

if ($lowRecords) {
    $Flag = new Flag();

    foreach ($lowRecords as $record) {
        $Flag->SetFlag('low_attendance', $record->GetChildId(), $record->GetCourseId());
    }
    echo "Low attendance records flagged successfully.";
} else {
    echo "No low attendance records found.";
}
exit();
