<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Submission.php';

$teacher_id    = (int) ($_POST['teacher_id']    ?? 0);
$submission_id = (int) ($_POST['submission_id'] ?? 0);
$grade         = htmlspecialchars($_POST['grade']    ?? '');
$feedback      = htmlspecialchars($_POST['feedback'] ?? '');

if ($teacher_id && $submission_id && $grade && $feedback) {
    $Submission = new Submission();
    $result     = $Submission->SaveGrade($submission_id, $grade, $feedback);

    if ($result) {
        $_SESSION['grade_message'] = "Grade and feedback saved successfully!";
        header("Location: /Grading.php?status=success");
    } else {
        header("Location: /Grading.php?error=save_failed");
    }
} else {
    header("Location: /Grading.php?error=missing_data");
}
exit();