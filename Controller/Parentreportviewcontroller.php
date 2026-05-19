<?php
// FR-39: Parent View of Child's Reports
// PHP page fetches published reports from MySQL and renders them for the parent;
// PDF download link available.

session_start();

// Accessible by parent role only
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'parent') {
    http_response_code(403);
    header("location: ../index.php");
    exit("Access denied. Parents only.");
}

$child_id = $session_parent_id = "";
$child_id_err = "";
$reportData = [];

// Parent ID comes from the session
$session_parent_id = $_SESSION['user_id'] ?? null;

if (empty($session_parent_id) || !ctype_digit((string)$session_parent_id)) {
    echo "Session error: could not identify parent. Please log in again.";
    exit();
}

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

    if (empty($child_id_err)) {
        include_once '../Models/ProgressReport.php';
        include_once '../Models/Child.php';

        $reportModel = new ProgressReport();
        $childModel  = new Child();

        // Verify the child belongs to this parent
        if (!$childModel->ChildBelongsToParent($child_id, $session_parent_id)) {
            echo "Access denied: this child is not associated with your account.";
            exit();
        }

        // Fetch only Published reports for this child
        $reportData = $reportModel->GetPublishedReportsByChild($child_id);

        if ($reportData === false) {
            echo "Something went wrong while fetching reports. Please try again later.";
            exit();
        }

        // Handle PDF download for a specific report
        if (isset($_POST["download_pdf"]) && $_POST["download_pdf"] === "1" && isset($_POST["report_id"])) {
            $report_id = trim($_POST["report_id"]);
            if (ctype_digit($report_id)) {
                include_once '../Util/PdfExporter.php';
                $exporter = new PdfExporter();
                $exporter->exportProgressReport((int)$report_id, $child_id);
                exit();
            }
        }
    }
}
?>