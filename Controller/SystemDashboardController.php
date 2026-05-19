<?php
session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Enrollment.php';
require_once __DIR__ . '/../Models/Payment.php';
require_once __DIR__ . '/../Models/Flag.php';

$admin_id = (int) ($_GET['admin_id'] ?? 0);

if ($admin_id) {
    $Enrollment = new Enrollment();
    $Payment    = new Payment();
    $Flag      = new Flag();

    $enrollmentMetrics = $Enrollment->GetAggregateMetrics();
    $revenueMetrics    = $Payment->GetAggregateRevenue();
    $activeFlags       = $Flag->GetAllActiveFlags();

    if ($enrollmentMetrics && $revenueMetrics) {
        $_SESSION['enrollment_metrics'] = $enrollmentMetrics;
        $_SESSION['revenue_metrics']    = $revenueMetrics;
        $_SESSION['active_flags']       = $activeFlags;

        header("Location: /SystemDashboard.php?status=loaded");
    } else {
        header("Location: /SystemDashboard.php?error=no_data");
    }
} else {
    header("Location: /SystemDashboard.php?error=unauthorized");
}
exit();
