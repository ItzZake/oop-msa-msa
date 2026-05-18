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

        $sql = "INSERT INTO Submissions (AssignmentID, ChildID, ParentID, Type, Content, SubmittedAt, Status)
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
        $sql = "UPDATE Submissions SET Grade = ?, Feedback = ?, GradedAt = ?, GradedBy = ?, Status = ? WHERE SubmissionID = ?";
        $params = [$this->grade, $this->feedback, $this->gradedat, $this->gradedby, "graded", $this->submissionID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function MarkLate()
    {
        if ($this->IsLate()) {
            $this->status = "late";
            $sql = "UPDATE Submissions SET Status = ? WHERE SubmissionID = ?";
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

        $sql = "SELECT DueDate FROM Assignments WHERE AssignmentID = ?";
        $result = Database::getInstance()->fetchOne($sql, [$this->assignmentID]);
        if (!$result || empty($result['DueDate'])) {
            return false;
        }

        return strtotime($this->submittedat) > strtotime($result['DueDate']);
    }
   
 }
?>