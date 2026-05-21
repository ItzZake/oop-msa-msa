<?php
/**
 * Test script to verify ProfileController database retrieval
 */

session_start();
require_once 'Models/Database.php';

// Simulate a teacher session
$_SESSION['user_id'] = 1; // Change this to match an actual teacher ID
$_SESSION['user_role'] = 'Teacher';

echo "=== ProfileController Test ===\n\n";

$db = Database::getInstance();

// Test 1: Check database connection
echo "Test 1: Database Connection\n";
try {
    $conn = $db->getConnection();
    if ($conn) {
        echo "✓ Database connection successful\n\n";
    } else {
        echo "✗ Database connection failed\n\n";
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n\n";
}

// Test 2: Verify user exists
echo "Test 2: User Data Retrieval\n";
$userId = $_SESSION['user_id'];
$user = $db->fetchOne("SELECT userID, email, firstname, Lastname FROM `user` WHERE userID = ?", [$userId]);
echo "Query: SELECT userID, email, firstname, Lastname FROM `user` WHERE userID = ?\n";
echo "Result: " . json_encode($user) . "\n";
if ($user) {
    echo "✓ User found\n\n";
} else {
    echo "✗ User not found\n";
    echo "Checking available users:\n";
    $allUsers = $db->fetchAll("SELECT userID, email, firstname, Lastname, Role FROM `user` LIMIT 5");
    echo json_encode($allUsers, JSON_PRETTY_PRINT) . "\n\n";
}

// Test 3: Verify teacher data
if ($user) {
    echo "Test 3: Teacher Data Retrieval\n";
    $teacher = $db->fetchOne("SELECT teacherID, exprience, qualifications, specialization, phone FROM `teacher` WHERE userID = ?", [$userId]);
    echo "Query: SELECT teacherID, exprience, qualifications, specialization, phone FROM `teacher` WHERE userID = ?\n";
    echo "Result: " . json_encode($teacher) . "\n";
    if ($teacher) {
        echo "✓ Teacher record found\n\n";
        
        // Test 4: Verify student enrollment
        echo "Test 4: Student Data Retrieval\n";
        $students = $db->fetchAll(
            "SELECT DISTINCT c.name as childName, c.childID, c.dateOfBirth, c.gender
             FROM `child` c
             JOIN `enrollment` e ON c.childID = e.childID
             JOIN `course` co ON e.courseID = co.courseID
             WHERE co.assignedTeacherID = ?
             ORDER BY c.name",
            [$teacher['teacherID']]
        );
        echo "Assigned Teacher ID: " . $teacher['teacherID'] . "\n";
        echo "Students found: " . count($students) . "\n";
        echo "Result: " . json_encode($students, JSON_PRETTY_PRINT) . "\n";
        
    } else {
        echo "✗ Teacher record not found\n";
        echo "Checking available teacher records:\n";
        $allTeachers = $db->fetchAll("SELECT teacherID, userID, phone, qualifications FROM `teacher` LIMIT 5");
        echo json_encode($allTeachers, JSON_PRETTY_PRINT) . "\n\n";
    }
}

// Test 5: Check table structure
echo "\nTest 5: Table Counts\n";
$tables = ['user', 'teacher', 'child', 'enrollment', 'course'];
foreach ($tables as $table) {
    $result = $db->fetchOne("SELECT COUNT(*) as cnt FROM `" . $table . "`");
    echo "$table: " . ($result['cnt'] ?? 0) . " records\n";
}

echo "\n=== End of Test ===\n";
?>
