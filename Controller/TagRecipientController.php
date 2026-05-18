<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Assignment.php';
require_once '/../Models/Tag.php';

$assignment_id = (int) ($_POST['assignment_id'] ?? 0);
$tags          = $_POST['tags'] ?? []; // selected tags array

if ($assignment_id && !empty($tags)) {
    $Tag = new Tag();

    // JOIN children and tags tables to resolve recipients
    $recipients = $Tag->GetChildrenByTags($tags); // deduplicates via MySQL JOIN

    if ($recipients) {
        $Assignment = new Assignment();

        foreach ($recipients as $child) {
            $Assignment->CreateRecipientRecord($assignment_id, $child->GetChildId());
        }

        $_SESSION['tag_message'] = "Recipients assigned successfully!";
        header("Location: /Assignment.php?id=" . $assignment_id . "&status=tagged");
    } else {
        header("Location: /Assignment.php?id=" . $assignment_id . "&error=no_recipients");
    }
} else {
    header("Location: /Assignment.php?error=missing_data");
}
exit();