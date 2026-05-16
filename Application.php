<?php
 class Application
 {
   private $ApplicationId;
   private $ChildId;
   private $ChildId;
   private $Status; //Pending, Approved, Rejected
   private $reviewedAt;
   private $RejectedReason;
   private $Documents;

   function Submit()
   {
        // Code to submit application
   }

   function Approve(AdminId)
   {
        // Code to approve application
   }

   function Reject(AdminId, reason)
   {
        // Code to reject application with reason
   }

   function GetDocuments()
   {
        // Code to retrieve application documents
   }
 }
?>