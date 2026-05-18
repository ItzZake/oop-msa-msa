<?php
require_once 'Database.php';
 class ARSession
 {
    private $sessionID;
    private $teacherID;
    private $courseID;
    private $startedat;
    private $endedat;
    private $rulestriggered;
    private $deviceinfo;

    function Start($teacherID, $courseID)
    {
        $this->teacherID = $teacherID;
        $this->courseID = $courseID;
        $this->startedat = date('Y-m-d H:i:s');
        $sql = "INSERT INTO ARSessions (TeacherID, CourseID, StartedAt, IsActive) VALUES (?, ?, ?, 1)";
        $stmt = Database::getInstance()->query($sql, [$teacherID, $courseID, $this->startedat]);
        if ($stmt && $stmt->rowCount() > 0) {
            $this->sessionID = Database::getInstance()->getConnection()->lastInsertId();
            return true;
        }
        return false;
    }

    function End()
    {
        $this->endedat = date('Y-m-d H:i:s');
        $sql = "UPDATE ARSessions SET EndedAt = ?, IsActive = 0 WHERE SessionID = ?";
        $stmt = Database::getInstance()->query($sql, [$this->endedat, $this->sessionID]);
        return $stmt && $stmt->rowCount() > 0;
    }

    function LogRuleTrigger($ruleID)
    {
        $sql = "INSERT INTO ARSessionTriggers (SessionID, RuleID, TriggeredAt) VALUES (?, ?, ?)";
        $stmt = Database::getInstance()->query($sql, [$this->sessionID, $ruleID, date('Y-m-d H:i:s')]);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetDuration()
    {
        if (empty($this->startedat) || empty($this->endedat)) {
            $sql = "SELECT StartedAt, EndedAt FROM ARSessions WHERE SessionID = ?";
            $row = Database::getInstance()->fetchOne($sql, [$this->sessionID]);
            if (!$row) {
                return 0;
            }
            $this->startedat = $row['StartedAt'];
            $this->endedat = $row['EndedAt'];
        }
        $start = new DateTime($this->startedat);
        $end = new DateTime($this->endedat);
        return $start->diff($end)->format('%h:%I:%S');
    }
 }
?>