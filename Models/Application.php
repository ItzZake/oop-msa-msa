<?php
require_once 'Database.php';
class Application
{
    private $applicationID;
    private $childID;
    private $parentID;
    private $status; // Pending, Approved, Rejected
    private $reviewedAt;
    private $submittedAt;
    private $rejectionReason;
    private $documents;

    function __construct($applicationID = null, $childID = null, $parentID = null, $status = 'Pending', $reviewedAt = null, $submittedAt = null, $rejectionReason = null, $documents = null)
    {
        $this->applicationID = $applicationID;
        $this->childID = $childID;
        $this->parentID = $parentID;
        $this->status = $status;
        $this->reviewedAt = $reviewedAt;
        $this->submittedAt = $submittedAt;
        $this->rejectionReason = $rejectionReason;
        $this->documents = $documents;
    }

    function Submit($data)
    {
        $childID = $data['ChildId'];
        $parentID = $data['ParentId'];
        $documents = isset($data['Documents']) ? json_encode($data['Documents']) : null;
        $status = 'Pending';
        $submittedAt = date("Y-m-d H:i:s");

        $sql = "INSERT INTO Application (parentID, childID, status, reviewedAt, rejectionReason, documents)
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$parentID, $childID, $status, null, null, $documents];
        $stmt = Database::getInstance()->query($sql, $params);

        return $stmt && $stmt->rowCount() > 0;
    }

    function Approve($data)
    {
        $applicationId = $data['ApplicationId'];
        $sql = "UPDATE Application SET status = 'Approved', reviewedAt = ?, rejectionReason = NULL WHERE applicationID = ?";
        $params = [date('Y-m-d H:i:s'), $applicationId];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function Reject($data)
    {
        $applicationId = $data['ApplicationId'];
        $reason = $data['Reason'] ?? 'Application rejected';
        $sql = "UPDATE Application SET status = 'Rejected', reviewedAt = ?, rejectionReason = ? WHERE applicationID = ?";
        $params = [date('Y-m-d H:i:s'), $reason, $applicationId];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetDocuments($applicationId)
    {
        $sql = "SELECT documents FROM Application WHERE applicationID = ?";
        $result = Database::getInstance()->fetchOne($sql, [$applicationId]);
        return $result ? json_decode($result['documents'], true) : [];
    }
}
?>