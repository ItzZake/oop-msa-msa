<?php
// FR-33: Field Trip Digital Consent Form
// PHP form sends consent link to parents; signed responses stored in MySQL;
// admin status page updated.

$child_id = $event_id = $parent_signature = $consent_date = "";
$child_id_err = $event_id_err = $parent_signature_err = $consent_date_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate child_id
    $input_child_id = trim($_POST["child_id"]);
    if (empty($input_child_id)) {
        $child_id_err = "Child ID is required.";
    } elseif (!ctype_digit($input_child_id)) {
        $child_id_err = "Child ID must be a positive integer.";
    } else {
        $child_id = $input_child_id;
    }

    // Validate event_id
    $input_event_id = trim($_POST["event_id"]);
    if (empty($input_event_id)) {
        $event_id_err = "Event ID is required.";
    } elseif (!ctype_digit($input_event_id)) {
        $event_id_err = "Event ID must be a positive integer.";
    } else {
        $event_id = $input_event_id;
    }

    // Validate parent signature (full name as digital signature)
    $input_signature = trim($_POST["parent_signature"]);
    if (empty($input_signature)) {
        $parent_signature_err = "Please enter your full name as a digital signature.";
    } elseif (!filter_var($input_signature, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[a-zA-Z\s\-']+$/"]])) {
        $parent_signature_err = "Signature must contain valid name characters only.";
    } elseif (strlen($input_signature) > 100) {
        $parent_signature_err = "Signature must not exceed 100 characters.";
    } else {
        $parent_signature = htmlspecialchars($input_signature);
    }

    // Validate consent date
    $input_consent_date = trim($_POST["consent_date"]);
    if (empty($input_consent_date)) {
        $consent_date_err = "Please provide the consent date.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input_consent_date) ||
              !checkdate(
                  (int)substr($input_consent_date, 5, 2),
                  (int)substr($input_consent_date, 8, 2),
                  (int)substr($input_consent_date, 0, 4)
              )) {
        $consent_date_err = "Please enter a valid date (YYYY-MM-DD).";
    } else {
        $consent_date = $input_consent_date;
    }

    // If no errors, store consent record and update admin status
    if (empty($child_id_err) && empty($event_id_err) && empty($parent_signature_err) && empty($consent_date_err)) {
        include_once '../Model/ConsentModel.php';
        $consentModel = new ConsentModel();

        if ($consentModel->insertConsent($child_id, $event_id, $parent_signature, $consent_date)) {
            header("location: ../index.php");
            exit();
        } else {
            echo "Something went wrong while saving your consent. Please try again later.";
        }
    }
}
?>