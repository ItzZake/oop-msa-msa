<?php
require_once 'Database.php';
 class Application
 {
   private $ApplicationId;
   private $ChildId;
   private $ParentId;
   private $AdminId;
   private $Status; //Pending, Approved, Rejected
   private $reviewedAt;
   private $SubmittedAt;
   private $RejectedReason;
   private $Documents;

   function __construct($ApplicationId, $ChildId, $ParentId, $AdminId, $Status = 'pending', $reviewedAt, $SubmittedAt, $RejectedReason, $Documents)
   {
     $this->ApplicationId = $ApplicationId;
     $this->ChildId = $ChildId;
     $this->ParentId = $ParentId;
     $this->AdminId = $AdminId;
     $this->Status = $Status;
     $this->reviewedAt = $reviewedAt;
     $this->SubmittedAt = $SubmittedAt;
     $this->RejectedReason = $RejectedReason;
     $this->Documents = $Documents;
   }
   function Submit($data)
   {
     $ChildId = $data['ChildId'];
     $ParentId = $data['ParentId'];
     $CourseId = $data['CourseId'];
     $Documents = isset($data['Documents']) ? json_encode($data['Documents']) : null;
     $Status = "pending";
     $SubmittedAt = date("Y-m-d H:i:s");
     $sql = "INSERT INTO Applications (ChildId, ParentId, CourseId, Status, SubmittedAt, Documents)
     VALUES (?,?,?,?,?,?)";
     $params = [$ChildId, $ParentId, $CourseId, $Status, $SubmittedAt, $Documents];
     $stmt = Database::getInstance()->query($sql, $params);
     if ($stmt && $stmt->rowCount() > 0) {
           return true;
     }
     return false;
        // Code to submit application
   }

   function Approve($data)
   {
        $applicationId = $data['ApplicationId'];
        $adminId = $data['AdminId'] ?? null;
        $sql = "UPDATE Applications SET Status = 'approved', ReviewedAt = ?, AdminId = ? WHERE ApplicationId = ?";
        $params = [date('Y-m-d H:i:s'), $adminId, $applicationId];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
   }

   function Reject($data)
   {
        $applicationId = $data['ApplicationId'];
        $reason = $data['Reason'] ?? 'Application rejected';
        $adminId = $data['AdminId'] ?? null;
        $sql = "UPDATE Applications SET Status = 'rejected', ReviewedAt = ?, RejectedReason = ?, AdminId = ? WHERE ApplicationId = ?";
        $params = [date('Y-m-d H:i:s'), $reason, $adminId, $applicationId];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
   }

   function GetDocuments($applicationId)
   {
        $sql = "SELECT Documents FROM Applications WHERE ApplicationId = ?";
        $result = Database::getInstance()->fetchOne($sql, [$applicationId]);
        return $result ? json_decode($result['Documents'], true) : [];
   }
 }
?>