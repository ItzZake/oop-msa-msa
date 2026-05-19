<?php
session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Assignment.php';

$teacher_id    = (int) ($_POST['teacher_id']    ?? 0);
$assignment_id = (int) ($_POST['assignment_id'] ?? 0);
$embed_code    = $_POST['embed_code'] ?? ''; // raw iframe HTML — do NOT htmlspecialchars

if ($teacher_id && $assignment_id && $embed_code) {

    // Only allow iframe tags from Wordwall
    $allowed = '<iframe>';
    $embed_code = strip_tags($embed_code, $allowed);

    $Assignment = new Assignment();
    $result     = $Assignment->SaveEmbedCode($assignment_id, $embed_code);

    if ($result) {
        $_SESSION['embed_message'] = "Wordwall activity embedded successfully!";
        header("Location: /Assignment.php?id=" . $assignment_id . "&status=embedded");
    } else {
        header("Location: /Assignment.php?id=" . $assignment_id . "&error=embed_failed");
    }
} else {
    header("Location: /Assignment.php?error=missing_data");
}
exit();
