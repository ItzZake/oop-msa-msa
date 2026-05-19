<?php
session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Enrollment.php';
require_once __DIR__ . '/../Models/Invoice.php';

$enrollment_id = (int) ($_POST['enrollment_id'] ?? 0);

if ($enrollment_id) {
    $Enrollment = new Enrollment();
    $enrollment = $Enrollment->GetEnrollmentById($enrollment_id);

    if ($enrollment) {
        $Invoice = new Invoice();

        // Auto-adds course fee to parent's monthly invoice via JOIN
        $result = $Invoice->AddCourseCharge($enrollment->GetParentId(), $enrollment_id);

        if ($result) {
            $_SESSION['billing_message'] = "Course charge added to invoice.";
            header("Location: /Invoice.php?status=updated");
        } else {
            header("Location: /Invoice.php?error=billing_failed");
        }
    } else {
        header("Location: /Invoice.php?error=enrollment_not_found");
    }
} else {
    header("Location: /Invoice.php?error=missing_data");
}
exit();
