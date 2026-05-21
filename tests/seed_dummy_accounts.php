<?php
/**
 * Database Seeder - Creates dummy test accounts
 * Run via: http://localhost/Yarab/tests/seed_dummy_accounts.php
 */

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/PasswordHasher.php';

$db = Database::getInstance();
$hasher = new PasswordHasher();

// Dummy accounts to create
$dummyAccounts = [
    [
        'email' => 'admin@wellucation.local',
        'password' => 'AdminPass123!',
        'firstName' => 'Admin',
        'lastName' => 'User',
        'role' => 'admin'
    ],
    [
        'email' => 'teacher@wellucation.local',
        'password' => 'TeacherPass123!',
        'firstName' => 'Jane',
        'lastName' => 'Teacher',
        'role' => 'teacher'
    ],
    [
        'email' => 'parent@wellucation.local',
        'password' => 'ParentPass123!',
        'firstName' => 'John',
        'lastName' => 'Parent',
        'role' => 'parent'
    ],
    [
        'email' => 'test.parent@wellucation.local',
        'password' => 'TestPass123!',
        'firstName' => 'Test',
        'lastName' => 'Parent',
        'role' => 'parent'
    ],
];

echo "=== Dummy Account Seeder ===\n\n";

$created = 0;
$skipped = 0;
$errors = [];

foreach ($dummyAccounts as $account) {
    try {
        // Check if account already exists
        $checkSql = "SELECT userID FROM User WHERE email = ? LIMIT 1";
        $exists = $db->fetchOne($checkSql, [$account['email']]);
        
        if ($exists) {
            echo "⊘ SKIP: {$account['email']} (already exists)\n";
            $skipped++;
            continue;
        }

        // Hash password
        $hashedPassword = $hasher->Hash($account['password']);

        // Insert account
        $insertSql = "INSERT INTO User (email, passwordHash, firstname, Lastname, Role, isActive, createdAt)
                      VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $result = $db->query($insertSql, [
            $account['email'],
            $hashedPassword,
            $account['firstName'],
            $account['lastName'],
            ucfirst($account['role']),  // Capitalize: admin -> Admin
            1
        ]);

        if ($result) {
            echo "✓ CREATED: {$account['email']} (role: {$account['role']})\n";
            echo "  └─ Password: {$account['password']}\n";
            $created++;
        } else {
            echo "✗ FAILED: {$account['email']}\n";
            $errors[] = $account['email'];
        }
    } catch (Exception $e) {
        echo "✗ ERROR: {$account['email']} - " . $e->getMessage() . "\n";
        $errors[] = $account['email'];
    }
}

echo "\n=== Summary ===\n";
echo "Created: $created\n";
echo "Skipped: $skipped\n";
echo "Errors: " . count($errors) . "\n";

if (count($errors) > 0) {
    echo "\nFailed accounts:\n";
    foreach ($errors as $email) {
        echo "  - $email\n";
    }
}

echo "\n=== Test Accounts ===\n";
echo "Use these credentials to test login:\n\n";
foreach ($dummyAccounts as $account) {
    echo "Email: {$account['email']}\n";
    echo "Password: {$account['password']}\n";
    echo "Role: {$account['role']}\n";
    echo "\n";
}
