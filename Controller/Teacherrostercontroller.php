<?php
// FR-44: Teacher Roster
// PHP page fetches all enrolled children for the teacher's courses from MySQL;
// renders HTML roster page with medical and allergy highlights.

session_start();

// Accessible by teacher or admin
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['teacher', 'admin'])) {
    http_response_code(403);
    header("location: ../index.php");
    exit("Access denied. Teachers and admins only.");
}

$teacher_id = $_SESSION['user_id'] ?? null;

if (empty($teacher_id) || !ctype_digit((string)$teacher_id)) {
    echo "Session error: could not identify teacher. Please log in again.";
    exit();
}

$rosterData = [];
$fetchError = "";

include_once '../Model/CourseAssignmentModel.php';
include_once '../Model/ChildModel.php';

$assignmentModel = new CourseAssignmentModel();
$childModel      = new ChildModel();

// Fetch all courses assigned to this teacher
$courses = $assignmentModel->getCoursesByTeacher($teacher_id);

if ($courses === false) {
    $fetchError = "Failed to load course assignments. Please try again later.";
} elseif (empty($courses)) {
    $fetchError = "No courses are currently assigned to you.";
} else {
    foreach ($courses as $course) {
        $course_id = $course['course_id'];

        if (empty($course_id) || !ctype_digit((string)$course_id)) {
            continue;
        }

        // Get all enrolled children for this course with medical profile
        $children = $childModel->getEnrolledChildrenWithMedical($course_id);

        if ($children === false) {
            $fetchError = "Failed to load roster for course ID $course_id.";
            break;
        }

        // Flag allergy/medical alerts for highlighted display in view
        foreach ($children as &$child) {
            $child['has_alert'] = !empty($child['allergies']) || !empty($child['medical_conditions']);
        }
        unset($child);

        $rosterData[] = [
            'course'   => $course,
            'children' => $children
        ];
    }
}
?>