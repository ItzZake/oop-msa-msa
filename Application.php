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

   function Submit()
   {
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