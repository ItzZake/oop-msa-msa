<?php
require_once 'Database.php';
 class CourseAssignment
 {
    private $assignmentID;
    private $teacherID;
    private $courseID;
    private $assignedat;
    private $assignedby;
    private $dayofweek;
    private $startTime;
    private $endTime;

    function HasConflict($teacherId, $day, $start, $end)
    {
        $sql = "SELECT COUNT(*) as conflicts FROM CourseAssignments WHERE TeacherID = ? AND DayOfWeek = ? 
                AND ((StartTime <= ? AND EndTime > ?) OR (StartTime < ? AND EndTime >= ?))";
        $result = Database::getInstance()->fetchOne($sql, [$teacherId, $day, $start, $start, $end, $end]);
        return $result && $result['conflicts'] > 0;
    }

    function GetTeacherSchedule($teacherID)
    {
        $sql = "SELECT * FROM CourseAssignments WHERE TeacherID = ? ORDER BY DayOfWeek, StartTime";
        return Database::getInstance()->fetchAll($sql, [$teacherID]);
    }
 }
?>