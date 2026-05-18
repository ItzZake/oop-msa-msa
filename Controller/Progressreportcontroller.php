<?php
// FR-37: Progress Report by Teacher
// Teacher submits a PHP form to save a progress report;
// saved to MySQL progress_reports table with status Draft or Pending Review.

$child_id = $observations = $ratings = $report_status = "";
$child_id_err = $observations_err = $ratings_err = $report_status_err = "";

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

    // Validate observations
    $input_observations = trim($_POST["observations"]);
    if (empty($input_observations)) {
        $observations_err = "Please enter observations.";
    } elseif (strlen($input_observations) > 2000) {
        $observations_err = "Observations must not exceed 2000 characters.";
    } else {
        $observations = htmlspecialchars($input_observations);
    }

    // Validate ratings (expected as numeric 1-5)
    $input_ratings = trim($_POST["ratings"]);
    if (empty($input_ratings)) {
        $ratings_err = "Please provide a rating.";
    } elseif (!ctype_digit($input_ratings) || (int)$input_ratings < 1 || (int)$input_ratings > 5) {
        $ratings_err = "Rating must be a whole number between 1 and 5.";
    } else {
        $ratings = $input_ratings;
    }

    // Validate report status (Draft / Pending Review)
    $input_status = trim($_POST["report_status"]);
    $allowed_statuses = ["Draft", "Pending Review"];
    if (empty($input_status)) {
        $report_status_err = "Please select a report status.";
    } elseif (!in_array($input_status, $allowed_statuses)) {
        $report_status_err = "Invalid status. Choose 'Draft' or 'Pending Review'.";
    } else {
        $report_status = $input_status;
    }

    // If no errors, insert the progress report
    if (empty($child_id_err) && empty($observations_err) && empty($ratings_err) && empty($report_status_err)) {
        include_once '../Model/ProgressReportModel.php';
        $reportModel = new ProgressReportModel();

        if ($reportModel->insertReport($child_id, $observations, $ratings, $report_status)) {
            header("location: ../index.php");
            exit();
        } else {
            echo "Something went wrong while saving the report. Please try again later.";
        }
    }
}
?>