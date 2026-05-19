<?php
require_once 'Models/Database.php';
$db = Database::getInstance();
$stmt = $db->getConnection()->query('SHOW TABLES');
while ($r = $stmt->fetch(PDO::FETCH_NUM)) {
    echo $r[0] . PHP_EOL;
}
