<?php
// Triggered by cron job — no session needed

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Subscription.php';
require_once __DIR__ . '/../Models/Notification.php';

$Subscription       = new Subscription();
$overdueSubscriptions = $Subscription->GetOverdue(); // compares due_date vs today

if ($overdueSubscriptions) {
    foreach ($overdueSubscriptions as $sub) {
        $Subscription->FlagAsOverdue($sub->GetSubscriptionId());
        $Subscription->RestrictAccess($sub->GetParentId());

        $Notification = new Notification();
        $Notification->SendOverdueNotification($sub->GetParentId());
    }
    echo "Overdue subscriptions flagged and notifications sent.";
} else {
    echo "No overdue subscriptions found.";
}
exit();
