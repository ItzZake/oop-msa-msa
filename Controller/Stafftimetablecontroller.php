<?php
// FR-45: Staff Schedule & Timetable
// PHP-generated weekly timetable page built from course assignments and events records in MySQL.
// Accessible by the relevant teacher or admin.

session_start();

// Accessible by teacher or admin
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['teacher', 'admin'])) {
    http_response_code(403);
    header("location: ../index.php");
    exit("Access denied. Teachers and admins only.");
}

$teacher_id = "";
$teacher_id_err = "";
$timetableData = [];
$fetchError = "";

// If admin is viewing another teacher's timetable via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['user_role'] === 'admin') {
    $input_teacher_id = trim($_POST["teacher_id"]);
    if (empty($input_teacher_id)) {
        $teacher_id_err = "Please provide a teacher ID.";
    } elseif (!ctype_digit($input_teacher_id)) {
        $teacher_id_err = "Teacher ID must be a positive integer.";
    } else {
        $teacher_id = $input_teacher_id;
    }
} else {
    // Teacher views their own timetable from session
    $teacher_id = $_SESSION['user_id'] ?? null;
}

if (empty($teacher_id_err) && !empty($teacher_id) && ctype_digit((string)$teacher_id)) {
    include_once '../Models/CourseAssignment.php';
    include_once '../Models/Event.php';

    $assignmentModel = new CourseAssignment();
    $eventModel      = new Event();

    // Fetch weekly course schedule for this teacher
    $courseSchedule = $assignmentModel->getWeeklyScheduleByTeacher($teacher_id);
    if ($courseSchedule === false) {
        $fetchError = "Failed to load course schedule. Please try again later.";
    }

    // Fetch events for this teacher in the current week
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $weekEnd   = date('Y-m-d', strtotime('sunday this week'));
    $eventSchedule = $eventModel->getEventsByDateRange($weekStart, $weekEnd);
    if ($eventSchedule === false) {
        $fetchError = "Failed to load event schedule. Please try again later.";
    }

    if (empty($fetchError)) {
        $timetableData = [
            'teacher_id'     => $teacher_id,
            'week_start'     => $weekStart,
            'week_end'       => $weekEnd,
            'courses'        => $courseSchedule ?? [],
            'events'         => $eventSchedule ?? []
        ];
    }
}
?>