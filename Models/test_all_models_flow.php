<?php
// Broader database model test harness.
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Parent.php';
require_once __DIR__ . '/Child.php';
require_once __DIR__ . '/Application.php';
require_once __DIR__ . '/Course.php';
require_once __DIR__ . '/Enrollment.php';
require_once __DIR__ . '/Attendance.php';
require_once __DIR__ . '/Assignment.php';
require_once __DIR__ . '/Message.php';
require_once __DIR__ . '/Notification.php';
require_once __DIR__ . '/NotificationManager.php';
require_once __DIR__ . '/Subscription.php';
require_once __DIR__ . '/ProgressReport.php';
require_once __DIR__ . '/Flag.php';
require_once __DIR__ . '/Teacher.php';
require_once __DIR__ . '/AuthService.php';
require_once __DIR__ . '/userRepository.php';

$db = Database::getInstance();
$conn = $db->getConnection();
$results = [];

function runTest($name, callable $fn)
{
    try {
        $result = $fn();
        return ['ok' => true, 'result' => $result];
    } catch (Throwable $e) {
        return ['ok' => false, 'error' => $e->getMessage()];
    }
}

$conn->beginTransaction();

try {
    $email = 'test_parent_' . time() . '@example.com';
    $password = 'TestPass123!';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $db->query("INSERT INTO user (email,passwordHash,firstname,Lastname,Role,preferredLanguage) VALUES (?,?,?,?,?,?)", [$email, $passwordHash, 'Test', 'Parent', 'Parent', 'EN']);
    $userId = $conn->lastInsertId();

    $db->query("INSERT INTO parent (userID,phone,address,notifPreferences) VALUES (?,?,?,?)", [$userId, '0123456789', '123 Street', json_encode(['alert' => true])]);
    $parentId = $conn->lastInsertId();

    $db->query("INSERT INTO user (email,passwordHash,firstname,Lastname,Role,preferredLanguage) VALUES (?,?,?,?,?,?)", ['teacher_' . time() . '@example.com', $passwordHash, 'Teacher', 'One', 'Teacher', 'EN']);
    $teacherUserId = $conn->lastInsertId();
    $db->query("INSERT INTO teacher (userID,phone) VALUES (?,?)", [$teacherUserId, '0987654321']);
    $teacherId = $conn->lastInsertId();

    $db->query("INSERT INTO user (email,passwordHash,firstname,Lastname,Role,preferredLanguage) VALUES (?,?,?,?,?,?)", ['admin_' . time() . '@example.com', $passwordHash, 'Admin', 'One', 'Admin', 'EN']);
    $adminUserId = $conn->lastInsertId();
    $db->query("INSERT INTO admin (userID) VALUES (?)", [$adminUserId]);
    $adminId = $conn->lastInsertId();

    $teacher = new Teacher($teacherUserId, 'teacher_' . time() . '@example.com', $passwordHash, 'EN', null, null, 'Teacher', 'Teacher', 'One', true);
    $refTeacherId = new ReflectionProperty($teacher, 'TeacherId');
    $refTeacherId->setAccessible(true);
    $refTeacherId->setValue($teacher, $teacherId);

    $parent = new Parents($userId, $email, $passwordHash, 'EN', null, null, 'Parent', 'Test', 'Parent', true);
    $ref = new ReflectionProperty($parent, 'ParentId');
    $ref->setAccessible(true);
    $ref->setValue($parent, $parentId);

    $refPhone = new ReflectionProperty($parent, 'PhoneNumber');
    $refPhone->setAccessible(true);
    $refPhone->setValue($parent, '0123456789');

    $results['Parent::AddChild'] = runTest('Parent::AddChild', function() use ($parent) {
        return $parent->AddChild([
            'Name' => 'Dummy Child',
            'DateOfBirth' => '2018-01-01',
            'Gender' => 'F',
            'allergies' => 'None',
            'MedicalNotes' => 'Healthy',
            'EmergencyContact' => 'Mom 0123456789',
            'PhotoPath' => null,
        ]);
    });

    $childId = $conn->lastInsertId();

    $db->query("INSERT INTO course (name,description,ageMin,ageMax,maxCapacity,price) VALUES (?,?,?,?,?,?)", ['Test Course', 'For testing', 3, 6, 10, 150.00]);
    $courseId = $conn->lastInsertId();

    $db->query("INSERT INTO subscription (parentID, childID, planName, basePrice, startDate, dueDate, status, billingCycle, isOverdue, daysOverdue) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [$parentId, $childId, 'Test Plan', 100.00, date('Y-m-d'), date('Y-m-d', strtotime('+30 days')), 'Active', 'Monthly', 0, 0]);
    $subscriptionId = $conn->lastInsertId();

    $results['Parent::SubmitApplication'] = runTest('Parent::SubmitApplication', function() use ($parent, $childId, $courseId) {
        return $parent->SubmitApplication(['ChildId' => $childId, 'CourseId' => $courseId, 'Documents' => ['form' => 'ok']]);
    });

    $results['Course::Create'] = runTest('Course::Create', function() {
        $course = new Course();
        return $course->Create(['Name' => 'Course X', 'Description' => 'Desc', 'AgeMin' => 4, 'AgeMax' => 8, 'MaxCapacity' => 15, 'Price' => 200, 'Schedule' => ['Mon' => '09:00']] );
    });

    $results['Course::Edit'] = runTest('Course::Edit', function() use ($courseId) {
        $course = new Course();
        return $course->Edit($courseId, ['Name' => 'Course X v2', 'Description' => 'Desc 2', 'AgeMin' => 4, 'AgeMax' => 8, 'MaxCapacity' => 15, 'Price' => 200, 'Schedule' => ['Tue' => '10:00'], 'IsActive' => true]);
    });

    $assignData = [
        'Title' => 'Test Assign',
        'Instructions' => 'Do this',
        'DueDate' => date('Y-m-d', strtotime('+7 days')),
        'WordwallEmbedCode' => 'abc12345',
        'AttachmentPath' => null,
        'Status' => 'Draft',
        'TargetTags' => null
    ];
    $assignment = new Assignment();
    $refAssign = new ReflectionProperty($assignment, 'teacherID');
    $refAssign->setAccessible(true);
    $refAssign->setValue($assignment, $teacherId);
    $refCourse = new ReflectionProperty($assignment, 'courseID');
    $refCourse->setAccessible(true);
    $refCourse->setValue($assignment, $courseId);
    $refTitle = new ReflectionProperty($assignment, 'title');
    $refTitle->setAccessible(true);
    $refTitle->setValue($assignment, 'Test Assign');
    $refInstructions = new ReflectionProperty($assignment, 'instructions');
    $refInstructions->setAccessible(true);
    $refInstructions->setValue($assignment, 'Instructions');
    $refDue = new ReflectionProperty($assignment, 'duedate');
    $refDue->setAccessible(true);
    $refDue->setValue($assignment, date('Y-m-d', strtotime('+7 days')));
    $refWord = new ReflectionProperty($assignment, 'wordwallembedcode');
    $refWord->setAccessible(true);
    $refWord->setValue($assignment, 'abc12345');
    $refStatus = new ReflectionProperty($assignment, 'status');
    $refStatus->setAccessible(true);
    $refStatus->setValue($assignment, 'Draft');
    $refTarget = new ReflectionProperty($assignment, 'targettags');
    $refTarget->setAccessible(true);
    $refTarget->setValue($assignment, null);

    $results['Assignment::SaveAssignment'] = runTest('Assignment::SaveAssignment', function() use ($assignment) {
        return $assignment->SaveAssignment();
    });
    $assignmentId = $conn->lastInsertId();

    $results['Assignment::FetchWordwallEmbed'] = runTest('Assignment::FetchWordwallEmbed', function() use ($assignment) {
        return $assignment->FetchWordwallEmbed('https://wordwall.net/resource/abcdef12');
    });

    $results['Assignment::ValidateEmbedCode'] = runTest('Assignment::ValidateEmbedCode', function() use ($assignment) {
        return $assignment->ValidateEmbedCode('abcdef12');
    });

    $messageRes = runTest('Message::Send', function() use ($userId) {
        $msg = new Message();
        return $msg->Send(['SenderID' => $userId, 'RecipientID' => $userId, 'Content' => 'Hello']);
    });
    $results['Message::Send'] = $messageRes;
    $messageId = $conn->lastInsertId();

    $results['Message::GetThread'] = runTest('Message::GetThread', function() use ($userId) {
        $msg = new Message();
        return $msg->GetThread($userId, $userId);
    });

    $results['Message::GetUnread'] = runTest('Message::GetUnread', function() use ($userId) {
        $msg = new Message();
        return $msg->GetUnread($userId);
    });

    $results['Message::MarkRead'] = runTest('Message::MarkRead', function() use ($messageId) {
        $msg = new Message();
        return $msg->MarkRead($messageId);
    });

    $results['Notification::Send'] = runTest('Notification::Send', function() use ($userId) {
        $note = new Notification();
        return $note->Send($userId, 'test', 'Hello', 'InApp');
    });
    $notificationId = $conn->lastInsertId();

    $results['Notification::GetUnread'] = runTest('Notification::GetUnread', function() use ($userId) {
        $note = new Notification();
        return $note->GetUnread($userId);
    });

    $results['Notification::GetUnreadCount'] = runTest('Notification::GetUnreadCount', function() use ($userId) {
        $note = new Notification();
        return $note->GetUnreadCount($userId);
    });

    $results['NotificationManager::SetUserPreferences'] = runTest('NotificationManager::SetUserPreferences', function() use ($userId) {
        $mgr = NotificationManager::getInstance();
        return $mgr->SetUserPreferences($userId, ['email' => true]);
    });

    $results['NotificationManager::GetUserPreferences'] = runTest('NotificationManager::GetUserPreferences', function() use ($userId) {
        $mgr = NotificationManager::getInstance();
        return $mgr->GetUserPreferences($userId);
    });

    $results['NotificationManager::GetUnreadCount'] = runTest('NotificationManager::GetUnreadCount', function() use ($userId) {
        $mgr = NotificationManager::getInstance();
        return $mgr->GetUnreadCount($userId);
    });

    $results['Subscription::GenerateInvoice'] = runTest('Subscription::GenerateInvoice', function() use ($parentId, $childId, $subscriptionId) {
        $sub = new Subscription();
        $refSub = new ReflectionProperty($sub, 'subscriptionID'); $refSub->setAccessible(true); $refSub->setValue($sub, $subscriptionId);
        $refParent = new ReflectionProperty($sub, 'parentID'); $refParent->setAccessible(true); $refParent->setValue($sub, $parentId);
        $refChild = new ReflectionProperty($sub, 'childID'); $refChild->setAccessible(true); $refChild->setValue($sub, $childId);
        $refBase = new ReflectionProperty($sub, 'baseprice'); $refBase->setAccessible(true); $refBase->setValue($sub, 100.0);
        $refStart = new ReflectionProperty($sub, 'startdate'); $refStart->setAccessible(true); $refStart->setValue($sub, date('Y-m-d'));
        $refDue = new ReflectionProperty($sub, 'duedate'); $refDue->setAccessible(true); $refDue->setValue($sub, date('Y-m-d', strtotime('+30 days')));
        $refStatus = new ReflectionProperty($sub, 'status'); $refStatus->setAccessible(true); $refStatus->setValue($sub, 'Active');
        return $sub->GenerateInvoice();
    });

    $results['ProgressReport::SubmitReport'] = runTest('ProgressReport::SubmitReport', function() use ($childId, $teacherId) {
        $pr = new ProgressReport();
        return $pr->SubmitReport(['ChildId' => $childId, 'TeacherId' => $teacherId, 'Period' => 'Term1', 'Observation' => 'OK', 'SkillRating' => json_encode(['math'=>5]), 'Status' => 'Draft']);
    });

    $results['ProgressReport::GetProgressReportsByChildId'] = runTest('ProgressReport::GetProgressReportsByChildId', function() use ($childId) {
        $pr = new ProgressReport();
        return $pr->GetProgressReportsByChildId($childId);
    });

    $results['Flag::Raise'] = runTest('Flag::Raise', function() use ($childId) {
        $flag = new Flag();
        $ref = new ReflectionProperty($flag, 'childID');
        $ref->setAccessible(true);
        $ref->setValue($flag, $childId);
        return $flag->Raise('LowAttendance', 'Test');
    });
    $flagId = $conn->lastInsertId();

    $results['Flag::GetActive'] = runTest('Flag::GetActive', function() {
        $flag = new Flag();
        return $flag->GetActive();
    });

    $results['Flag::Clear'] = runTest('Flag::Clear', function() use ($flagId, $adminId) {
        $flag = new Flag();
        $ref = new ReflectionProperty($flag, 'flagID');
        $ref->setAccessible(true);
        $ref->setValue($flag, $flagId);
        return $flag->Clear($adminId, 'cleared');
    });

    $results['Teacher::CreateAssignment'] = runTest('Teacher::CreateAssignment', function() use ($teacher, $courseId) {
        return $teacher->CreateAssignment($courseId, ['Title' => 'Teach Assign', 'Description' => 'Desc', 'DueDate' => date('Y-m-d', strtotime('+5 days'))]);
    });

    $conn->rollBack();
} catch (Throwable $e) {
    $conn->rollBack();
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    exit(1);
}

foreach ($results as $name => $data) {
    echo "{$name}: ";
    if ($data['ok']) {
        echo "OK";
        if (is_array($data['result']) || is_object($data['result'])) {
            echo " - " . json_encode($data['result']);
        } else {
            echo " - " . var_export($data['result'], true);
        }
    } else {
        echo "ERROR - " . $data['error'];
    }
    echo "\n";
}
