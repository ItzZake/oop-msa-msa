<?php
// FR-16 — Field Trip Approval Request
// Auto-notifies all parents of enrolled children when a field trip Event is created,
// attaching a consent form link.
//
// Called in TWO ways:
//   1. After admin saves a field-trip Event (POST action="notify_field_trip")
//   2. By a cron job to chase unsigned consent forms
//
// Cron example (runs nightly):
//   0 20 * * * php /var/www/html/Controllers/field_trip_notify.php cron >> /var/log/cron_fieldtrip.log 2>&1

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/NotificationManager.php';

$is_cron = (PHP_SAPI === "cli" && ($argv[1] ?? "") === "cron");

// ════════════════════════════════════════════════════════════════════════
//  WEB: Admin just created / updated a field trip → send initial notices
// ════════════════════════════════════════════════════════════════════════
if (!$is_cron) {

    // Guard — only admins (web context)
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION["user_id"]) || strtolower($_SESSION["user_role"] ?? "") !== "admin") {
        $_SESSION["error"] = "Access denied.";
        header("Location: ../View/login.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: ../View/dashboard.php");
        exit;
    }

    // ── Define variables ──────────────────────────────────────────────
    $eventId     = 0;
    $eventId_err = "";

    // ── Validate Event ID ─────────────────────────────────────────────
    $input_eventId = intval($_POST["event_id"] ?? 0);
    if ($input_eventId <= 0) {
        $eventId_err = "Invalid event ID.";
    } else {
        $eventId = $input_eventId;
    }

    if (!empty($eventId_err)) {
        $_SESSION["error"] = $eventId_err;
        header("Location: ../View/dashboard.php");
        exit;
    }

    // ── Fetch event and confirm it is a field trip ────────────────────
    $event = Database::getInstance()->fetchOne(
        "SELECT eventID, title, eventDate, location, rsvpDeadline, isFieldTrip
         FROM Event WHERE eventID = ? AND isFieldTrip = 1 AND isCancelled = 0",
        [$eventId]
    );

    if (!$event) {
        $_SESSION["error"] = "Field trip event not found.";
        header("Location: ../View/dashboard.php");
        exit;
    }

    $sent = notifyFieldTripParents($event);

    $_SESSION["message"] = "Field trip notifications sent to {$sent} parent(s).";
    header("Location: ../View/dashboard.php");
    exit;
}

// ════════════════════════════════════════════════════════════════════════
//  CRON: Chase unsigned consent forms for upcoming field trips
// ════════════════════════════════════════════════════════════════════════
if ($is_cron) {

    // Fetch field trips happening in the next 14 days with unsigned consents
    $upcoming = Database::getInstance()->fetchAll(
        "SELECT e.eventID, e.title, e.eventDate, e.location, e.rsvpDeadline
         FROM Event e
         WHERE e.isFieldTrip  = 1
           AND e.isCancelled  = 0
           AND e.eventDate    BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 14 DAY)",
        []
    );

    $total_sent = 0;

    foreach ($upcoming as $event) {
        // Find parents with unsigned consent forms for this event
        $unsigned = Database::getInstance()->fetchAll(
            "SELECT cf.consentID, cf.parentID, cf.childID,
                    u.userID, c.name AS childName
             FROM ConsentForm cf
             JOIN Parent p ON p.parentID = cf.parentID
             JOIN User   u ON u.userID   = p.userID
             JOIN Child  c ON c.childID  = cf.childID
             WHERE cf.eventID  = ?
               AND cf.isSigned = 0",
            [$event["eventID"]]
        );

        $manager = NotificationManager::getInstance();

        foreach ($unsigned as $row) {
            $childName   = htmlspecialchars($row["childName"]);
            $eventTitle  = htmlspecialchars($event["title"]);
            $eventDate   = date("d M Y", strtotime($event["eventDate"]));
            $consentLink = "https://yourdomain.com/View/consent.php?id=" . $row["consentID"];

            $message = "Reminder: Consent form required for {$childName} "
                     . "for the field trip '{$eventTitle}' on {$eventDate}. "
                     . "Please sign here: {$consentLink}";

            $manager->NotifyUser((int) $row["userID"], $message, "Event");

            // Increment reminder counter
            Database::getInstance()->query(
                "UPDATE ConsentForm SET reminderCount = reminderCount + 1 WHERE consentID = ?",
                [$row["consentID"]]
            );

            $total_sent++;
            echo "[" . date("Y-m-d H:i:s") . "] Consent reminder → userID={$row['userID']} | event={$event['eventID']}\n";
        }
    }

    $status = "Success";
    Database::getInstance()->query(
        "INSERT INTO CronJob (jobName, lastRun, lastStatus)
         VALUES ('field_trip_consent', NOW(), ?)
         ON DUPLICATE KEY UPDATE lastRun = NOW(), lastStatus = ?",
        [$status, $status]
    );

    echo "[" . date("Y-m-d H:i:s") . "] Done. Reminders sent: {$total_sent}\n";
    exit;
}

// ════════════════════════════════════════════════════════════════════════
//  SHARED HELPER
// ════════════════════════════════════════════════════════════════════════

/**
 * Notify all enrolled parents for a field trip event and create consent forms.
 */
function notifyFieldTripParents(array $event): int
{
    $manager    = NotificationManager::getInstance();
    $sent_count = 0;
    $eventId    = (int) $event["eventID"];
    $eventTitle = htmlspecialchars($event["title"]);
    $eventDate  = date("d M Y", strtotime($event["eventDate"]));
    $deadline   = $event["rsvpDeadline"]
                  ? date("d M Y", strtotime($event["rsvpDeadline"]))
                  : "N/A";

    // Get all active enrolled children
    $enrolled = Database::getInstance()->fetchAll(
        "SELECT DISTINCT
             c.childID,
             c.name     AS childName,
             p.parentID,
             u.userID
         FROM Enrollment e
         JOIN Child  c ON c.childID  = e.childID
         JOIN Parent p ON p.parentID = c.parentID
         JOIN User   u ON u.userID   = p.userID
         WHERE e.status = 'Active'",
        []
    );

    foreach ($enrolled as $row) {
        $childId  = (int) $row["childID"];
        $parentId = (int) $row["parentID"];
        $userId   = (int) $row["userID"];

        // Create consent form row if it does not exist yet
        $exists = Database::getInstance()->fetchOne(
            "SELECT consentID FROM ConsentForm WHERE eventID = ? AND childID = ?",
            [$eventId, $childId]
        );

        if (!$exists) {
            Database::getInstance()->query(
                "INSERT INTO ConsentForm (eventID, parentID, childID, isSigned, sentAt, reminderCount)
                 VALUES (?, ?, ?, 0, NOW(), 0)",
                [$eventId, $parentId, $childId]
            );

            $consentRow = Database::getInstance()->fetchOne(
                "SELECT consentID FROM ConsentForm WHERE eventID = ? AND childID = ?",
                [$eventId, $childId]
            );
        } else {
            $consentRow = $exists;
        }

        $consentLink = "https://yourdomain.com/View/consent.php?id=" . ($consentRow["consentID"] ?? 0);
        $childName   = htmlspecialchars($row["childName"]);

        $message = "Field trip notice for {$childName}: '{$eventTitle}' on {$eventDate}. "
                 . "RSVP deadline: {$deadline}. "
                 . "Please sign the consent form here: {$consentLink}";

        $manager->NotifyUser($userId, $message, "Event");
        $sent_count++;
    }

    return $sent_count;
}
?>