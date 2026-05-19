<?php
/**
 * Login Test - Verify login works with database
 * Run via: http://localhost/Yarab/tests/test_login.php
 */

// Suppress header warnings for CLI testing
ob_start();

require_once __DIR__ . '/../Models/AuthService.php';

$output = ob_get_clean();

echo "=== Login Test ===\n\n";

// Single authService instance for all tests
$authService = new AuthService();

// Test credentials
$testAccounts = [
    ['email' => 'admin@wellucation.local', 'password' => 'AdminPass123!', 'expected_role' => 'admin'],
    ['email' => 'teacher@wellucation.local', 'password' => 'TeacherPass123!', 'expected_role' => 'teacher'],
];

$passed = 0;
$failed = 0;

echo "--- Valid Credentials ---\n";
foreach ($testAccounts as $account) {
    try {
        $user = $authService->login($account['email'], $account['password']);
        
        if ($user) {
            $actualRole = strtolower($user->getRole());
            $expectedRole = strtolower($account['expected_role']);
            
            if ($actualRole === $expectedRole) {
                echo "✓ LOGIN SUCCESS: {$account['email']}\n";
                echo "  └─ Role: {$user->getRole()}, Name: {$user->getFirstName()} {$user->getLastName()}\n";
                $passed++;
            } else {
                echo "✗ ROLE MISMATCH: {$account['email']}\n";
                echo "  Expected: {$expectedRole}, Got: {$actualRole}\n";
                $failed++;
            }
        } else {
            echo "✗ LOGIN FAILED: {$account['email']}\n";
            $failed++;
        }
    } catch (Exception $e) {
        echo "✗ ERROR: {$account['email']} - " . $e->getMessage() . "\n";
        $failed++;
    }
}

// Test invalid password
echo "\n--- Security Tests ---\n";
try {
    $user = $authService->login('admin@wellucation.local', 'WrongPassword123!');
    
    if ($user === null) {
        echo "✓ CORRECTLY REJECTED invalid password\n";
        $passed++;
    } else {
        echo "✗ FAILED to reject invalid password\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "✗ ERROR on invalid password test - " . $e->getMessage() . "\n";
    $failed++;
}

// Test non-existent email
try {
    $user = $authService->login('nonexistent@wellucation.local', 'SomePassword123!');
    
    if ($user === null) {
        echo "✓ CORRECTLY REJECTED non-existent email\n";
        $passed++;
    } else {
        echo "✗ FAILED to reject non-existent email\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "✗ ERROR on non-existent email test - " . $e->getMessage() . "\n";
    $failed++;
}

echo "\n=== Summary ===\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";
echo "Total: " . ($passed + $failed) . "\n";
echo "Success Rate: " . round(($passed / ($passed + $failed)) * 100) . "%\n";

