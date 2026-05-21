<?php
require_once __DIR__ . '/Models/Database.php';

echo "=== Checking user table structure ===\n\n";

try {
    $db = Database::getInstance();
    
    // Get user table columns
    $sql = "DESCRIBE user";
    $result = $db->query($sql)->fetchAll();
    
    echo "User table columns:\n";
    foreach ($result as $col) {
        echo "  - {$col['Field']}: {$col['Type']}\n";
    }
    
    echo "\n=== Checking user ID 39 ===\n\n";
    
    $sql = "SELECT * FROM user WHERE userID = 39";
    $row = $db->query($sql)->fetchOne();
    
    if ($row) {
        echo "User Record (ID 39):\n";
        foreach ($row as $key => $value) {
            echo "  {$key}: {$value}\n";
        }
    } else {
        echo "No user with ID 39\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
