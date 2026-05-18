<?php
// Triggered by cron job — no session needed

require_once '/../Models/Database.php';
require_once '/../Models/Attendance.php';
require_once '/../Models/Enrollment.php';

$Attendance  = new Attendance();
$Enrollment  = new Enrollment();
$timestamp   = date('Y-m-d H:i:s');

// Get all active sessions that have passed their marking window
$closedSessions = $Attendance->GetClosedUnmarkedSessions();

if ($closedSessions) {
    foreach ($closedSessions as $session) {
        // Get children enrolled but not yet marked
        $unmarkedChildren = $Enrollment->GetUnmarkedChildren($session->GetSessionId());

        foreach ($unmarkedChildren as $child) {
            $Attendance->InsertRecord($child->GetChildId(), $session->GetCourseId(), 'absent', $timestamp);
        }
    }
    echo "Absent records auto-inserted for unmarked children.";
} else {
    echo "No closed unmarked sessions found.";
}
exit();