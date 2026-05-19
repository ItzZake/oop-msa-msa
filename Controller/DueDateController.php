<?php
// Triggered by cron job — no session needed

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Subscription.php';

$Subscription       = new Subscription();
$activeSubscriptions = $Subscription->GetAllActive();

if ($activeSubscriptions) {
    foreach ($activeSubscriptions as $sub) {
        $nextDueDate = $Subscription->CalculateNextDueDate($sub->GetStartDate());

        $Subscription->UpdateDueDate($sub->GetSubscriptionId(), $nextDueDate);
        $Subscription->QueueReminder($sub->GetSubscriptionId(), $nextDueDate);
    }
    echo "Due dates updated and reminders queued.";
} else {
    echo "No active subscriptions found.";
}
exit();
