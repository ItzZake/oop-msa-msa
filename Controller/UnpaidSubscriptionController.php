<?php
// Triggered by cron job — no session needed

require_once '/../Models/Database.php';
require_once '/../Models/Subscription.php';
require_once '/../Models/Payment.php';

$Payment      = new Payment();
$Subscription = new Subscription();

// Compare due dates vs today and get overdue records
$overdueList = $Payment->GetOverdueByDueDate();

if ($overdueList) {
    foreach ($overdueList as $record) {
        $Subscription->FlagAsUnpaid($record->GetSubscriptionId());
    }
    echo "Unpaid subscriptions flagged and sorted by days overdue.";
} else {
    echo "No unpaid subscriptions found.";
}
exit();