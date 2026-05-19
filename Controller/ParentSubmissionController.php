<?php
session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Submission.php';
require_once __DIR__ . '/../Models/Notification.php';

$parent_id     = (int) ($_POST['parent_id']     ?? 0);
$child_id      = (int) ($_POST['child_id']      ?? 0);
$assignment_id = (int) ($_POST['assignment_id'] ?? 0);
$type          = htmlspecialchars($_POST['type'] ?? ''); // 'mark_done', 'photo', 'text'
$text_content  = htmlspecialchars($_POST['text_content'] ?? '');
$photo         = $_FILES['photo'] ?? null;

if ($parent_id && $child_id && $assignment_id && $type) {
    $file_path = null;

    // Handle photo upload if type is photo
    if ($type === 'photo' && $photo && $photo['error'] === UPLOAD_ERR_OK) {
        $file_path = '/uploads/submissions/' . basename($photo['name']);
        move_uploaded_file($photo['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $file_path);
    }

    $Submission = new Submission();
    $result     = $Submission->Insert($child_id, $assignment_id, $type, $text_content, $file_path);

    if ($result) {
        // Notify teacher
        $Notification = new Notification();
        $Notification->SendSubmissionAlert($assignment_id, $child_id);

        $_SESSION['submission_message'] = "Submission recorded successfully!";
        header("Location: /Submission.php?status=success");
    } else {
        header("Location: /Submission.php?error=save_failed");
    }
} else {
    header("Location: /Submission.php?error=missing_data");
}
exit();
