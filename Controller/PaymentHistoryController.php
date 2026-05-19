<?php
session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Payment.php';

$parent_id = (int) ($_GET['parent_id'] ?? 0);

if ($parent_id) {
    $Payment = new Payment();
    $history = $Payment->GetTransactionsByParentId($parent_id);

    if ($history) {
        $_SESSION['payment_history'] = $history;
        $_SESSION['parent_id']       = $parent_id;

        header("Location: /PaymentHistory.php?status=success");
    } else {
        header("Location: /PaymentHistory.php?error=no_records");
    }
} else {
    header("Location: /PaymentHistory.php?error=missing_data");
}
exit();
