<?php
require_once 'Database.php';
class Attendance
{
    private $attendanceID;
    private $childID;
    private $courseID;
    private $teacherID;
    private $sessionDate;
    private $status; // Present, Absent, Late, Excused
    private $markedAt;
    private $source; // Teacher, Auto

    function MarkPresent($data)
    {
        if (empty($data['TeacherId'])) {
            return false;
        }

        $sql = "INSERT INTO Attendance (childID, courseID, teacherID, sessionDate, status, markedAt, source)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $data['ChildId'],
            $data['CourseId'],
            $data['TeacherId'],
            date("Y-m-d"),
            'Present',
            date("Y-m-d H:i:s"),
            'Teacher'
        ];

        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function MarkAbsent($data)
    {
        if (empty($data['TeacherId'])) {
            return false;
        }

        $sql = "INSERT INTO Attendance (childID, courseID, teacherID, sessionDate, status, markedAt, source)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $data['ChildId'],
            $data['CourseId'],
            $data['TeacherId'],
            date("Y-m-d"),
            'Absent',
            date("Y-m-d H:i:s"),
            'Teacher'
        ];

        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function MarkExcused($data)
    {
        if (empty($data['TeacherId'])) {
            return false;
        }

        $sql = "INSERT INTO Attendance (childID, courseID, teacherID, sessionDate, status, markedAt, source)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $data['ChildId'],
            $data['CourseId'],
            $data['TeacherId'],
            date("Y-m-d"),
            'Excused',
            date("Y-m-d H:i:s"),
            'Teacher'
        ];

        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetAttendanceByChildId($childID, $fromDate, $toDate)
    {
        $sql = "SELECT * FROM Attendance WHERE childID = ? AND sessionDate BETWEEN ? AND ?";
        $params = [$childID, $fromDate, $toDate];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function GetStreakCount($childID, $courseID)
    {
        $sql = "SELECT COUNT(*) as streak FROM Attendance 
                WHERE childID = ? AND courseID = ? AND status = 'Present'
                AND sessionDate >= DATE_SUB(CURDATE(), INTERVAL 100 DAY)";
        $params = [$childID, $courseID];
        $result = Database::getInstance()->fetchOne($sql, $params);

        return $result['streak'] ?? 0;
    }

    function AutoAssignAbsent($attendanceID)
    {
        $sessionSql = "SELECT courseID FROM Attendance WHERE attendanceID = ?";
        $sessionData = Database::getInstance()->fetchOne($sessionSql, [$attendanceID]);

        if (!$sessionData) {
            return ['count' => 0, 'message' => 'Session not found'];
        }

        $sql = "SELECT DISTINCT e.childID FROM Enrollment e
                WHERE e.courseID = ? AND e.status = 'Active'
                AND e.childID NOT IN (
                    SELECT childID FROM Attendance WHERE sessionDate = CURDATE() AND courseID = ?
                )";

        $params = [$sessionData['courseID'], $sessionData['courseID']];
        $unmarked = Database::getInstance()->fetchAll($sql, $params);

        $count = 0;
        foreach ($unmarked as $child) {
            $insertSql = "INSERT INTO Attendance (childID, courseID, teacherID, sessionDate, status, markedAt, source) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertParams = [$child['childID'], $sessionData['courseID'], null, date('Y-m-d'), 'Absent', date('Y-m-d H:i:s'), 'Auto'];
            $stmt = Database::getInstance()->query($insertSql, $insertParams);
            if ($stmt && $stmt->rowCount() > 0) {
                $count++;
            }
        }

        return ['count' => $count, 'message' => "Auto-marked {$count} children absent"];
    }

    function InsertRecord($childID, $courseID, $status, $timestamp = null)
    {
        $timestamp = $timestamp ?? date('Y-m-d H:i:s');
        $sql = "INSERT INTO Attendance (childID, courseID, sessionDate, status, markedAt, source)
                VALUES (?, ?, ?, ?, ?, 'Auto')";
        $params = [
            $childID,
            $courseID,
            date('Y-m-d', strtotime($timestamp)),
            ucfirst(strtolower($status)),
            $timestamp
        ];

        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetAllEnrolledChildren()
    {
        require_once 'Child.php';
        $sql = "SELECT DISTINCT c.* FROM Child c
                INNER JOIN Enrollment e ON c.childID = e.childID
                WHERE e.status = 'Active'";
        $rows = Database::getInstance()->fetchAll($sql);
        $children = [];
        foreach ($rows as $row) {
            $children[] = new Child(
                $row['childID'],
                $row['parentID'],
                $row['dateOfBirth'],
                $row['gender'],
                $row['allergies'] ?? null,
                $row['medicalNotes'] ?? null,
                $row['emergencyContact'] ?? null,
                $row['enrollmentStatus'] ?? null,
                $row['photoPath'] ?? null,
                $row['name'] ?? null
            );
        }
        return $children;
    }

    function GetConsecutiveAbsences($childID)
    {
        $sql = "SELECT status FROM Attendance
                WHERE childID = ? AND sessionDate <= CURDATE()
                ORDER BY sessionDate DESC
                LIMIT 10";
        $rows = Database::getInstance()->fetchAll($sql, [$childID]);
        $streak = 0;
        foreach ($rows as $row) {
            if (($row['status'] ?? '') === 'Absent') {
                $streak++;
            } else {
                break;
            }
        }
        return $streak;
    }

    function FlagAbsenceStreak($childID)
    {
        $sql = "INSERT INTO Flag (childID, type, details, isActive, createdAt)
                VALUES (?, 'Absence Streak', ?, ?)";
        $params = [$childID, 'Child has reached an absence streak threshold', date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetClosedUnmarkedSessions()
    {
        $sql = "SELECT a.courseID, a.sessionDate FROM Attendance a
                GROUP BY a.courseID, a.sessionDate
                HAVING a.sessionDate < CURDATE()
                AND COUNT(*) < (
                    SELECT COUNT(*) FROM Enrollment e
                    WHERE e.courseID = a.courseID AND e.status = 'Active'
                )";

        $rows = Database::getInstance()->fetchAll($sql);
        $sessions = [];
        foreach ($rows as $row) {
            $sessions[] = new AttendanceSession($row['courseID'], $row['sessionDate']);
        }
        return $sessions;
    }

    function GetReportByChildAndDateRange($childID, $fromDate, $toDate)
    {
        $sql = "SELECT * FROM Attendance WHERE childID = ? AND sessionDate BETWEEN ? AND ? ORDER BY sessionDate DESC";
        return Database::getInstance()->fetchAll($sql, [$childID, $fromDate, $toDate]);
    }

    function GetAttendanceRatesPerClass()
    {
        $sql = "SELECT courseID,
                       SUM(status = 'Present') / COUNT(*) * 100 AS attendanceRate,
                       COUNT(*) AS totalSessions
                FROM Attendance
                GROUP BY courseID";
        return Database::getInstance()->fetchAll($sql);
    }

    function GetBelowThreshold($threshold = 75)
    {
        $sql = "SELECT c.childID, c.name AS childName, a.courseID,
                       ROUND(SUM(a.status = 'Present') / COUNT(*) * 100, 2) AS attendanceRate
                FROM Attendance a
                INNER JOIN Child c ON a.childID = c.childID
                INNER JOIN Enrollment e ON a.childID = e.childID AND a.courseID = e.courseID
                WHERE e.status = 'Active'
                GROUP BY a.childID, a.courseID
                HAVING attendanceRate < ?";
        return Database::getInstance()->fetchAll($sql, [$threshold]);
    }
}

class AttendanceSession
{
    private $courseID;
    private $sessionDate;

    function __construct($courseID, $sessionDate)
    {
        $this->courseID = $courseID;
        $this->sessionDate = $sessionDate;
    }

    function GetSessionId()
    {
        return $this->courseID . '|' . $this->sessionDate;
    }

    function GetCourseId()
    {
        return $this->courseID;
    }
}
?>