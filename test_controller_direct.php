<?php
/**
 * Test ProfileController endpoint directly
 * Simulates a logged-in teacher session
 */

// Start session and set teacher session data
session_start();
$_SESSION['user_id'] = 39; // John Smith
$_SESSION['user_role'] = 'Teacher';

echo "=== Testing ProfileController Endpoint ===\n\n";
echo "Session Data:\n";
echo "  user_id: " . $_SESSION['user_id'] . "\n";
echo "  user_role: " . $_SESSION['user_role'] . "\n\n";

// Simulate the controller request
echo "Simulating ProfileController request with action=get...\n\n";

// Buffer output to capture the response
ob_start();

// Include and run the controller
$_GET['action'] = 'get';
require_once 'Controller/ProfileController.php';

// Get the output
$output = ob_get_clean();

echo "Raw Response:\n";
echo $output . "\n\n";

// Parse and pretty-print the JSON
$response = json_decode($output, true);
if ($response) {
    echo "Parsed JSON:\n";
    echo json_encode($response, JSON_PRETTY_PRINT) . "\n\n";
    
    if (isset($response['data']['teacherData'])) {
        echo "Teacher Data:\n";
        echo "  firstname: " . ($response['data']['teacherData']['firstname'] ?? 'NOT SET') . "\n";
        echo "  Lastname: " . ($response['data']['teacherData']['Lastname'] ?? 'NOT SET') . "\n";
        echo "  email: " . ($response['data']['teacherData']['email'] ?? 'NOT SET') . "\n";
        echo "  specialization: " . ($response['data']['teacherData']['specialization'] ?? 'NOT SET') . "\n";
        echo "  exprience: " . ($response['data']['teacherData']['exprience'] ?? 'NOT SET') . "\n\n";
    }
    
    if (isset($response['data']['studentsList'])) {
        echo "Students Count: " . count($response['data']['studentsList']) . "\n";
        if (count($response['data']['studentsList']) > 0) {
            echo "Students:\n";
            foreach ($response['data']['studentsList'] as $student) {
                echo "  - {$student['childName']}\n";
            }
        }
    }
} else {
    echo "ERROR: Could not parse JSON response\n";
}

echo "\n=== Test Complete ===\n";
?>
