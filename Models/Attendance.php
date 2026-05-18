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
}
?>