<?php
require_once 'Models/Database.php';
$db = Database::getInstance();
$stmt = $db->getConnection()->query('SELECT userID, email, Role, firstname, Lastname FROM `user`');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode($row) . PHP_EOL;
}
