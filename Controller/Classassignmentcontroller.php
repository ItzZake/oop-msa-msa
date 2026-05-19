<?php
// FR-43: Class Assignment Management
// Admin assigns teachers to courses via PHP;
// MySQL checks for time slot conflicts before saving.

session_start();

// Admin only
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    header("location: ../index.php");
    exit("Access denied. Admins only.");
}

$teacher_id = $course_id = "";
$teacher_id_err = $course_id_err = $conflict_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate teacher_id
    $input_teacher_id = trim($_POST["teacher_id"]);
    if (empty($input_teacher_id)) {
        $teacher_id_err = "Please select a teacher.";
    } elseif (!ctype_digit($input_teacher_id)) {
        $teacher_id_err = "Teacher ID must be a positive integer.";
    } else {
        $teacher_id = $input_teacher_id;
    }

    // Validate course_id
    $input_course_id = trim($_POST["course_id"]);
    if (empty($input_course_id)) {
        $course_id_err = "Please select a course.";
    } elseif (!ctype_digit($input_course_id)) {
        $course_id_err = "Course ID must be a positive integer.";
    } else {
        $course_id = $input_course_id;
    }

    if (empty($teacher_id_err) && empty($course_id_err)) {
        include_once '../Models/CourseAssignment.php';
        include_once '../Models/Course.php';
        $assignmentModel = new CourseAssignment();
        $courseModel = new Course();

        // Check for time slot conflict before assigning
        $hasConflict = $courseModel->HasSchedulingConflict($teacher_id, $course_id);

        if ($hasConflict === true) {
            $conflict_err = "Assignment blocked: this teacher has a time slot conflict with the selected course.";
        } elseif ($hasConflict === false) {
            // No conflict — proceed with assignment
            if ($assignmentModel->insertAssignment($teacher_id, $course_id)) {
                header("location: ../index.php");
                exit();
            } else {
                echo "Something went wrong while saving the assignment. Please try again later.";
            }
        } else {
            echo "Could not verify time slot availability. Please try again later.";
        }
    }
}
?>