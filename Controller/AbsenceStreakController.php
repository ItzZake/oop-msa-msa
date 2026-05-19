<?php
// Triggered by cron job — no session needed

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Attendance.php';
require_once __DIR__ . '/../Models/Notification.php';

$ABSENCE_THRESHOLD = 3; // escalate after 3 consecutive absences

$Attendance = new Attendance();
$allChildren = $Attendance->GetAllEnrolledChildren();

if ($allChildren) {
    foreach ($allChildren as $child) {
        $streak = $Attendance->GetConsecutiveAbsences($child->GetChildId());

        if ($streak >= $ABSENCE_THRESHOLD) {
            // Notify parent
            $Notification = new Notification();
            $Notification->SendAbsenceAlert($child->GetParentId(), $child->GetChildId(), $streak);

            // Flag in DB for admin
            $Attendance->FlagAbsenceStreak($child->GetChildId());
        }
    }
    echo "Absence streak detection complete.";
} else {
    echo "No enrolled children found.";
}
exit();
