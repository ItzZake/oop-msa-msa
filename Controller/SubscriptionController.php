<?php
session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Subscription.php';

$plan_id  = (int) ($_POST['plan_id']  ?? 0);
$child_id = (int) ($_POST['child_id'] ?? 0);

if ($plan_id && $child_id) {
    $Subscription = new Subscription();
    $result       = $Subscription->SaveSubscription($child_id, $plan_id);

    if ($result) {
        $_SESSION['sub_message'] = "Subscription saved successfully!";
        header("Location: /Subscription.php?status=success");
    } else {
        header("Location: /Subscription.php?error=save_failed");
    }
} else {
    header("Location: /Subscription.php?error=missing_data");
}
exit();
