<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Child.php';
require_once '/../Models/Course.php';
require_once '/../Models/Enrollment.php';
require_once '/../Models/Waitlist.php';

$child_id  = (int) ($_POST['child_id']  ?? 0);
$course_id = (int) ($_POST['course_id'] ?? 0);

if ($child_id && $course_id) {
    $Course = new Course();
    $course = $Course->GetCourseById($course_id);
    
    $Child = new Child();
    $child = $Child->GetChildById($child_id);

    // FR-20: Age eligibility check
    if ($child && $course && $child->GetAge() >= $course->GetMinAge()
                          && $child->GetAge() <= $course->GetMaxAge()) {

        $Enrollment = new Enrollment();

        // FR-22: Seat availability check
        if ($course->GetCurrentEnrollment() < $course->GetMaxCapacity()) {
            $Enrollment->Enroll($child_id, $course_id);
            $_SESSION['enroll_message'] = "Enrolled successfully!";
            header("Location: /Enrollment.php?status=success");
        } else {
            // FR-22: Full → waitlist
            $Waitlist = new Waitlist();
            $ParentId = $child->GetParentID();
            $Waitlist->AssignWaitlist($child_id, $course_id, $ParentId);
            $_SESSION['enroll_message'] = "Course full. Added to waitlist.";
            header("Location: /Enrollment.php?status=waitlisted");
        }
    } else {
        header("Location: /Enrollment.php?error=age_ineligible");
    }
} else {
    header("Location: /Enrollment.php?error=missing_data");
}
exit();