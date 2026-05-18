<?php
// Triggered by cron job — no session needed

require_once '/../Models/Database.php';
require_once '/../Models/Assignment.php';
require_once '/../Models/Notification.php';

$Assignment = new Assignment();
$now        = date('Y-m-d H:i:s');
$in24hours  = date('Y-m-d H:i:s', strtotime('+24 hours'));

// Get assignments due within 24 hours that haven't been submitted
$upcoming = $Assignment->GetDueSoon($now, $in24hours);

if ($upcoming) {
    $Notification = new Notification();

    foreach ($upcoming as $assignment) {
        // Send 24hr reminder to parent
        $Notification->SendDueReminder($assignment->GetAssignmentId(), $assignment->GetChildId());
    }
    echo "Due date reminders sent.";
}

// Get assignments past due date — mark as Late
$overdue = $Assignment->GetOverdueUnsubmitted($now);

if ($overdue) {
    foreach ($overdue as $assignment) {
        $Assignment->SetStatusLate($assignment->GetAssignmentId(), $assignment->GetChildId());
    }
    echo " Overdue assignments marked as Late.";
}

if (!$upcoming && !$overdue) {
    echo "No upcoming or overdue assignments found.";
}
exit();