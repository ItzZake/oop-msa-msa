<?php
session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Subscription.php';
require_once __DIR__ . '/../Models/Payment.php';

$admin_id = (int) ($_GET['admin_id'] ?? 0);

if ($admin_id) {
    $Subscription = new Subscription();
    $Payment      = new Payment();

    $subscriptions = $Subscription->GetAll();
    $revenue       = $Payment->GetTotalRevenue();
    $overdue       = $Subscription->GetOverdue();

    if ($subscriptions) {
        $_SESSION['all_subscriptions'] = $subscriptions;
        $_SESSION['total_revenue']     = $revenue;
        $_SESSION['overdue_accounts']  = $overdue;

        header("Location: /AdminDashboard.php?status=loaded");
    } else {
        header("Location: /AdminDashboard.php?error=no_data");
    }
} else {
    header("Location: /AdminDashboard.php?error=unauthorized");
}
exit();
