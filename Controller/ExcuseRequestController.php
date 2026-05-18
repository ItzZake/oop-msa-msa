<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Excuse.php';

$child_id = (int) ($_POST['child_id'] ?? 0);
$date     = htmlspecialchars($_POST['date']   ?? '');
$reason   = htmlspecialchars($_POST['reason'] ?? '');

if ($child_id && $date && $reason) {
    $Excuse = new Excuse();
    $result = $Excuse->InsertExcuse($child_id, $date, $reason);

    if ($result) {
        // Exclude this date from absence streak count
        $Excuse->ExcludeFromStreakCount($child_id, $date);

        $_SESSION['excuse_message'] = "Excuse submitted successfully!";
        header("Location: /Excuse.php?status=success");
    } else {
        header("Location: /Excuse.php?error=save_failed");
    }
} else {
    header("Location: /Excuse.php?error=missing_data");
}
exit();