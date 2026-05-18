<?php
// Temporary test script for database model flow.
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Parent.php';
require_once __DIR__ . '/Child.php';
require_once __DIR__ . '/Application.php';
require_once __DIR__ . '/Course.php';

function setPrivateProperty($object, $property, $value)
{
    $ref = new ReflectionProperty($object, $property);
    $ref->setAccessible(true);
    $ref->setValue($object, $value);
}

$db = Database::getInstance();
$conn = $db->getConnection();

$email = 'parent_test_' . time() . '@example.com';
$password = 'Secret123!';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Create a dummy user and parent.
$db->query("INSERT INTO user (email,passwordHash,firstname,Lastname,Role,preferredLanguage) VALUES (?,?,?,?,?,?)", [$email, $passwordHash, 'Test', 'Parent', 'Parent', 'EN']);
$userId = $conn->lastInsertId();
$db->query("INSERT INTO parent (userID,phone,address,notifPreferences) VALUES (?,?,?,?)", [$userId, '0123456789', '123 Test St', json_encode(['newsletter' => true])]);
$parentId = $conn->lastInsertId();

$parent = new Parents($userId, $email, $passwordHash, 'EN', null, null, 'Parent', 'Test', 'Parent', true);
setPrivateProperty($parent, 'ParentId', $parentId);
setPrivateProperty($parent, 'PhoneNumber', '0123456789');

$childData = [
    'Name' => 'Dummy Child',
    'DateOfBirth' => '2018-01-01',
    'Gender' => 'F',
    'allergies' => 'None',
    'MedicalNotes' => 'No issues',
    'EmergencyContact' => 'Mom 0123456789',
    'PhotoPath' => null,
];

$added = $parent->AddChild($childData);
$childId = $conn->lastInsertId();
$childRow = $db->fetchOne("SELECT * FROM child WHERE childID = ?", [$childId]);

// Create a dummy course.
$db->query("INSERT INTO course (name,description,ageMin,ageMax,maxCapacity,price) VALUES (?,?,?,?,?,?)", ['Dummy Course', 'Test course', 3, 6, 10, 100.00]);
$courseId = $conn->lastInsertId();

$submitted = $parent->SubmitApplication(['ChildId' => $childId, 'CourseId' => $courseId, 'Documents' => ['form' => 'doc']]);
$appId = $conn->lastInsertId();
$appRow = $db->fetchOne("SELECT * FROM application WHERE applicationID = ?", [$appId]);

echo "RESULTS:\n";
echo "userId=$userId parentId=$parentId childId=$childId courseId=$courseId appId=$appId\n";
echo "AddChild returned: " . json_encode($added) . "\n";
echo "Child row: " . json_encode($childRow) . "\n";
echo "SubmitApplication returned: " . json_encode($submitted) . "\n";
echo "Application row: " . json_encode($appRow) . "\n";
