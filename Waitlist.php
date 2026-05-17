<?php
 class Waitlist
 {
    private $waitlistID;
    private $courseID;
    private $childID;
    private $parentID;
    private $addedat;
    private $status;

    function AssignWaitlist($courseID, $childID, $ParentID)
    {
        $this->courseID = $courseID;
        $this->childID = $childID;
        $this->addedat = date("Y-m-d H:i:s");
        $this->status = "active";
        $this->parentID = $this->parentID;
        $sql = "INSERT INTO Waitlist (CourseID, ChildID, ParentID, AddedAt, Status)
        VALUES (?,?,?,?,?)";
        $params = [$this->courseID, $this->childID, $this->parentID, $this->addedat, $this->status];
        $stmt = Database::getInstance()->query($sql, $params);
        if ($stmt && $stmt->rowCount() > 0) {
            return true;
        }
            return false;
        // Code
    }

    function AcceptParent($parentID, $courseID, $childID)
    {
        // Code
    }
 }
?>