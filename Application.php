<?php
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
        // Code to approve application
   }

   function Reject($data)
   {
        // Code to reject application with reason
   }

   function GetDocuments()
   {
        // Code to retrieve application documents
   }
 }
?>