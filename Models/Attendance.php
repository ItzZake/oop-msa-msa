<?php
require_once 'Teacher.php';
require_once 'Course.php';
require_once 'Child.php';
 class Attendance
 {
    Private $AttendanceId;
    private $ChildId;
    private $CourseId;
    private $TeacherId;
    private $SesionDate;
    private $Status; //Present, Absent, Late, Excused
    private $MarkedAt;
    private $Source; //Teacher, Auto

    function MarkPresent($data)
    {

        $Attendance = new Attendance();
        $Attendance->ChildId = $data['ChildId'];
        $Attendance->CourseId = $data['CourseId'];
        $Attendance->SesionDate = date("Y-m-d");
        $Attendance->Status = "Present";
        $Attendance->MarkedAt = date("Y-m-d H:i:s");
        $Attendance->Source = "Teacher";
        // Code to save attendance record to database
        $sql = "INSERT INTO Attendance (ChildId, CourseId, SessionDate, Status, MarkedAt, Source)
         VALUES (?,?,?,?,?,?)";
         $params = [$Attendance->ChildId, $Attendance->CourseId, $Attendance->SesionDate, $Attendance->Status, $Attendance->MarkedAt, $Attendance->Source];
         $stmt = Database::getInstance()->query($sql, $params);
         if ($stmt && $stmt->rowCount() > 0) {
           return true;
        }
    }

    function MarkAbsent($data)
    {
        $Attendance = new Attendance();
        $Attendance->ChildId = $data['ChildId'];
        $Attendance->CourseId = $data['CourseId'];
        $Attendance->SesionDate = date("Y-m-d");
        $Attendance->Status = "Absent";
        $Attendance->MarkedAt = date("Y-m-d H:i:s");
        $Attendance->Source = "Teacher";
        // Code to save attendance record to database
        $sql = "INSERT INTO Attendance (ChildId, CourseId, SessionDate, Status, MarkedAt, Source)
         VALUES (?,?,?,?,?,?)";
         $params = [$Attendance->ChildId, $Attendance->CourseId, $Attendance->SesionDate, $Attendance->Status, $Attendance->MarkedAt, $Attendance->Source];
         $stmt = Database::getInstance()->query($sql, $params);
         if ($stmt && $stmt->rowCount() > 0) {
           return true;
        }
    }

    function MarkExcused($data)
    {
        $Attendance = new Attendance();
        $Attendance->ChildId = $data['ChildId'];
        $Attendance->CourseId = $data['CourseId'];
        $Attendance->SesionDate = date("Y-m-d");
        $Attendance->Status = "Excused";
        $Attendance->MarkedAt = date("Y-m-d H:i:s");
        $Attendance->Source = "Teacher";
        // Code to save attendance record to database
        $sql = "INSERT INTO Attendance (ChildId, CourseId, SessionDate, Status, MarkedAt, Source)
         VALUES (?,?,?,?,?,?)";
         $params = [$Attendance->ChildId, $Attendance->CourseId, $Attendance->SesionDate, $Attendance->Status, $Attendance->MarkedAt, $Attendance->Source];
         $stmt = Database::getInstance()->query($sql, $params);
         if ($stmt && $stmt->rowCount() > 0) {
           return true;
        }
    }   
    function GetAttendanceByChildId($ChildId, $fromDate, $toDate)
    {
        $Database = Database::getInstance();
        $sql = "SELECT * FROM Attendance WHERE ChildId = ? AND SessionDate BETWEEN ? AND ?";
        $params = [$ChildId, $fromDate, $toDate];
        return $Database->fetchAll($sql, $params);
        // Code to get attendance records for child within date range
    }
    function GetStreakCount($ChildId, $CourseId)
    {
        // Get consecutive present days
        $sql = "SELECT COUNT(*) as streak FROM Attendance 
                WHERE ChildId = ? AND CourseId = ? AND Status = 'Present'
                AND SessionDate >= DATE_SUB(CURDATE(), INTERVAL 100 DAY)
                ORDER BY SessionDate DESC";
        $params = [$ChildId, $CourseId];
        $result = Database::getInstance()->fetchOne($sql, $params);
        
        return $result['streak'] ?? 0;
    }

    function AutoAssignAbsent($SessionId)
    {
        // Get all enrolled children for this session's course
        $sql = "SELECT DISTINCT e.ChildId FROM Enrollments e
                INNER JOIN AttendanceSessions s ON e.CourseId = s.CourseId
                WHERE s.SessionId = ? AND e.Status = 'Active'
                AND e.ChildId NOT IN (
                    SELECT ChildId FROM Attendance WHERE SessionDate = CURDATE() AND CourseId = ?
                )";
        
        $sessionSql = "SELECT CourseId FROM AttendanceSessions WHERE SessionId = ?";
        $sessionData = Database::getInstance()->fetchOne($sessionSql, [$SessionId]);
        
        if (!$sessionData) {
            return ['count' => 0, 'message' => 'Session not found'];
        }
        
        $params = [$SessionId, $sessionData['CourseId']];
        $unmarked = Database::getInstance()->fetchAll($sql, $params);
        
        $count = 0;
        foreach ($unmarked as $child) {
            $insertSql = "INSERT INTO Attendance (ChildId, CourseId, SessionDate, Status, MarkedAt, Source) 
                          VALUES (?, ?, ?, ?, ?, ?)";
            $insertParams = [$child['ChildId'], $sessionData['CourseId'], date('Y-m-d'), 'Absent', date('Y-m-d H:i:s'), 'Auto'];
            $stmt = Database::getInstance()->query($insertSql, $insertParams);
            if ($stmt && $stmt->rowCount() > 0) {
                $count++;
            }
        }
        
        return ['count' => $count, 'message' => "Auto-marked {$count} children absent"];
    }
 }
?>