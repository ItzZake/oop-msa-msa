<?php
require_once 'Database.php';
class Enrollment
{
    private $enrollmentID;
    private $childID;
    private $courseID;
    private $parentID;
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

    function GetEnrolledCoursesByChildId($childID)
    {
        $sql = "SELECT courseID FROM Enrollment WHERE childID = ? AND status = 'Active'";
        return Database::getInstance()->fetchAll($sql, [$childID]);
    }

    function GetReportByDateRange($startDate, $endDate)
    {
        $sql = "SELECT * FROM Enrollment WHERE enrolledAt BETWEEN ? AND ?";
        return Database::getInstance()->fetchAll($sql, [$startDate, $endDate]);
    }

    function Enroll($childId, $courseId)
    {
        $sql = "INSERT INTO Enrollment (childID, courseID, enrolledAt, status, isWaitlisted) VALUES (?, ?, ?, 'Active', 0)";
        $params = [$childId, $courseId, date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetEnrollmentById($enrollmentId)
    {
        $sql = "SELECT * FROM Enrollment WHERE enrollmentID = ?";
        $result = Database::getInstance()->fetchOne($sql, [$enrollmentId]);
        if (!$result) {
            return null;
        }
        $instance = new self();
        $instance->enrollmentID = $result['enrollmentID'];
        $instance->childID = $result['childID'];
        $instance->courseID = $result['courseID'];
        $instance->enrolledAt = $result['enrolledAt'];
        $instance->status = $result['status'];
        $instance->isWaitlisted = $result['isWaitlisted'];
        $instance->waitlistPosition = $result['waitlistPosition'] ?? null;
        $instance->parentID = $result['parentID'] ?? null;
        return $instance;
    }

    function GetParentId()
    {
        return $this->parentID;
    }

    function GetUnmarkedChildren($sessionId)
    {
        $parts = explode('|', $sessionId, 2);
        if (count($parts) !== 2) {
            return [];
        }
        [$courseId, $sessionDate] = $parts;

        require_once 'Child.php';
        $sql = "SELECT c.* FROM Child c
                INNER JOIN Enrollment e ON c.childID = e.childID
                WHERE e.courseID = ? AND e.status = 'Active'
                  AND c.childID NOT IN (
                      SELECT childID FROM Attendance WHERE courseID = ? AND sessionDate = ?
                  )";
        $rows = Database::getInstance()->fetchAll($sql, [$courseId, $courseId, $sessionDate]);
        $children = [];
        foreach ($rows as $row) {
            $children[] = new Child(
                $row['childID'],
                $row['parentID'],
                $row['dateOfBirth'],
                $row['gender'],
                $row['allergies'] ?? null,
                $row['medicalNotes'] ?? null,
                $row['emergencyContact'] ?? null,
                $row['enrollmentStatus'] ?? null,
                $row['photoPath'] ?? null,
                $row['name'] ?? null
            );
        }
        return $children;
    }

    function GetAggregateMetrics()
    {
        $sql = "SELECT 
                    SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) AS activeEnrollments,
                    SUM(CASE WHEN status != 'Active' THEN 1 ELSE 0 END) AS inactiveEnrollments,
                    COUNT(*) AS totalEnrollments
                FROM Enrollment";
        return Database::getInstance()->fetchOne($sql);
    }
}
?>