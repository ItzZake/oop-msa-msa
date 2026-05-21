<?php
require_once __DIR__ . '/Models/Database.php';

echo "=== Checking teacher table structure ===\n\n";

try {
    $db = Database::getInstance();
    
    // Get teacher table columns
    $sql = "DESCRIBE teacher";
    $result = $db->query($sql)->fetchAll();
    
    echo "Teacher table columns:\n";
    foreach ($result as $col) {
        echo "  - {$col['Field']}: {$col['Type']}\n";
    }
    
    echo "\n=== Checking sample teacher data ===\n\n";
    
    $sql = "SELECT * FROM teacher LIMIT 3";
    $rows = $db->query($sql)->fetchAll();
    
    foreach ($rows as $row) {
        echo "Teacher Record:\n";
        foreach ($row as $key => $value) {
            echo "  {$key}: {$value}\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
