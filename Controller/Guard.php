<?php
// FR-03 — Role-Based Access Control
// Include this file at the TOP of any protected page with:
//
//   $allowed_roles = ['admin'];          // set before including
//   require_once '../Controllers/guard.php';
//
// The script checks the session role and redirects away if unauthorised.
// It does NOT produce any output itself.

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// ── Check: is the user even logged in? ────────────────────────────────────────
if (empty($_SESSION["user_id"])) {
    $_SESSION["error"] = "Please sign in to continue.";
    header("Location: ../View/login.php");
    exit;
}

// ── Check: does the user's role match the allowed list? ──────────────────────
// Pages that don't set $allowed_roles allow any authenticated user through.
if (isset($allowed_roles) && !empty($allowed_roles)) {

    $session_role = strtolower($_SESSION["user_role"] ?? "");

    $allowed_normalised = array_map("strtolower", $allowed_roles);

    if (!in_array($session_role, $allowed_normalised, true)) {
        http_response_code(403);
        $_SESSION["error"] = "You do not have permission to access that page.";

        // Send each role back to their own landing page
        if ($session_role === "admin") {
            header("Location: ../View/dashboard.php");
        } elseif ($session_role === "teacher") {
            header("Location: ../View/attendance.php");
        } else {
            header("Location: ../View/profiles.php");
        }
        exit;
    }
}
// If we reach here the user is authorised — the including page continues normally.
?>