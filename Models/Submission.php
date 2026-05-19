<?php
require_once 'Database.php';
require_once 'Assignment.php';
 class Submission
 {
    private $submissionID;
    private $assignmentID;
    private $childID;
    private $parentID;
    private $type;
    private $content;
    private $photopath;
    private $submittedat;
    private $status;
    private $grade;
    private $feedback;
    private $gradedat;
    private $gradedby;

    function Submit($type, $content)
    {
        $this->type = $type;
        $this->content = $content;
        $this->submittedat = date("Y-m-d H:i:s");
        $this->status = $this->IsLate() ? "late" : "submitted";

        $sql = "INSERT INTO submission (assignmentID, childID, parentID, type, content, submittedAt, status)
         VALUES (?,?,?,?,?,?,?)";
        $params = [$this->assignmentID, $this->childID, $this->parentID, $this->type, $this->content, $this->submittedat, $this->status];
        $stmt = Database::getInstance()->query($sql, $params);
        if ($stmt && $stmt->rowCount() > 0) {
            $this->submissionID = Database::getInstance()->getConnection()->lastInsertId();
            return true;
        }
        return false;
    }

    function Grade($grade, $feedback, $teacherID)
    {
        $this->grade = $grade;
        $this->feedback = $feedback;
        $this->gradedat = date("Y-m-d H:i:s");
        $this->gradedby = $teacherID;
        $sql = "UPDATE submission SET grade = ?, feedback = ?, gradedAt = ?, gradedBy = ?, status = ? WHERE submissionID = ?";
        $params = [$this->grade, $this->feedback, $this->gradedat, $this->gradedby, "graded", $this->submissionID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function SaveGrade($submissionId, $grade, $feedback)
    {
        $sql = "UPDATE submission SET grade = ?, feedback = ?, gradedAt = ?, status = 'Graded' WHERE submissionID = ?";
        $params = [$grade, $feedback, date('Y-m-d H:i:s'), $submissionId];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function Insert($childId, $assignmentId, $type, $content, $photoPath = null)
    {
        $sql = "INSERT INTO submission (childID, assignmentID, type, content, photoPath, submittedAt, status) VALUES (?, ?, ?, ?, ?, ?, 'Submitted')";
        $params = [$childId, $assignmentId, $type, $content, $photoPath, date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function MarkLate()
    {
        if ($this->IsLate()) {
            $this->status = "late";
            $sql = "UPDATE submission SET status = ? WHERE submissionID = ?";
            $params = [$this->status, $this->submissionID];
            $stmt = Database::getInstance()->query($sql, $params);
            return $stmt && $stmt->rowCount() > 0;
        }
        return false;
    }

    function IsLate()
    {
        if (empty($this->submittedat) || empty($this->assignmentID)) {
            return false;
        }

        $sql = "SELECT dueDate FROM assignment WHERE assignmentID = ?";
        $result = Database::getInstance()->fetchOne($sql, [$this->assignmentID]);
        if (!$result || empty($result['DueDate'])) {
            return false;
        }

        return strtotime($this->submittedat) > strtotime($result['DueDate']);
    }
   
 }
?>