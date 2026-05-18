<?php
// FR-38: Developmental Milestone Tracking
// Teacher marks milestones via PHP form;
// records inserted into MySQL milestones table; admin notified.

$child_id = $milestone_name = $milestone_status = $milestone_domain = "";
$child_id_err = $milestone_name_err = $milestone_status_err = $milestone_domain_err = "";

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

    // Validate milestone name
    $input_milestone_name = trim($_POST["milestone_name"]);
    if (empty($input_milestone_name)) {
        $milestone_name_err = "Please enter the milestone name.";
    } elseif (strlen($input_milestone_name) > 255) {
        $milestone_name_err = "Milestone name must not exceed 255 characters.";
    } else {
        $milestone_name = htmlspecialchars($input_milestone_name);
    }

    // Validate milestone status (e.g. Not Started / In Progress / Achieved)
    $input_status = trim($_POST["milestone_status"]);
    $allowed_statuses = ["Not Started", "In Progress", "Achieved"];
    if (empty($input_status)) {
        $milestone_status_err = "Please select a milestone status.";
    } elseif (!in_array($input_status, $allowed_statuses)) {
        $milestone_status_err = "Invalid milestone status selected.";
    } else {
        $milestone_status = $input_status;
    }

    // Validate milestone domain (e.g. Cognitive / Social / Motor / Language)
    $input_domain = trim($_POST["milestone_domain"]);
    $allowed_domains = ["Cognitive", "Social", "Motor", "Language"];
    if (empty($input_domain)) {
        $milestone_domain_err = "Please select a milestone domain.";
    } elseif (!in_array($input_domain, $allowed_domains)) {
        $milestone_domain_err = "Invalid domain selected.";
    } else {
        $milestone_domain = $input_domain;
    }

    // If no errors, insert milestone and notify admin
    if (empty($child_id_err) && empty($milestone_name_err) && empty($milestone_status_err) && empty($milestone_domain_err)) {
        include_once '../Model/MilestoneModel.php';
        include_once '../Model/NotificationModel.php';

        $milestoneModel    = new MilestoneModel();
        $notificationModel = new NotificationModel();

        $inserted = $milestoneModel->insertMilestone($child_id, $milestone_name, $milestone_status, $milestone_domain);

        if ($inserted) {
            // Notify admin of the new milestone record
            $notificationModel->notifyAdmin(
                "New milestone recorded for child ID $child_id: $milestone_name ($milestone_status)"
            );

            header("location: ../index.php");
            exit();
        } else {
            echo "Something went wrong while saving the milestone. Please try again later.";
        }
    }
}
?>