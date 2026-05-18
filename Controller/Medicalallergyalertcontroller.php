<?php
// FR-41: Medical & Allergy Alerts for Teachers
// PHP roster page prominently renders allergy and medical data from MySQL for each child;
// highlighted alerts on roster and attendance pages.

session_start();

// Accessible by teacher or admin
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['teacher', 'admin'])) {
    http_response_code(403);
    header("location: ../index.php");
    exit("Access denied. Teachers and admins only.");
}

$teacher_id = "";
$teacher_id_err = "";
$rosterData = [];

// Teacher ID from session
$teacher_id = $_SESSION['user_id'] ?? null;

if (empty($teacher_id) || !ctype_digit((string)$teacher_id)) {
    echo "Session error: could not identify teacher. Please log in again.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "GET") {

    include_once '../Model/ChildModel.php';
    $childModel = new ChildModel();

    // Fetch all children assigned to this teacher with their medical profiles
    $rosterData = $childModel->getChildrenWithMedicalAlerts($teacher_id);

    if ($rosterData === false) {
        echo "Something went wrong while loading the roster. Please try again later.";
        exit();
    }

    // Flag children with active alerts for highlighted display in the view
    foreach ($rosterData as &$child) {
        $child['has_alert'] = !empty($child['allergies']) || !empty($child['medical_conditions']);
    }
    unset($child); // Break reference after loop
}
?>