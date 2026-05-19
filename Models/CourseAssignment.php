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

    function InsertAssignment($teacherId, $courseId)
    {
        $sql = "INSERT INTO CourseAssignments (TeacherID, CourseID, AssignedAt) VALUES (?, ?, ?)";
        $params = [$teacherId, $courseId, date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetCoursesByTeacher($teacherId)
    {
        $sql = "SELECT * FROM CourseAssignments WHERE TeacherID = ?";
        return Database::getInstance()->fetchAll($sql, [$teacherId]);
    }

    function getWeeklyScheduleByTeacher($teacherId)
    {
        $sql = "SELECT ca.*, c.courseName FROM CourseAssignments ca 
                LEFT JOIN Course c ON ca.CourseID = c.courseID
                WHERE ca.TeacherID = ? ORDER BY ca.DayOfWeek, ca.StartTime";
        return Database::getInstance()->fetchAll($sql, [$teacherId]);
    }
 }
?>