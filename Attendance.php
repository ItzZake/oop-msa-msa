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

    function GetStreakCount(ChildId, CourseId)
    {
        // Code to calculate attendance streak count for child in course
    }

    function AutoAssignAbsent(SessionId)
    {
        
        // Code to automatically mark absent for children not marked present by end of day
    }
 }
?>