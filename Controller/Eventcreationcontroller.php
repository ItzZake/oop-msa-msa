<?php
// FR-31: Event Creation & Management
// Admin creates events via a PHP form; data saved to MySQL events table;
// broadcast notification sent.

session_start();

// Only accessible by admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    header("location: ../index.php");
    exit("Access denied. Admins only.");
}

$event_title = $event_date = $event_description = $target_tag = "";
$event_title_err = $event_date_err = $event_description_err = $target_tag_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate event title
    $input_title = trim($_POST["event_title"]);
    if (empty($input_title)) {
        $event_title_err = "Please enter an event title.";
    } elseif (strlen($input_title) > 255) {
        $event_title_err = "Event title must not exceed 255 characters.";
    } else {
        $event_title = htmlspecialchars($input_title);
    }

    // Validate event date
    $input_date = trim($_POST["event_date"]);
    if (empty($input_date)) {
        $event_date_err = "Please enter an event date.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input_date) ||
              !checkdate(
                  (int)substr($input_date, 5, 2),
                  (int)substr($input_date, 8, 2),
                  (int)substr($input_date, 0, 4)
              )) {
        $event_date_err = "Please enter a valid date (YYYY-MM-DD).";
    } else {
        $event_date = $input_date;
    }

    // Validate event description
    $input_description = trim($_POST["event_description"]);
    if (empty($input_description)) {
        $event_description_err = "Please enter an event description.";
    } elseif (strlen($input_description) > 1000) {
        $event_description_err = "Description must not exceed 1000 characters.";
    } else {
        $event_description = htmlspecialchars($input_description);
    }

    // Validate target tag (e.g. class group or 'all')
    $input_tag = trim($_POST["target_tag"]);
    if (empty($input_tag)) {
        $target_tag_err = "Please specify a target audience tag.";
    } elseif (!filter_var($input_tag, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[a-zA-Z0-9_\-\s]+$/"]])) {
        $target_tag_err = "Target tag contains invalid characters.";
    } else {
        $target_tag = $input_tag;
    }

    // If no errors, insert event and send notification
    if (empty($event_title_err) && empty($event_date_err) && empty($event_description_err) && empty($target_tag_err)) {
        include_once '../Model/EventModel.php';
        include_once '../Model/NotificationModel.php';

        $eventModel        = new EventModel();
        $notificationModel = new NotificationModel();

        $eventId = $eventModel->insertEvent($event_title, $event_date, $event_description, $target_tag);

        if ($eventId) {
            // Send broadcast notification to targeted recipients
            $notified = $notificationModel->sendBroadcast($target_tag, "New Event: $event_title on $event_date");

            if (!$notified) {
                echo "Warning: Event created but broadcast notification failed.";
            } else {
                header("location: ../index.php");
                exit();
            }
        } else {
            echo "Something went wrong while creating the event. Please try again later.";
        }
    }
}
?>