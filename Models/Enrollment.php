<?php
require_once 'Database.php';
class Enrollment
{
    private $enrollmentID;
    private $childID;
    private $courseID;
    private $enrolledAt;
    private $status; // Active, Completed, Canceled
    private $isWaitlisted;
    private $waitlistPosition;

    function Activate($enrollmentID)
    {
        $this->status = "Active";
        $this->enrolledAt = date("Y-m-d H:i:s");
        $sql = "UPDATE Enrollment SET status = ?, enrolledAt = ?, isWaitlisted = 0, waitlistPosition = NULL WHERE enrollmentID = ?";
        $params = [$this->status, $this->enrolledAt, $enrollmentID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function Cancel($enrollmentID)
    {
        $this->status = "Canceled";
        $sql = "UPDATE Enrollment SET status = ? WHERE enrollmentID = ?";
        $params = [$this->status, $enrollmentID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function PromoteFromWaitlist($enrollmentID)
    {
        $sql = "SELECT isWaitlisted FROM Enrollment WHERE enrollmentID = ?";
        $enrollment = Database::getInstance()->fetchOne($sql, [$enrollmentID]);

        if (!$enrollment || !$enrollment['isWaitlisted']) {
            return false;
        }

        $this->isWaitlisted = false;
        $this->status = "Active";
        $enrolledAt = date("Y-m-d H:i:s");
        $sql = "UPDATE Enrollment SET isWaitlisted = 0, status = ?, waitlistPosition = NULL, enrolledAt = ? WHERE enrollmentID = ?";
        $params = [$this->status, $enrolledAt, $enrollmentID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetEnrollmentsByChildId($childID)
    {
        $sql = "SELECT * FROM Enrollment WHERE childID = ?";
        $params = [$childID];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function GetEnrolledAtByChildID($childID)
    {
        $sql = "SELECT enrolledAt FROM Enrollment WHERE childID = ? ORDER BY enrolledAt DESC LIMIT 1";
        $params = [$childID];
        return Database::getInstance()->fetchOne($sql, $params);
    }
}
?>