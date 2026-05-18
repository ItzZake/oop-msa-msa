<?php
// FR-35: Reminder Before Event Date
// PHP cron job that sends reminders at 48 and 24 hours before every event
// to all confirmed RSVPs via email and in-app notifications.

// Only allow CLI or internal cron execution
if (php_sapi_name() !== 'cli' && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
    http_response_code(403);
    exit("Access denied.");
}

include_once '../Model/EventModel.php';
include_once '../Model/RsvpModel.php';
include_once '../Model/NotificationModel.php';

$eventModel        = new EventModel();
$rsvpModel         = new RsvpModel();
$notificationModel = new NotificationModel();

$now = new DateTime();

// Define reminder windows: 48 hours and 24 hours before event
$reminderWindows = [
    ['hours' => 48, 'label' => '48-hour'],
    ['hours' => 24, 'label' => '24-hour'],
];

foreach ($reminderWindows as $window) {
    $targetTime = (clone $now)->modify("+{$window['hours']} hours");
    $targetDate = $targetTime->format('Y-m-d');

    // Fetch confirmed events on the target date
    $upcomingEvents = $eventModel->getConfirmedEventsByDate($targetDate);

    if (empty($upcomingEvents)) {
        echo "No events found for {$window['label']} reminder window.\n";
        continue;
    }

    foreach ($upcomingEvents as $event) {
        $event_id    = $event['event_id'];
        $event_title = $event['event_title'];
        $event_date  = $event['event_date'];

        // Validate event_id
        if (empty($event_id) || !ctype_digit((string)$event_id)) {
            echo "Warning: Invalid event ID encountered, skipping.\n";
            continue;
        }

        // Get all confirmed RSVPs for this event
        $confirmedRsvps = $rsvpModel->getConfirmedRsvpsByEvent($event_id);

        if (empty($confirmedRsvps)) {
            echo "Event ID $event_id: No confirmed RSVPs to remind.\n";
            continue;
        }

        $successCount = 0;
        foreach ($confirmedRsvps as $rsvp) {
            $child_id  = $rsvp['child_id'];
            $parent_id = $rsvp['parent_id'];

            // Send email reminder
            $emailSent = $notificationModel->sendEmailReminder(
                $parent_id,
                "Reminder: $event_title is in {$window['hours']} hours ($event_date)"
            );

            // Send in-app notification
            $inAppSent = $notificationModel->sendInAppNotification(
                $parent_id,
                "Event Reminder",
                "$event_title starts on $event_date. Don't forget!"
            );

            if ($emailSent && $inAppSent) {
                $successCount++;
            } else {
                echo "Warning: Reminder partially failed for parent ID $parent_id (Event $event_id).\n";
            }
        }

        echo "Event ID $event_id ({$window['label']} reminder): $successCount reminder(s) sent.\n";
    }
}
?>