<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Course.php';

$teacher_id = (int) ($_POST['teacher_id'] ?? 0);
$course_id  = (int) ($_POST['course_id']  ?? 0);

if ($teacher_id && $course_id) {
    $Course = new Course();

    // FR-24: Conflict check before saving
    if ($Course->HasSchedulingConflict($teacher_id, $course_id)) {
        header("Location: /TeacherAssign.php?error=conflict");
    } else {
        $Course->AssignTeacher($teacher_id, $course_id);
        header("Location: /TeacherAssign.php?status=success");
    }
} else {
    header("Location: /TeacherAssign.php?error=missing_data");
}
exit();