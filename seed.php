<?php
// ══════════════════════════════════════════════════════════════════
//  seed.php
//  Populates database with dummy data for testing
//  Run: c:\xampp\php\php.exe seed.php
// ══════════════════════════════════════════════════════════════════

require_once __DIR__ . '/Models/Database.php';

$db = Database::getInstance();

echo "🌱 Starting database seed...\n";

try {
    // ══════════════════════════════════════════════════════════════════
    // ADMINS
    // ══════════════════════════════════════════════════════════════════
    echo "\n📋 Seeding Admins...\n";
    
    $admins = [
        ['email' => 'admin@wellucation.local', 'firstname' => 'System', 'Lastname' => 'Admin'],
        ['email' => 'principal@wellucation.local', 'firstname' => 'Principal', 'Lastname' => 'Sarah'],
    ];

    $adminIds = [];
    foreach ($admins as $admin) {
        $sql = "INSERT IGNORE INTO user (email, firstname, Lastname, passwordHash, Role, isActive) 
                VALUES (?, ?, ?, ?, 'Admin', 1)";
        $passwordHash = password_hash('admin123', PASSWORD_BCRYPT);
        $db->query($sql, [$admin['email'], $admin['firstname'], $admin['Lastname'], $passwordHash]);
        
        $adminUser = $db->fetchOne("SELECT userID FROM user WHERE email = ?", [$admin['email']]);
        if ($adminUser) {
            $adminIds[] = $adminUser['userID'];
            echo "✓ Admin: {$admin['firstname']} ({$admin['email']})\n";
        }
    }

    // ══════════════════════════════════════════════════════════════════
    // TEACHERS
    // ══════════════════════════════════════════════════════════════════
    echo "\n👨‍🏫 Seeding Teachers...\n";
    
    $teachers = [
        ['email' => 'john.smith@wellucation.local', 'firstname' => 'John', 'Lastname' => 'Smith', 'experience' => '8'],
        ['email' => 'sarah.jones@wellucation.local', 'firstname' => 'Sarah', 'Lastname' => 'Jones', 'experience' => '12'],
        ['email' => 'michael.brown@wellucation.local', 'firstname' => 'Michael', 'Lastname' => 'Brown', 'experience' => '5'],
        ['email' => 'emma.davis@wellucation.local', 'firstname' => 'Emma', 'Lastname' => 'Davis', 'experience' => '10'],
    ];

    $teacherIds = [];
    foreach ($teachers as $teacher) {
        $sql = "INSERT IGNORE INTO user (email, firstname, Lastname, passwordHash, Role, isActive) 
                VALUES (?, ?, ?, ?, 'Teacher', 1)";
        $passwordHash = password_hash('teacher123', PASSWORD_BCRYPT);
        $db->query($sql, [$teacher['email'], $teacher['firstname'], $teacher['Lastname'], $passwordHash]);
        
        $teacherUser = $db->fetchOne("SELECT userID FROM user WHERE email = ?", [$teacher['email']]);
        if ($teacherUser) {
            // Insert into teacher table
            $teacherSql = "INSERT IGNORE INTO teacher (userID, qualifications, exprience) VALUES (?, ?, ?)";
            $db->query($teacherSql, [$teacherUser['userID'], 'Bachelor\'s Degree', $teacher['experience']]);
            $teacherIds[] = $teacherUser['userID'];
            echo "✓ Teacher: {$teacher['firstname']} {$teacher['Lastname']} ({$teacher['email']})\n";
        }
    }

    // ══════════════════════════════════════════════════════════════════
    // COURSES
    // ══════════════════════════════════════════════════════════════════
    echo "\n📚 Seeding Courses...\n";
    
    $courses = [
        ['name' => 'Mathematics', 'description' => 'Basic math and numeracy', 'ageMin' => 4, 'ageMax' => 6, 'maxCapacity' => 25, 'price' => 150.00],
        ['name' => 'English Literature', 'description' => 'Reading and writing skills', 'ageMin' => 5, 'ageMax' => 7, 'maxCapacity' => 25, 'price' => 150.00],
        ['name' => 'Science', 'description' => 'General science concepts', 'ageMin' => 4, 'ageMax' => 6, 'maxCapacity' => 20, 'price' => 120.00],
        ['name' => 'History', 'description' => 'World history and culture', 'ageMin' => 5, 'ageMax' => 7, 'maxCapacity' => 25, 'price' => 100.00],
        ['name' => 'Physical Education', 'description' => 'Sports and wellness', 'ageMin' => 4, 'ageMax' => 7, 'maxCapacity' => 30, 'price' => 80.00],
    ];

    $courseIds = [];
    foreach ($courses as $index => $course) {
        $assignedTeacherId = $teacherIds[$index % count($teacherIds)];
        $sql = "INSERT IGNORE INTO course (name, description, ageMin, ageMax, maxCapacity, assignedTeacherID, price, isActive) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
        $db->query($sql, [
            $course['name'], 
            $course['description'], 
            $course['ageMin'],
            $course['ageMax'],
            $course['maxCapacity'], 
            $assignedTeacherId,
            $course['price']
        ]);
        
        $courseResult = $db->fetchOne("SELECT courseID FROM course WHERE name = ?", [$course['name']]);
        if ($courseResult) {
            $courseIds[] = $courseResult['courseID'];
            echo "✓ Course: {$course['name']}\n";
        }
    }

    // ══════════════════════════════════════════════════════════════════
    // PARENTS & CHILDREN
    // ══════════════════════════════════════════════════════════════════
    echo "\n👨‍👩‍👧 Seeding Parents & Children...\n";
    
    $parentData = [
        ['email' => 'parent1@wellucation.local', 'firstname' => 'Robert', 'Lastname' => 'Wilson', 'children' => [
            ['name' => 'Lucy Wilson', 'dob' => '2018-05-15', 'gender' => 'F'],
            ['name' => 'Noah Wilson', 'dob' => '2019-08-22', 'gender' => 'M'],
        ]],
        ['email' => 'parent2@wellucation.local', 'firstname' => 'Jennifer', 'Lastname' => 'Martinez', 'children' => [
            ['name' => 'Sophia Martinez', 'dob' => '2017-03-10', 'gender' => 'F'],
        ]],
        ['email' => 'parent3@wellucation.local', 'firstname' => 'David', 'Lastname' => 'Anderson', 'children' => [
            ['name' => 'Ethan Anderson', 'dob' => '2018-11-05', 'gender' => 'M'],
            ['name' => 'Olivia Anderson', 'dob' => '2020-02-14', 'gender' => 'F'],
        ]],
        ['email' => 'parent4@wellucation.local', 'firstname' => 'Michelle', 'Lastname' => 'Taylor', 'children' => [
            ['name' => 'Isabella Taylor', 'dob' => '2019-07-30', 'gender' => 'F'],
        ]],
    ];

    $childIds = [];
    foreach ($parentData as $parent) {
        $sql = "INSERT IGNORE INTO user (email, firstname, Lastname, passwordHash, Role, isActive) 
                VALUES (?, ?, ?, ?, 'Parent', 1)";
        $passwordHash = password_hash('parent123', PASSWORD_BCRYPT);
        $db->query($sql, [$parent['email'], $parent['firstname'], $parent['Lastname'], $passwordHash]);
        
        $parentUser = $db->fetchOne("SELECT userID FROM user WHERE email = ?", [$parent['email']]);
        if ($parentUser) {
            // Insert into parent table
            $parentTableSql = "INSERT IGNORE INTO parent (userID) VALUES (?)";
            $db->query($parentTableSql, [$parentUser['userID']]);
            
            // Get the parentID after insertion
            $parentRecord = $db->fetchOne("SELECT parentID FROM parent WHERE userID = ?", [$parentUser['userID']]);
            if (!$parentRecord) {
                echo "  ⚠️ Warning: Could not get parentID for userID {$parentUser['userID']}\n";
                continue;
            }
            
            $parentID = $parentRecord['parentID'];
            echo "✓ Parent: {$parent['firstname']} {$parent['Lastname']} ({$parent['email']}) - parentID: $parentID\n";
            
            // Add children
            foreach ($parent['children'] as $child) {
                $childSql = "INSERT INTO child (parentID, name, dateOfBirth, gender, enrollmentStatus) 
                             VALUES (?, ?, ?, ?, 'Pending')";
                $db->query($childSql, [$parentID, $child['name'], $child['dob'], $child['gender']]);
                
                $childRecord = $db->fetchOne("SELECT childID FROM child WHERE parentID = ? AND name = ?", 
                                             [$parentID, $child['name']]);
                if ($childRecord) {
                    $childIds[] = $childRecord['childID'];
                    echo "  └─ Child: {$child['name']}\n";
                    
                    // Enroll child in random courses
                    $randomCourses = array_rand($courseIds, min(rand(2, 4), count($courseIds)));
                    if (!is_array($randomCourses)) $randomCourses = [$randomCourses];
                    
                    foreach ($randomCourses as $courseIdx) {
                        $enrollSql = "INSERT IGNORE INTO enrollment (childID, courseID, status) VALUES (?, ?, 'Active')";
                        $db->query($enrollSql, [$childRecord['childID'], $courseIds[$courseIdx]]);
                    }
                }
            }
        }
    }

    // ══════════════════════════════════════════════════════════════════
    // ASSIGNMENTS
    // ══════════════════════════════════════════════════════════════════
    echo "\n📝 Seeding Assignments...\n";
    
    $assignments = [
        ['title' => 'Solving Quadratic Equations', 'course' => 'Mathematics', 'description' => 'Complete 10 problems on quadratic equations', 'daysFromNow' => 3],
        ['title' => 'Shakespeare Essay', 'course' => 'English Literature', 'description' => 'Write a 500-word essay on Hamlet', 'daysFromNow' => 7],
        ['title' => 'Science Experiment Report', 'course' => 'Science', 'description' => 'Document and analyze your experiment results', 'daysFromNow' => 5],
        ['title' => 'Historical Timeline', 'course' => 'History', 'description' => 'Create a timeline of major historical events', 'daysFromNow' => 4],
        ['title' => 'Physical Fitness Test', 'course' => 'Physical Education', 'description' => 'Complete fitness assessment', 'daysFromNow' => 2],
    ];

    foreach ($assignments as $assignment) {
        $courseResult = $db->fetchOne("SELECT courseID, assignedTeacherID FROM course WHERE name = ?", [$assignment['course']]);
        
        if ($courseResult) {
            $dueDate = date('Y-m-d H:i:s', strtotime("+{$assignment['daysFromNow']} days"));
            $sql = "INSERT INTO assignment (courseID, teacherID, title, instructions, dueDate, status) 
                    VALUES (?, ?, ?, ?, ?, 'Published')";
            $db->query($sql, [
                $courseResult['courseID'],
                $courseResult['assignedTeacherID'],
                $assignment['title'],
                $assignment['description'],
                $dueDate
            ]);
            echo "✓ Assignment: {$assignment['title']} (Due: $dueDate)\n";
        }
    }

    // ══════════════════════════════════════════════════════════════════
    // SUBSCRIPTIONS & PAYMENTS
    // ══════════════════════════════════════════════════════════════════
    echo "\n💳 Seeding Subscriptions & Payments...\n";
    
    $parentUsers = $db->fetchAll("SELECT userID FROM user WHERE Role = 'Parent' LIMIT 4");
    foreach ($parentUsers as $parentUser) {
        $parentRecord = $db->fetchOne("SELECT parentID FROM parent WHERE userID = ?", [$parentUser['userID']]);
        if ($parentRecord) {
            // Create dummy subscription for each child
            $children = $db->fetchAll("SELECT childID FROM child WHERE parentID = ?", [$parentRecord['parentID']]);
            foreach ($children as $child) {
                $subscriptionSql = "INSERT INTO subscription (parentID, childID, planName, basePrice, billingCycle, status, startDate, dueDate) 
                                   VALUES (?, ?, 'Premium', 99.99, 'Monthly', 'Active', ?, ?)";
                $db->query($subscriptionSql, [$parentRecord['parentID'], $child['childID'], date('Y-m-d'), date('Y-m-d', strtotime('+1 month'))]);
                
                $subscriptionRecord = $db->fetchOne("SELECT subscriptionID FROM subscription WHERE parentID = ? AND childID = ? LIMIT 1", 
                                                    [$parentRecord['parentID'], $child['childID']]);
                if ($subscriptionRecord) {
                    $paymentSql = "INSERT INTO payment (subscriptionID, parentID, amount, gateway, status, paidAt) 
                                  VALUES (?, ?, 99.99, 'Paymob', 'Paid', ?)";
                    $db->query($paymentSql, [$subscriptionRecord['subscriptionID'], $parentRecord['parentID'], date('Y-m-d H:i:s')]);
                    echo "✓ Subscription & Payment for Parent ID: {$parentRecord['parentID']}\n";
                }
            }
        }
    }

    echo "\n✅ Database seeding completed successfully!\n";
    echo "\n📋 Test Credentials:\n";
    echo "─────────────────────────────────────────\n";
    echo "Admin:   admin@wellucation.local / admin123\n";
    echo "Teacher: john.smith@wellucation.local / teacher123\n";
    echo "Parent:  parent1@wellucation.local / parent123\n";
    echo "─────────────────────────────────────────\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

?>
