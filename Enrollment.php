<?php
 class Enrollment
 {
    private $EnrollmentId;
    private $ChildId;
    private $CourseId;
    private $EnrolledAt;
    private $Status; //Active, Completed, Dropped
    private $IsWaitlisted;
    private $WaitlistPosition;

   function Activate()
   {
        // Code to activate enrollment
   }
   
   function Cancel()
   {
        // Code to cancel enrollment
   }

   function PromoteFromWaitlist()
   {
       // Code to promote child from waitlist to active enrollment
   }
 }
?>