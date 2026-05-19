<?php
// FR-32: RSVP & Attendance Confirmation
// Parents submit RSVP via a PHP form; response stored in MySQL
// and reflected on the admin page.

$child_id = $event_id = $rsvp_response = "";
$child_id_err = $event_id_err = $rsvp_response_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate child_id
    $input_child_id = trim($_POST["child_id"]);
    if (empty($input_child_id)) {
        $child_id_err = "Please provide the child ID.";
    } elseif (!ctype_digit($input_child_id)) {
        $child_id_err = "Child ID must be a positive integer.";
    } else {
        $child_id = $input_child_id;
    }

    // Validate event_id
    $input_event_id = trim($_POST["event_id"]);
    if (empty($input_event_id)) {
        $event_id_err = "Please provide the event ID.";
    } elseif (!ctype_digit($input_event_id)) {
        $event_id_err = "Event ID must be a positive integer.";
    } else {
        $event_id = $input_event_id;
    }

    // Validate RSVP response (yes / no / maybe)
    $input_rsvp = strtolower(trim($_POST["rsvp_response"]));
    $allowed_responses = ["yes", "no", "maybe"];
    if (empty($input_rsvp)) {
        $rsvp_response_err = "Please select an RSVP response.";
    } elseif (!in_array($input_rsvp, $allowed_responses)) {
        $rsvp_response_err = "Invalid RSVP response. Choose 'yes', 'no', or 'maybe'.";
    } else {
        $rsvp_response = $input_rsvp;
    }

    // If no errors, save RSVP to MySQL
    if (empty($child_id_err) && empty($event_id_err) && empty($rsvp_response_err)) {
        include_once '../Models/Rsvp.php';
        $rsvpModel = new Rsvp();

        $timestamp = date("Y-m-d H:i:s");

        // Upsert: update if already responded, insert if new
        if ($rsvpModel->upsertRsvp($child_id, $event_id, $rsvp_response, $timestamp)) {
            header("location: ../index.php");
            exit();
        } else {
            echo "Something went wrong while saving your RSVP. Please try again later.";
        }
    }
}
?>