<?php
/**
 * Quick test to verify ProfileController fix
 * Tests with teacher ID 39 (John Smith)
 */

require_once __DIR__ . '/Models/Database.php';

echo "=== ProfileController Database Retrieval Test ===\n\n";

$db = Database::getInstance();

// Use John Smith (userID = 39) as the test user
$userId = 39;

echo "Testing with Teacher User ID: $userId\n";
echo "Expected: John Smith\n\n";

// Test 1: Get user data
echo "Step 1: Fetching user data...\n";
$user = $db->fetchOne("SELECT userID, email, firstname, Lastname FROM `user` WHERE userID = ?", [$userId]);
if ($user) {
    echo "✓ User found: {$user['firstname']} {$user['Lastname']} ({$user['email']})\n\n";
} else {
    echo "✗ User not found\n\n";
}

if ($user) {
    // Test 2: Get teacher data
    echo "Step 2: Fetching teacher data...\n";
    $teacher = $db->fetchOne("SELECT teacherID, exprience, qualifications, specialization, phone FROM `teacher` WHERE userID = ?", [$userId]);
    if ($teacher) {
        echo "✓ Teacher record found: teacherID=" . $teacher['teacherID'] . "\n\n";
    } else {
        echo "✗ Teacher record not found\n\n";
    }
    
    if ($teacher) {
        // Test 3: Get students (THE KEY TEST - using userID, not teacherID!)
        echo "Step 3: Fetching students assigned to this teacher...\n";
        echo "Using assignedTeacherID = $userId (userID, not teacherID)\n";
        $students = $db->fetchAll(
            "SELECT DISTINCT c.name as childName, c.childID, c.dateOfBirth, c.gender
             FROM `child` c
             JOIN `enrollment` e ON c.childID = e.childID
             JOIN `course` co ON e.courseID = co.courseID
             WHERE co.assignedTeacherID = ?
             ORDER BY c.name",
            [$userId]
        );
        
        if ($students && count($students) > 0) {
            echo "✓ Students found: " . count($students) . "\n";
            foreach ($students as $student) {
                echo "  - {$student['childName']} (ID: {$student['childID']})\n";
            }
        } else {
            echo "✓ No students enrolled (This is OK if teacher has no students)\n";
        }
    }
}

echo "\n=== Test Complete ===\n";
?>
