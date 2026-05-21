<?php
/**
 * AttendanceController
 *
 * Resolves role-specific attendance views, page metadata, and data for the View layer.
 */

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Attendance.php';
require_once __DIR__ . '/../Models/Course.php';

class AttendanceController
{
    private $db;
    private $attendanceModel;

    private const ROLE_TO_VIEW = [
        'teacher' => 'teacher',
        'parent'  => 'parent',
        'admin'   => 'admin',
    ];

    private const VIEW_META = [
        'teacher' => [
            'badge'    => '👩‍🏫 Teacher Attendance',
            'title'    => 'Mark Student Attendance',
            'subtitle' => 'Select your course, mark daily attendance, and submit records for enrolled students.',
        ],
        'parent' => [
            'badge'    => '👨‍👩‍👧 Parent Attendance',
            'title'    => 'Your Child\'s Attendance',
            'subtitle' => 'Track your child\'s attendance history, calendar, and rate at a glance.',
        ],
        'admin' => [
            'badge'    => '🛡️ Admin Attendance',
            'title'    => 'Attendance Reports & Analytics',
            'subtitle' => 'School-wide attendance reports, filters, charts, and course summaries.',
        ],
    ];

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->attendanceModel = new Attendance();
    }

    public function resolveView(?string $role): ?string
    {
        $normalized = strtolower(trim((string) $role));
        return self::ROLE_TO_VIEW[$normalized] ?? null;
    }

    public function getViewMeta(string $view): array
    {
        return self::VIEW_META[$view] ?? [
            'badge'    => '📋 Attendance',
            'title'    => 'Attendance Tracking',
            'subtitle' => 'Manage and monitor student attendance.',
        ];
    }

    public function getUnauthorizedRedirect(?string $role): string
    {
        $normalized = strtolower(trim((string) $role));

        if ($normalized === 'admin') {
            return 'dashboard.php';
        }
        if ($normalized === 'teacher') {
            return 'Profile.php';
        }

        return 'Index.php';
    }

    public function getTeacherIdFromUserId(int $userId): ?int
    {
        $row = $this->db->fetchOne(
            'SELECT teacherID FROM teacher WHERE userID = ?',
            [$userId]
        );
        return $row ? (int) $row['teacherID'] : null;
    }

    /**
     * Courses assigned to this teacher (assignedTeacherID may store teacherID or userID).
     */
    public function getTeacherCourses(int $userId, ?int $teacherId = null): array
    {
        $teacherId = $teacherId ?? $this->getTeacherIdFromUserId($userId);
        if (!$teacherId) {
            return [];
        }

        $sql = "SELECT c.courseID, c.name, c.description,
                       (SELECT COUNT(DISTINCT ch.childID)
                        FROM enrollment e2
                        INNER JOIN child ch ON e2.childID = ch.childID
                        INNER JOIN parent p ON ch.parentID = p.parentID
                        WHERE e2.courseID = c.courseID AND e2.status = 'Active') AS enrolledCount
                FROM course c
                WHERE c.isActive = 1
                  AND (c.assignedTeacherID = ? OR c.assignedTeacherID = ?)
                ORDER BY c.name ASC";

        return $this->db->fetchAll($sql, [$teacherId, $userId]);
    }

    public function formatChildForView(array $row): array
    {
        $gender = strtoupper((string) ($row['gender'] ?? ''));
        $emoji = $gender === 'M' ? '👦' : ($gender === 'F' ? '👧' : '👤');

        return [
            'id'    => (int) $row['childID'],
            'name'  => $row['name'] ?? '',
            'emoji' => $emoji,
            'cls'   => $row['courseName'] ?? '',
        ];
    }

    /**
     * Enrolled children with optional existing marks for a session.
     */
    public function getStudentsForCourse(int $courseId, string $sessionDate): array
    {
        $rows = $this->attendanceModel->GetEnrolledChildrenByCourse($courseId);
        $marks = $this->attendanceModel->GetSessionMarks($courseId, $sessionDate);

        $students = [];
        foreach ($rows as $row) {
            $formatted = $this->formatChildForView($row);
            $formatted['existingStatus'] = $marks[$formatted['id']] ?? null;
            $students[] = $formatted;
        }

        return $students;
    }

    /**
     * Data passed to the teacher attendance view on first load.
     */
    public function loadTeacherPageData(int $userId): array
    {
        $teacherId = $this->getTeacherIdFromUserId($userId);
        $courses = $this->getTeacherCourses($userId, $teacherId);
        $sessionDate = date('Y-m-d');
        $selectedCourseId = !empty($courses) ? (int) $courses[0]['courseID'] : 0;
        $students = $selectedCourseId > 0
            ? $this->getStudentsForCourse($selectedCourseId, $sessionDate)
            : [];

        return [
            'teacherId'          => $teacherId,
            'courses'            => $courses,
            'selectedCourseId'   => $selectedCourseId,
            'sessionDate'        => $sessionDate,
            'students'           => $students,
        ];
    }

    public function verifyTeacherOwnsCourse(int $userId, int $courseId): bool
    {
        $teacherId = $this->getTeacherIdFromUserId($userId);
        if (!$teacherId) {
            return false;
        }

        $course = $this->db->fetchOne(
            'SELECT courseID FROM course WHERE courseID = ? AND isActive = 1
             AND (assignedTeacherID = ? OR assignedTeacherID = ?)',
            [$courseId, $teacherId, $userId]
        );

        return !empty($course);
    }

    public function getParentIdFromUserId(int $userId): ?int
    {
        $row = $this->db->fetchOne(
            'SELECT parentID FROM parent WHERE userID = ?',
            [$userId]
        );
        return $row ? (int) $row['parentID'] : null;
    }

    /**
     * Children linked to this parent account.
     */
    public function getChildrenForParent(int $parentId): array
    {
        // Prefer the child row that has active enrollments; otherwise lowest childID.
        // Handles duplicate seed rows (same name + parent) without showing twice in the UI.
        $sql = "SELECT c.childID, c.name, c.gender, c.dateOfBirth,
                       (SELECT COUNT(*) FROM enrollment e
                        WHERE e.childID = c.childID AND e.status = 'Active') AS activeEnrollments
                FROM child c
                WHERE c.parentID = ?
                ORDER BY c.name ASC, activeEnrollments DESC, c.childID ASC";
        $rows = $this->db->fetchAll($sql, [$parentId]);
        $children = [];
        $seenNames = [];

        foreach ($rows as $row) {
            $nameKey = strtolower(trim((string) ($row['name'] ?? '')));
            if ($nameKey === '' || isset($seenNames[$nameKey])) {
                continue;
            }
            $seenNames[$nameKey] = true;

            $gender = strtoupper((string) ($row['gender'] ?? ''));
            $children[] = [
                'id'    => (int) $row['childID'],
                'name'  => $row['name'] ?? '',
                'emoji' => $gender === 'M' ? '👦' : ($gender === 'F' ? '👧' : '👤'),
                'age'   => $this->calculateAge($row['dateOfBirth'] ?? null),
            ];
        }

        return $children;
    }

    public function verifyChildBelongsToParent(int $parentId, int $childId): bool
    {
        $row = $this->db->fetchOne(
            'SELECT childID FROM child WHERE childID = ? AND parentID = ?',
            [$childId, $parentId]
        );
        return !empty($row);
    }

    /**
     * Course and teacher info for a child (first active enrollment).
     */
    public function getChildEnrollmentInfo(int $childId): array
    {
        $row = $this->db->fetchOne(
            "SELECT co.name AS courseName,
                    TRIM(CONCAT(COALESCE(tu.firstname, ''), ' ', COALESCE(tu.Lastname, ''))) AS teacherName
             FROM enrollment e
             INNER JOIN course co ON e.courseID = co.courseID
             LEFT JOIN teacher t ON (co.assignedTeacherID = t.teacherID OR co.assignedTeacherID = t.userID)
             LEFT JOIN user tu ON t.userID = tu.userID
             WHERE e.childID = ? AND e.status = 'Active'
             ORDER BY e.enrolledAt ASC
             LIMIT 1",
            [$childId]
        );

        return [
            'courseName'  => trim((string) ($row['courseName'] ?? '')) ?: 'Not enrolled',
            'teacherName' => trim((string) ($row['teacherName'] ?? '')) ?: '—',
        ];
    }

    /**
     * Full parent view payload for one child and month.
     */
    public function getChildAttendanceViewData(int $childId, int $year, int $month): array
    {
        $child = $this->db->fetchOne(
            'SELECT childID, name, gender, dateOfBirth FROM child WHERE childID = ?',
            [$childId]
        );

        if (!$child) {
            return [];
        }

        $enrollment = $this->getChildEnrollmentInfo($childId);
        $gender = strtoupper((string) ($child['gender'] ?? ''));
        $records = $this->attendanceModel->GetAttendanceByChildForMonth($childId, $year, $month);
        $marksByDay = $this->mapRecordsToDays($records);
        $calendar = $this->buildMonthCalendar($year, $month, $marksByDay);
        $stats = $this->calculateMonthStats($calendar);

        $firstName = explode(' ', trim((string) $child['name']))[0] ?: 'your child';

        return [
            'child' => [
                'id'          => (int) $child['childID'],
                'name'        => $child['name'],
                'emoji'       => $gender === 'M' ? '👦' : ($gender === 'F' ? '👧' : '👤'),
                'age'         => $this->calculateAge($child['dateOfBirth'] ?? null),
                'courseLabel' => $enrollment['courseName'],
                'teacherName' => $enrollment['teacherName'],
            ],
            'year'           => $year,
            'month'          => $month,
            'monthLabel'     => date('F Y', mktime(0, 0, 0, $month, 1, $year)),
            'stats'          => $stats,
            'calendar'       => $calendar,
            'notice'         => $this->buildAttendanceNotice($firstName, $stats['rate']),
        ];
    }

    /**
     * Initial data for the parent attendance page.
     */
    public function loadParentPageData(int $userId): array
    {
        $parentId = $this->getParentIdFromUserId($userId);
        if (!$parentId) {
            return [
                'parentId'         => null,
                'children'         => [],
                'selectedChildId'  => 0,
                'year'             => (int) date('Y'),
                'month'            => (int) date('n'),
                'attendance'       => null,
            ];
        }

        $children = $this->getChildrenForParent($parentId);
        $year = (int) date('Y');
        $month = (int) date('n');
        $selectedChildId = !empty($children) ? (int) $children[0]['id'] : 0;
        $attendance = $selectedChildId > 0
            ? $this->getChildAttendanceViewData($selectedChildId, $year, $month)
            : null;

        return [
            'parentId'        => $parentId,
            'children'        => $children,
            'selectedChildId' => $selectedChildId,
            'year'            => $year,
            'month'           => $month,
            'attendance'      => $attendance,
        ];
    }

    private function calculateAge(?string $dateOfBirth): ?int
    {
        if (empty($dateOfBirth)) {
            return null;
        }
        try {
            $dob = new DateTime($dateOfBirth);
            return (int) $dob->diff(new DateTime('today'))->y;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param array<int, string> $marksByDay day of month => present|absent|late
     */
    private function mapRecordsToDays(array $records): array
    {
        $marksByDay = [];
        foreach ($records as $row) {
            $day = (int) date('j', strtotime((string) $row['sessionDate']));
            $status = strtolower((string) ($row['status'] ?? ''));
            if (in_array($status, ['present', 'absent', 'late', 'excused'], true)) {
                $marksByDay[$day] = $status === 'excused' ? 'present' : $status;
            }
        }
        return $marksByDay;
    }

    /**
     * @param array<int, string> $marksByDay
     */
    private function buildMonthCalendar(int $year, int $month, array $marksByDay): array
    {
        $daysInMonth = (int) date('t', mktime(0, 0, 0, $month, 1, $year));
        $calendar = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $dow = (int) date('w', strtotime($date));

            if ($dow === 0 || $dow === 6) {
                $status = 'weekend';
            } elseif (isset($marksByDay[$day])) {
                $status = $marksByDay[$day];
            } else {
                $status = 'empty';
            }

            $calendar[] = ['date' => $day, 'status' => $status];
        }

        return $calendar;
    }

    private function calculateMonthStats(array $calendar): array
    {
        $present = 0;
        $absent = 0;
        $late = 0;

        foreach ($calendar as $day) {
            switch ($day['status']) {
                case 'present':
                    $present++;
                    break;
                case 'absent':
                    $absent++;
                    break;
                case 'late':
                    $late++;
                    break;
            }
        }

        $schoolDays = $present + $absent + $late;
        $rate = $schoolDays > 0 ? (int) round(($present / $schoolDays) * 100) : 0;

        return [
            'present' => $present,
            'absent'  => $absent,
            'late'    => $late,
            'rate'    => $rate,
        ];
    }

    private function buildAttendanceNotice(string $firstName, int $rate): array
    {
        if ($rate >= 90) {
            return ['show' => false, 'title' => '', 'body' => ''];
        }

        return [
            'show'  => true,
            'title' => 'Attendance Notice',
            'body'  => "{$firstName}'s attendance rate is below 90%. We recommend ensuring regular attendance to support their learning progress. Please contact the school if you need assistance.",
        ];
    }

    /* ── Admin analytics ─────────────────────────────────────────────── */

    public function getAdminCourseOptions(): array
    {
        return $this->db->fetchAll(
            "SELECT courseID, name
             FROM course
             WHERE isActive = 1 AND name NOT LIKE 'Dummy%'
             ORDER BY name ASC"
        );
    }

    private function adminCourseFilterSql(int $courseId): array
    {
        if ($courseId > 0) {
            return [' AND co.courseID = ? ', [$courseId]];
        }
        return ['', []];
    }

    /**
     * Per-course attendance report for a single date.
     */
    public function getAdminCourseReports(string $date, int $courseId = 0): array
    {
        [$courseSql, $courseParams] = $this->adminCourseFilterSql($courseId);

        $sql = "SELECT co.courseID, co.name AS courseName,
                       TRIM(CONCAT(COALESCE(tu.firstname, ''), ' ', COALESCE(tu.Lastname, ''))) AS teacherName,
                       (SELECT COUNT(DISTINCT e.childID)
                        FROM enrollment e
                        INNER JOIN child c ON e.childID = c.childID
                        INNER JOIN parent p ON c.parentID = p.parentID
                        WHERE e.courseID = co.courseID AND e.status = 'Active') AS totalStudents,
                       SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present,
                       SUM(CASE WHEN a.status = 'Absent' THEN 1 ELSE 0 END) AS absent,
                       SUM(CASE WHEN a.status = 'Late' THEN 1 ELSE 0 END) AS late
                FROM course co
                LEFT JOIN teacher t ON (co.assignedTeacherID = t.teacherID OR co.assignedTeacherID = t.userID)
                LEFT JOIN user tu ON t.userID = tu.userID
                LEFT JOIN attendance a ON a.courseID = co.courseID AND a.sessionDate = ?
                WHERE co.isActive = 1 AND co.name NOT LIKE 'Dummy%' {$courseSql}
                GROUP BY co.courseID, co.name, tu.firstname, tu.Lastname
                ORDER BY co.name ASC";

        $rows = $this->db->fetchAll($sql, array_merge([$date], $courseParams));
        $reports = [];

        foreach ($rows as $row) {
            $present = (int) ($row['present'] ?? 0);
            $absent  = (int) ($row['absent'] ?? 0);
            $late    = (int) ($row['late'] ?? 0);
            $marked  = $present + $absent + $late;
            $rate    = $marked > 0 ? (int) round(($present / $marked) * 100) : 0;

            $reports[] = [
                'courseId'      => (int) $row['courseID'],
                'courseName'    => $row['courseName'] ?? '',
                'teacherName'   => trim((string) ($row['teacherName'] ?? '')) ?: '—',
                'totalStudents' => (int) ($row['totalStudents'] ?? 0),
                'present'       => $present,
                'absent'        => $absent,
                'late'          => $late,
                'rate'          => $rate,
                'rateClass'     => $this->rateBadgeClass($rate),
            ];
        }

        return $reports;
    }

    public function getAdminSummary(string $date, int $courseId = 0): array
    {
        $params = [$date];
        $courseSql = '';
        if ($courseId > 0) {
            $courseSql = ' AND a.courseID = ?';
            $params[] = $courseId;
        }

        $totals = $this->db->fetchOne(
            "SELECT
                SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present,
                SUM(CASE WHEN a.status = 'Absent' THEN 1 ELSE 0 END) AS absent,
                SUM(CASE WHEN a.status = 'Late' THEN 1 ELSE 0 END) AS late
             FROM attendance a
             INNER JOIN course co ON a.courseID = co.courseID
             WHERE a.sessionDate = ? AND co.isActive = 1 AND co.name NOT LIKE 'Dummy%' {$courseSql}",
            $params
        );

        $present = (int) ($totals['present'] ?? 0);
        $absent  = (int) ($totals['absent'] ?? 0);
        $late    = (int) ($totals['late'] ?? 0);
        $marked  = $present + $absent + $late;
        $schoolRate = $marked > 0 ? (int) round(($present / $marked) * 100) : 0;

        $reports = $this->getAdminCourseReports($date, $courseId);
        $below85 = 0;
        foreach ($reports as $report) {
            $m = $report['present'] + $report['absent'] + $report['late'];
            if ($m > 0 && $report['rate'] < 85) {
                $below85++;
            }
        }

        return [
            'schoolWideRate'    => $schoolRate,
            'presentToday'      => $present,
            'absentToday'       => $absent,
            'lateToday'         => $late,
            'classesBelow85'    => $below85,
        ];
    }

    /**
     * Daily stacked counts for chart (weekdays in the given month).
     */
    public function getAdminDailyChart(int $year, int $month, int $courseId = 0): array
    {
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end = date('Y-m-t', strtotime($start));

        $params = [$start, $end];
        $courseSql = '';
        if ($courseId > 0) {
            $courseSql = ' AND a.courseID = ?';
            $params[] = $courseId;
        }

        $rows = $this->db->fetchAll(
            "SELECT a.sessionDate,
                    SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present,
                    SUM(CASE WHEN a.status = 'Absent' THEN 1 ELSE 0 END) AS absent,
                    SUM(CASE WHEN a.status = 'Late' THEN 1 ELSE 0 END) AS late
             FROM attendance a
             INNER JOIN course co ON a.courseID = co.courseID
             WHERE a.sessionDate BETWEEN ? AND ?
               AND co.isActive = 1 AND co.name NOT LIKE 'Dummy%' {$courseSql}
             GROUP BY a.sessionDate
             ORDER BY a.sessionDate ASC",
            $params
        );

        $chart = [];
        foreach ($rows as $row) {
            $dow = (int) date('w', strtotime((string) $row['sessionDate']));
            if ($dow === 0 || $dow === 6) {
                continue;
            }
            $chart[] = [
                'date'    => date('M j', strtotime((string) $row['sessionDate'])),
                'present' => (int) $row['present'],
                'absent'  => (int) $row['absent'],
                'late'    => (int) $row['late'],
            ];
        }

        return $chart;
    }

    /**
     * School-wide (or course) attendance rate for the last 6 months.
     */
    public function getAdminMonthlyChart(int $courseId = 0): array
    {
        $chart = [];
        for ($i = 5; $i >= 0; $i--) {
            $ts = strtotime("-{$i} months");
            $year = (int) date('Y', $ts);
            $month = (int) date('n', $ts);
            $start = sprintf('%04d-%02d-01', $year, $month);
            $end = date('Y-m-t', strtotime($start));

            $params = [$start, $end];
            $courseSql = '';
            if ($courseId > 0) {
                $courseSql = ' AND a.courseID = ?';
                $params[] = $courseId;
            }

            $row = $this->db->fetchOne(
                "SELECT
                    SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present,
                    SUM(CASE WHEN a.status IN ('Present','Absent','Late') THEN 1 ELSE 0 END) AS total
                 FROM attendance a
                 INNER JOIN course co ON a.courseID = co.courseID
                 WHERE a.sessionDate BETWEEN ? AND ?
                   AND co.isActive = 1 AND co.name NOT LIKE 'Dummy%' {$courseSql}",
                $params
            );

            $total = (int) ($row['total'] ?? 0);
            $present = (int) ($row['present'] ?? 0);
            $rate = $total > 0 ? (int) round(($present / $total) * 100) : 0;

            $chart[] = [
                'month' => date('M', mktime(0, 0, 0, $month, 1, $year)),
                'rate'  => $rate,
            ];
        }

        return $chart;
    }

    public function loadAdminPageData(int $courseId = 0, ?string $date = null): array
    {
        $date = $date ?? date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $date = date('Y-m-d');
        }

        $year = (int) date('Y', strtotime($date));
        $month = (int) date('n', strtotime($date));

        return [
            'courses'          => $this->getAdminCourseOptions(),
            'selectedCourseId' => $courseId,
            'filterDate'       => $date,
            'summary'          => $this->getAdminSummary($date, $courseId),
            'courseReports'    => $this->getAdminCourseReports($date, $courseId),
            'dailyChart'       => $this->getAdminDailyChart($year, $month, $courseId),
            'monthlyChart'     => $this->getAdminMonthlyChart($courseId),
            'chartMonthLabel'  => date('F Y', mktime(0, 0, 0, $month, 1, $year)),
            'reportDateLabel'  => date('F j, Y', strtotime($date)),
        ];
    }

    private function rateBadgeClass(int $rate): string
    {
        if ($rate >= 90) {
            return 'rate-good';
        }
        if ($rate < 85) {
            return 'rate-warn';
        }
        return 'rate-mid';
    }
}
