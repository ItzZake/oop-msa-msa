<?php
require_once 'Database.php';
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
        $this->parentID = $ParentID;
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
        $sql = "SELECT * FROM Waitlist WHERE ParentID = ? AND CourseID = ? AND ChildID = ? AND Status = 'active' LIMIT 1";
        $entry = Database::getInstance()->fetchOne($sql, [$parentID, $courseID, $childID]);
        if (!$entry) {
            return false;
        }

        $updateSql = "UPDATE Waitlist SET Status = 'accepted', AcceptedAt = ? WHERE WaitlistID = ?";
        $updateParams = [date('Y-m-d H:i:s'), $entry['WaitlistID']];
        $stmt = Database::getInstance()->query($updateSql, $updateParams);
        if (!($stmt && $stmt->rowCount() > 0)) {
            return false;
        }

        $sqlEnroll = "INSERT INTO Enrollments (ChildId, CourseId, EnrolledAt, Status, IsWaitlisted) VALUES (?, ?, ?, 'Active', 0)";
        $paramsEnroll = [$childID, $courseID, date('Y-m-d H:i:s')];
        $stmtEnroll = Database::getInstance()->query($sqlEnroll, $paramsEnroll);
        return $stmtEnroll && $stmtEnroll->rowCount() > 0;
    }
 }
?>