<?php
/**
 * Ensures attendance demo data: courses assigned to teachers (by teacherID),
 * children linked to parents, and active course enrollments.
 *
 * Run: php seed_attendance_setup.php
 */
require_once __DIR__ . '/Models/Database.php';

$db = Database::getInstance();

echo "Setting up attendance data...\n";

$teachers = $db->fetchAll('SELECT teacherID, userID FROM teacher ORDER BY teacherID');
$courses = $db->fetchAll('SELECT courseID, name FROM course WHERE isActive = 1 AND name NOT LIKE \'Dummy%\' ORDER BY courseID');

if (empty($courses)) {
    echo "No active courses found. Run seed.php first.\n";
    exit(1);
}

// Assign each real course to a teacher (teacherID, not userID)
foreach ($courses as $i => $course) {
    $teacher = $teachers[$i % count($teachers)];
    $db->query(
        'UPDATE course SET assignedTeacherID = ? WHERE courseID = ?',
        [$teacher['teacherID'], $course['courseID']]
    );
    echo "Course {$course['name']} -> teacherID {$teacher['teacherID']}\n";
}

// Ensure every child with a parent has at least one active enrollment
$children = $db->fetchAll(
    'SELECT c.childID, c.name, c.parentID
     FROM child c
     INNER JOIN parent p ON c.parentID = p.parentID'
);

$courseIds = array_column($courses, 'courseID');
$enrolled = 0;

foreach ($children as $child) {
    $existing = $db->fetchOne(
        'SELECT enrollmentID FROM enrollment WHERE childID = ? AND status = \'Active\' LIMIT 1',
        [$child['childID']]
    );

    if ($existing) {
        continue;
    }

    $courseId = $courseIds[$child['childID'] % count($courseIds)];
    $db->query(
        'INSERT INTO enrollment (childID, courseID, enrolledAt, status, isWaitlisted) VALUES (?, ?, ?, \'Active\', 0)',
        [$child['childID'], $courseId, date('Y-m-d H:i:s')]
    );
    echo "Enrolled {$child['name']} in courseID {$courseId}\n";
    $enrolled++;
}

echo "Done. New enrollments: {$enrolled}\n";

// Sample attendance for current month (weekdays only) so parent view has data
echo "\nSeeding sample attendance for current month...\n";
$teacherRow = $db->fetchOne('SELECT teacherID FROM teacher ORDER BY teacherID LIMIT 1');
$teacherId = $teacherRow ? (int) $teacherRow['teacherID'] : 1;
$year = (int) date('Y');
$month = (int) date('n');
$daysInMonth = (int) date('t');

$statusCycle = ['Present', 'Present', 'Present', 'Late', 'Absent'];
$seeded = 0;

$enrollments = $db->fetchAll(
    'SELECT DISTINCT e.childID, e.courseID
     FROM enrollment e
     INNER JOIN child c ON e.childID = c.childID
     INNER JOIN parent p ON c.parentID = p.parentID
     WHERE e.status = \'Active\''
);

foreach ($enrollments as $en) {
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $dow = (int) date('w', strtotime($date));
        if ($dow === 0 || $dow === 6) {
            continue;
        }

        $exists = $db->fetchOne(
            'SELECT attendanceID FROM attendance WHERE childID = ? AND courseID = ? AND sessionDate = ?',
            [$en['childID'], $en['courseID'], $date]
        );
        if ($exists) {
            continue;
        }

        $status = $statusCycle[($day + (int) $en['childID']) % count($statusCycle)];
        $db->query(
            'INSERT INTO attendance (childID, courseID, teacherID, sessionDate, status, markedAt, source)
             VALUES (?, ?, ?, ?, ?, ?, \'Teacher\')',
            [$en['childID'], $en['courseID'], $teacherId, $date, $status, date('Y-m-d H:i:s')]
        );
        $seeded++;
    }
}

echo "Sample attendance records added: {$seeded}\n";
