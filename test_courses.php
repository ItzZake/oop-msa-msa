<?php
require_once __DIR__ . '/Models/Database.php';
require_once __DIR__ . '/Models/Course.php';
require_once __DIR__ . '/Models/Child.php';

echo "=== Course and Enrollment Report for User 39 (John Smith) ===\n\n";

try {
    $db = Database::getInstance();
    
    // Step 1: Get teacherID from userID 39
    echo "[Step 1] Getting teacher ID for user 39...\n";
    $sql = "SELECT teacherID, firstName, lastName FROM teacher WHERE userID = 39";
    $teacher = $db->query($sql)->fetchOne();
    
    if (!$teacher) {
        echo "ERROR: No teacher found for userID 39\n";
        exit(1);
    }
    
    $teacherID = $teacher['teacherID'];
    echo "? Found Teacher: {$teacher['firstName']} {$teacher['lastName']} (TeacherID: {$teacherID})\n\n";
    
    // Step 2: Get all courses assigned to this teacher
    echo "[Step 2] Getting all courses assigned to teacher {$teacherID}...\n";
    $course = new Course();
    $courses = $course->GetTeacherCourses($teacherID);
    
    if (empty($courses)) {
        echo "No courses assigned to this teacher.\n";
        exit(0);
    }
    
    echo "? Found " . count($courses) . " course(s) assigned\n\n";
    
    // Step 3: Display each course and its enrolled students
    foreach ($courses as $c) {
        echo "-------------------------------------------\n";
        echo "Course: {$c['name']} (ID: {$c['courseID']})\n";
        echo "Description: {$c['description']}\n";
        echo "Age Range: {$c['ageMin']} - {$c['ageMax']} years\n";
        echo "Max Capacity: {$c['maxCapacity']}\n";
        echo "Current Enrollment: {$c['enrolledStudents']}\n";
        echo "Price: \${$c['price']}\n";
        
        // Get enrolled students for this course
        $sql = "SELECT 
                            c.childID, 
                            c.firstName, 
                            c.lastName,
                            c.dateOfBirth,
                            p.firstName as parentFirstName,
                            p.lastName as parentLastName,
                            p.email as parentEmail,
                            e.enrolledAt,
                            e.status
                        FROM child c
                        INNER JOIN enrollment e ON c.childID = e.childID
                        INNER JOIN parent p ON c.parentID = p.parentID
                        WHERE e.courseID = ? AND e.status = 'Active'
                        ORDER BY c.firstName ASC";
        
        $students = $db->query($sql, [$c['courseID']])->fetchAll();
        
        if (empty($students)) {
            echo "\n  No active enrollments.\n";
        } else {
            echo "\n  Enrolled Students (" . count($students) . "):\n";
            foreach ($students as $idx => $s) {
                echo "  " . ($idx + 1) . ". {$s['firstName']} {$s['lastName']} (Child ID: {$s['childID']})\n";
                echo "     DOB: {$s['dateOfBirth']}\n";
                echo "     Parent: {$s['parentFirstName']} {$s['parentLastName']}\n";
                echo "     Parent Email: {$s['parentEmail']}\n";
                echo "     Enrolled: {$s['enrolledAt']}\n";
            }
        }
        echo "\n";
    }
    
    echo "=== Report Complete ===\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
