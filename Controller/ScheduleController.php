<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Enrollment.php';

$child_id = (int) ($_GET['child_id'] ?? 0);

if ($child_id) {
    $Enrollment = new Enrollment();
	$Courses = new Course();
    $Courses = $Enrollment->GetEnrolledCoursesByChildId($child_id);
	$schedule = $Courses->GetScheduleForCourses();
    $_SESSION['schedule'] = $schedule;
    $_SESSION['child_id'] = $child_id;

    header("Location: /Schedule.php");
} else {
    header("Location: /Schedule.php?error=missing_child");
}
exit();