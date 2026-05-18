<?php
// FR-05 — Application & Approval Flow
// Three actions share this file, selected by the hidden "action" field:
//
//   action = "submit"   → parent submits an application   (role: parent)
//   action = "approve"  → admin approves an application   (role: admin)
//   action = "reject"   → admin rejects an application    (role: admin)

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/application.php");
    exit;
}

require_once '../Models/Application.php';
require_once '../Models/Database.php';

$action = trim($_POST["action"] ?? "");

// ════════════════════════════════════════════════════════════════════════
//  PARENT: Submit Application
// ════════════════════════════════════════════════════════════════════════
if ($action === "submit") {

    // Guard — only parents
    if (empty($_SESSION["user_id"]) || strtolower($_SESSION["user_role"] ?? "") !== "parent") {
        $_SESSION["error"] = "Please sign in as a parent to submit an application.";
        header("Location: ../View/login.php");
        exit;
    }

    // ── Define variables ──────────────────────────────────────────────
    $childId = 0;
    $childId_err = "";

    // ── Validate Child Selection ──────────────────────────────────────
    $input_childId = intval($_POST["child_id"] ?? 0);
    if ($input_childId <= 0) {
        $childId_err = "Please select a child profile.";
    } else {
        $childId = $input_childId;
    }

    // ── If no errors, verify ownership then submit ────────────────────
    if (empty($childId_err)) {

        require_once '../Models/AuthService.php';
        require_once '../Models/userRepository.php';

        $authService = new AuthService();
        $user        = $authService->getAuthenticatedUser();
        $userId      = $user->getId();

        // Get parentID
        $parentRow = Database::getInstance()->fetchOne(
            "SELECT parentID FROM Parent WHERE userID = ?",
            [$userId]
        );

        if (!$parentRow) {
            $_SESSION["error"] = "Parent profile not found.";
            header("Location: ../View/application.php");
            exit;
        }

        $parentId = (int) $parentRow["parentID"];

        // Confirm child belongs to this parent
        $childRow = Database::getInstance()->fetchOne(
            "SELECT childID FROM Child WHERE childID = ? AND parentID = ?",
            [$childId, $parentId]
        );

        if (!$childRow) {
            $_SESSION["error"] = "Selected child profile was not found.";
            header("Location: ../View/application.php");
            exit;
        }

        // Check no pending application already exists
        $existing = Database::getInstance()->fetchOne(
            "SELECT applicationID FROM Application WHERE childID = ? AND status = 'Pending'",
            [$childId]
        );

        if ($existing) {
            $_SESSION["error"] = "An application for this child is already pending review.";
            header("Location: ../View/application.php");
            exit;
        }

        // Collect uploaded document paths from session (populated by upload_document.php)
        $docs = $_SESSION["uploaded_docs"] ?? [];

        $application = new Application();
        $result = $application->Submit([
            "ChildId"   => $childId,
            "ParentId"  => $parentId,
            "Documents" => $docs,
        ]);

        if ($result) {
            unset($_SESSION["uploaded_docs"]);
            $_SESSION["message"] = "Application submitted! You will be notified once it is reviewed.";
            header("Location: ../View/dashboard.php");
        } else {
            $_SESSION["error"] = "Application submission failed. Please try again.";
            header("Location: ../View/application.php");
        }
        exit;
    }

    $_SESSION["error"] = $childId_err;
    header("Location: ../View/application.php");
    exit;
}

// ════════════════════════════════════════════════════════════════════════
//  ADMIN: Approve Application
// ════════════════════════════════════════════════════════════════════════
if ($action === "approve") {

    // Guard — only admins
    if (empty($_SESSION["user_id"]) || strtolower($_SESSION["user_role"] ?? "") !== "admin") {
        $_SESSION["error"] = "Access denied.";
        header("Location: ../View/login.php");
        exit;
    }

    // ── Define variables ──────────────────────────────────────────────
    $applicationId = 0;
    $applicationId_err = "";

    // ── Validate Application ID ───────────────────────────────────────
    $input_appId = intval($_POST["application_id"] ?? 0);
    if ($input_appId <= 0) {
        $applicationId_err = "Invalid application ID.";
    } else {
        $applicationId = $input_appId;
    }

    // ── If no errors, approve ─────────────────────────────────────────
    if (empty($applicationId_err)) {

        // Fetch parentID for notification
        $appRow = Database::getInstance()->fetchOne(
            "SELECT parentID FROM Application WHERE applicationID = ?",
            [$applicationId]
        );

        require_once '../Models/Admin.php';
        $admin = new Admin(
            $_SESSION["user_id"], "", "", null, null, null, "admin", "", ""
        );

        $result = $admin->ApproveApplication([
            "ApplicationId" => $applicationId,
            "ParentId"      => $appRow["parentID"] ?? null,
        ]);

        if ($result["status"] === "success") {
            $_SESSION["message"] = "Application approved successfully.";
        } else {
            $_SESSION["error"] = $result["message"];
        }

        header("Location: ../View/dashboard.php");
        exit;
    }

    $_SESSION["error"] = $applicationId_err;
    header("Location: ../View/dashboard.php");
    exit;
}

// ════════════════════════════════════════════════════════════════════════
//  ADMIN: Reject Application
// ════════════════════════════════════════════════════════════════════════
if ($action === "reject") {

    // Guard — only admins
    if (empty($_SESSION["user_id"]) || strtolower($_SESSION["user_role"] ?? "") !== "admin") {
        $_SESSION["error"] = "Access denied.";
        header("Location: ../View/login.php");      
        exit;
    }

    // ── Define variables ──────────────────────────────────────────────
    $applicationId = 0;
    $rejectionReason = "";
    $applicationId_err = $rejectionReason_err = "";

    // ── Validate Application ID ───────────────────────────────────────
    $input_appId = intval($_POST["application_id"] ?? 0);
    if ($input_appId <= 0) {
        $applicationId_err = "Invalid application ID.";
    } else {
        $applicationId = $input_appId;
    }

    // ── Validate Rejection Reason ─────────────────────────────────────
    $input_reason = trim($_POST["rejection_reason"] ?? "");
    if (empty($input_reason)) {
        $rejectionReason_err = "Please provide a reason for rejection.";
    } else {
        $rejectionReason = htmlspecialchars($input_reason);
    }

    // ── If no errors, reject ──────────────────────────────────────────
    if (empty($applicationId_err) && empty($rejectionReason_err)) {

        $appRow = Database::getInstance()->fetchOne(
            "SELECT parentID FROM Application WHERE applicationID = ?",
            [$applicationId]
        );

        require_once '../Models/Admin.php';
        $admin = new Admin(
            $_SESSION["user_id"], "", "", null, null, null, "admin", "", ""
        );

        $result = $admin->RejectApplication([
            "ApplicationId" => $applicationId,
            "Reason"        => $rejectionReason,
            "ParentId"      => $appRow["parentID"] ?? null,
        ]);

        if ($result["status"] === "success") {
            $_SESSION["message"] = "Application rejected.";
        } else {
            $_SESSION["error"] = $result["message"];
        }

        header("Location: ../View/dashboard.php");
        exit;
    }

    $_SESSION["error"] = implode(" | ", array_filter([$applicationId_err, $rejectionReason_err]));
    header("Location: ../View/dashboard.php");
    exit;
}

// ── Unknown action ────────────────────────────────────────────────────────────
$_SESSION["error"] = "Unknown action.";
header("Location: ../View/dashboard.php");
exit;
?>