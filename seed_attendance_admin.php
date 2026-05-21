<?php
/**
 * Seeds rich attendance history across all active courses (last 6 months).
 * Run after seed.php and seed_attendance_setup.php:
 *   php seed_attendance_admin.php
 */
require_once __DIR__ . '/Models/Database.php';

$db = Database::getInstance();

echo "Seeding admin attendance analytics data (6 months, all courses)...\n";

$courses = $db->fetchAll(
    "SELECT courseID, name, assignedTeacherID FROM course
     WHERE isActive = 1 AND name NOT LIKE 'Dummy%'
     ORDER BY courseID"
);

if (empty($courses)) {
    echo "No courses found. Run seed.php first.\n";
    exit(1);
}

$enrollments = $db->fetchAll(
    "SELECT DISTINCT e.childID, e.courseID
     FROM enrollment e
     INNER JOIN child c ON e.childID = c.childID
     INNER JOIN parent p ON c.parentID = p.parentID
     WHERE e.status = 'Active'"
);

if (empty($enrollments)) {
    echo "No enrollments found. Run seed_attendance_setup.php first.\n";
    exit(1);
}

// Per-course absence weight (higher = more absences in seed data)
$courseWeights = [];
foreach ($courses as $i => $course) {
    $courseWeights[(int) $course['courseID']] = 2 + ($i % 4);
}

$statusPool = ['Present', 'Present', 'Present', 'Present', 'Late', 'Absent'];
$inserted = 0;
$skipped = 0;

for ($monthsAgo = 5; $monthsAgo >= 0; $monthsAgo--) {
    $monthStart = strtotime("-{$monthsAgo} months");
    $year = (int) date('Y', $monthStart);
    $month = (int) date('n', $monthStart);
    $daysInMonth = (int) date('t', mktime(0, 0, 0, $month, 1, $year));

    echo "  Month {$year}-" . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . "...\n";

    foreach ($enrollments as $en) {
        $courseId = (int) $en['courseID'];
        $childId = (int) $en['childID'];
        $weight = $courseWeights[$courseId] ?? 3;

        $courseRow = null;
        foreach ($courses as $c) {
            if ((int) $c['courseID'] === $courseId) {
                $courseRow = $c;
                break;
            }
        }
        $teacherId = (int) ($courseRow['assignedTeacherID'] ?? 1);
        if ($teacherId <= 0) {
            $fallback = $db->fetchOne('SELECT teacherID FROM teacher ORDER BY teacherID LIMIT 1');
            $teacherId = $fallback ? (int) $fallback['teacherID'] : 1;
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $dow = (int) date('w', strtotime($date));
            if ($dow === 0 || $dow === 6) {
                continue;
            }

            $exists = $db->fetchOne(
                'SELECT attendanceID FROM attendance WHERE childID = ? AND courseID = ? AND sessionDate = ?',
                [$childId, $courseId, $date]
            );
            if ($exists) {
                $skipped++;
                continue;
            }

            $roll = ($day + $childId + $courseId + $monthsAgo) % (count($statusPool) + $weight);
            if ($roll < $weight) {
                $status = 'Absent';
            } elseif ($roll === $weight) {
                $status = 'Late';
            } else {
                $status = 'Present';
            }

            $db->query(
                "INSERT INTO attendance (childID, courseID, teacherID, sessionDate, status, markedAt, source)
                 VALUES (?, ?, ?, ?, ?, ?, 'Teacher')",
                [$childId, $courseId, $teacherId, $date, $status, $date . ' 09:00:00']
            );
            $inserted++;
        }
    }
}

echo "Done. Inserted: {$inserted}, skipped (existing): {$skipped}\n";
