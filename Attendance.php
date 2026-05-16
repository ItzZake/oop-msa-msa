<?php
 class Attendance
 {
    Private $AttendanceId;
    private $ChildId;
    private $CourseId;
    private $CourseId;
    private $SesionDate;
    private $Status; //Present, Absent, Late, Excused
    private $MarkedAt;
    private $Source; //Teacher, Auto

    function MarkPresent()
    {
        // Code to mark attendance as present
    }

    function MarkAbsent()
    {
        // Code to mark attendance as absent
    }

    function MarkExecused(ChildId, CourseId)
    {
        // Code to mark attendance as excused
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