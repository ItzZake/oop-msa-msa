<?php
require_once 'Models/Database.php';
$db = Database::getInstance();
$tables = ['child','teacher','user','attendance','flag','notification','course','enrollment'];
foreach ($tables as $t) {
    try {
        $stmt = $db->getConnection()->query('SELECT COUNT(*) AS cnt FROM `' . $t . '`');
        $cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
        echo "$t = $cnt\n";
    } catch (Exception $e) {
        echo "$t = ERROR: " . $e->getMessage() . "\n";
    }
}
