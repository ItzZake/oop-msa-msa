<?php
require_once 'Database.php';
class Waitlist
{
    private $waitlistID;
    private $courseID;
    private $childID;
    private $parentID;
    private $addedAt;
    private $status;

    function AssignWaitlist($courseID, $childID, $parentID)
    {
        $this->courseID = $courseID;
        $this->childID = $childID;
        $this->addedAt = date("Y-m-d H:i:s");
        $this->status = "Waiting";
        $this->parentID = $parentID;

        $sql = "INSERT INTO Waitlist (courseID, childID, parentID, addedAt, status)
                VALUES (?, ?, ?, ?, ?)";
        $params = [$this->courseID, $this->childID, $this->parentID, $this->addedAt, $this->status];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function AcceptParent($parentID, $courseID, $childID)
    {
        $sql = "SELECT * FROM Waitlist WHERE parentID = ? AND courseID = ? AND childID = ? AND status = 'Waiting' LIMIT 1";
        $entry = Database::getInstance()->fetchOne($sql, [$parentID, $courseID, $childID]);
        if (!$entry) {
            return false;
        }

        $updateSql = "UPDATE Waitlist SET status = 'Enrolled' WHERE waitlistID = ?";
        $updateParams = [$entry['waitlistID']];
        $stmt = Database::getInstance()->query($updateSql, $updateParams);
        if (!($stmt && $stmt->rowCount() > 0)) {
            return false;
        }

        $sqlEnroll = "INSERT INTO Enrollment (childID, courseID, enrolledAt, status, isWaitlisted) VALUES (?, ?, ?, 'Active', 0)";
        $paramsEnroll = [$childID, $courseID, date('Y-m-d H:i:s')];
        $stmtEnroll = Database::getInstance()->query($sqlEnroll, $paramsEnroll);
        return $stmtEnroll && $stmtEnroll->rowCount() > 0;
    }
}
?>