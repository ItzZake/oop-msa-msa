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

   function Activate($CourseId)
   {
          $this->Status = "Active";
          $this->EnrolledAt = date("Y-m-d H:i:s");
          $sql = "UPDATE Enrollments SET Status = ?, EnrolledAt = ? WHERE EnrollmentId = ?";
          $params = [$this->Status, $this->EnrolledAt, $this->EnrollmentId];
          $stmt = Database::getInstance()->query($sql, $params);
          if ($stmt && $stmt->rowCount() > 0) {
            return true;
          }
          $this->CourseId = $CourseId;
        // Code to activate enrollment
   }
   
   function Cancel($CourseId)
   {
           $this->Status = "Dropped";
           // Code to update enrollment status in database
           $sql = "UPDATE Enrollments SET Status = ? WHERE EnrollmentId = ?";
           $params = [$this->Status, $this->EnrollmentId];
           $stmt = Database::getInstance()->query($sql, $params);
           if ($stmt && $stmt->rowCount() > 0) {
             return true;
          }
        // Code to cancel enrollment
   }

   function PromoteFromWaitlist($CourseId)
   {
     if($this->IsWaitlisted) {
           $this->IsWaitlisted = false;
           $this->Status = "Active";
           $this->WaitlistPosition = null;
           $this->CourseId = $CourseId;
           $enrolledAt = date("Y-m-d H:i:s");
           $sql = "UPDATE Enrollments SET IsWaitlisted = ?, Status = ?, WaitlistPosition = ?, EnrolledAt = ? WHERE EnrollmentId = ?";
           $params = [$this->IsWaitlisted, $this->Status, $this->WaitlistPosition, $enrolledAt, $this->EnrollmentId];
           $stmt = Database::getInstance()->query($sql, $params);

               if ($stmt && $stmt->rowCount() > 0) {
               return true;
               }
     }
       // Code to promote child from waitlist to active enrollment
   }
   function GetEnrollmentsByChildId($ChildId)
   {
     $Database = Database::getInstance();
     $sql = "SELECT * FROM Enrollments WHERE ChildId = ?";
     $params = [$ChildId];
     $Database->fetchOne($sql, $params);
        // Code to get enrollments for child
   }
     function GetEnrolledAtByChildID()
     {
        $Database = Database::getInstance();
        $sql = "SELECT EnrolledAt FROM Enrollments WHERE ChildId = ?";
        $params = [$this->ChildId];
        return $Database->fetchOne($sql, $params);
        // Code to get enrollment date for child
     }
 }
?>