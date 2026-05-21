<?php
// Quick test script to verify registration flow
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/Models/Database.php';
require_once __DIR__ . '/Models/RegistrationContext.php';
require_once __DIR__ . '/Models/ParentRegistrationStrategy.php';
require_once __DIR__ . '/Models/TeacherRegistrationStrategy.php';

try {
    echo "Testing Parent Registration...\n";
    
    // Test parent registration
    $parentContext = RegistrationContext::createParentStrategy(
        'testparent@example.com',
        'TestPassword123',
        'Test',
        'Parent',
        '1234567890',
        '123 Test Street'
    );
    
    // Validate
    $errors = $parentContext->validate();
    if (!empty($errors)) {
        echo "Validation errors:\n";
        foreach ($errors as $error) {
            echo "  - $error\n";
        }
    } else {
        echo "✓ Validation passed\n";
        
        // Register
        try {
            $result = $parentContext->register(
                'testparent@example.com',
                'TestPassword123',
                'Test',
                'Parent'
            );
            echo "✓ Registration successful!\n";
            
            // Check if parent record was created
            $db = Database::getInstance();
            $user = $db->fetchOne("SELECT userID FROM user WHERE email = ?", ['testparent@example.com']);
            if ($user) {
                echo "  - User created with ID: " . $user['userID'] . "\n";
                
                $parent = $db->fetchOne("SELECT * FROM parent WHERE userID = ?", [$user['userID']]);
                if ($parent) {
                    echo "  ✓ Parent record created with ID: " . $parent['parentID'] . "\n";
                } else {
                    echo "  ✗ Parent record NOT found!\n";
                }
            }
        } catch (Exception $e) {
            echo "✗ Registration failed: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
