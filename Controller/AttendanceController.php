<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Attendance.php';

$teacher_id = (int) ($_POST['teacher_id'] ?? 0);
$course_id  = (int) ($_POST['course_id']  ?? 0);
$marks      = $_POST['marks'] ?? []; // array of ['child_id' => status]

if ($teacher_id && $course_id && !empty($marks)) {
    $Attendance = new Attendance();
    $timestamp  = date('Y-m-d H:i:s');

    foreach ($marks as $child_id => $status) {
        $Attendance->InsertRecord((int)$child_id, $course_id, $status, $timestamp);
    }

    $_SESSION['attendance_message'] = "Attendance submitted successfully!";
    header("Location: /Attendance.php?status=success");
} else {
    header("Location: /Attendance.php?error=missing_data");
}
exit();