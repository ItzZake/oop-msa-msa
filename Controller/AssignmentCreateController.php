<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Assignment.php';
require_once '/../Models/Notification.php';

$teacher_id  = (int) ($_POST['teacher_id'] ?? 0);
$title       = htmlspecialchars($_POST['title']       ?? '');
$description = htmlspecialchars($_POST['description'] ?? '');
$due_date    = htmlspecialchars($_POST['due_date']    ?? '');
$tags        = $_POST['tags'] ?? []; // recipient tags array
$file        = $_FILES['attachment'] ?? null;

if ($teacher_id && $title && $due_date && !empty($tags)) {
    $Assignment = new Assignment();

    // Handle file attachment if provided
    $file_path = null;
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $file_path = '/uploads/assignments/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $file_path);
    }

    $assignment_id = $Assignment->Insert($teacher_id, $title, $description, $due_date, $tags, $file_path);

    if ($assignment_id) {
        // Notify recipients
        $Notification = new Notification();
        $Notification->SendAssignmentNotification($assignment_id, $tags);

        $_SESSION['assignment_message'] = "Assignment created successfully!";
        header("Location: /Assignment.php?status=success");
    } else {
        header("Location: /Assignment.php?error=save_failed");
    }
} else {
    header("Location: /Assignment.php?error=missing_data");
}
exit();