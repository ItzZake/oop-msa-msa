<?php
// FR-34: Event Attendee List
// PHP-generated page showing all RSVP and consent statuses from MySQL;
// with PDF/CSV export.

session_start();

// Accessible by admin only
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    header("location: ../index.php");
    exit("Access denied. Admins only.");
}

$event_id = "";
$event_id_err = "";
$attendeeData = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate event_id
    $input_event_id = trim($_POST["event_id"]);
    if (empty($input_event_id)) {
        $event_id_err = "Please provide an event ID.";
    } elseif (!ctype_digit($input_event_id)) {
        $event_id_err = "Event ID must be a positive integer.";
    } else {
        $event_id = $input_event_id;
    }

    if (empty($event_id_err)) {
        include_once '../Model/RsvpModel.php';
        include_once '../Model/ConsentModel.php';

        $rsvpModel    = new RsvpModel();
        $consentModel = new ConsentModel();

        // Fetch all attendees with their RSVP + consent status
        $attendeeData = $rsvpModel->getAttendeesWithConsentByEvent($event_id);

        if ($attendeeData === false) {
            echo "Something went wrong while fetching attendee data. Please try again later.";
            exit();
        }

        // Handle CSV export
        if (isset($_POST["export_csv"]) && $_POST["export_csv"] === "1") {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="attendees_event_' . $event_id . '_' . date('Y-m-d') . '.csv"');

            $output = fopen('php://output', 'w');
            fputcsv($output, ['Child ID', 'Child Name', 'RSVP Response', 'Consent Status', 'Parent Name']);

            foreach ($attendeeData as $row) {
                fputcsv($output, [
                    $row['child_id']       ?? '',
                    $row['child_name']     ?? '',
                    $row['rsvp_response']  ?? '',
                    $row['consent_status'] ?? '',
                    $row['parent_name']    ?? ''
                ]);
            }

            fclose($output);
            exit();
        }

        // Handle PDF export
        if (isset($_POST["export_pdf"]) && $_POST["export_pdf"] === "1") {
            include_once '../Util/PdfExporter.php';
            $exporter = new PdfExporter();
            $exporter->exportAttendeeList($attendeeData, $event_id);
            exit();
        }
    }
}
?>