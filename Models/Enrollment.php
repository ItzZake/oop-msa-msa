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

   function Activate($EnrollmentId)
   {
          $this->Status = "Active";
          $this->EnrolledAt = date("Y-m-d H:i:s");
          $sql = "UPDATE Enrollments SET Status = ?, EnrolledAt = ?, IsWaitlisted = 0, WaitlistPosition = NULL WHERE EnrollmentId = ?";
          $params = [$this->Status, $this->EnrolledAt, $EnrollmentId];
          $stmt = Database::getInstance()->query($sql, $params);
          return $stmt && $stmt->rowCount() > 0;
   }
   
   function Cancel($EnrollmentId)
   {
           $this->Status = "Dropped";
           $sql = "UPDATE Enrollments SET Status = ? WHERE EnrollmentId = ?";
           $params = [$this->Status, $EnrollmentId];
           $stmt = Database::getInstance()->query($sql, $params);
           return $stmt && $stmt->rowCount() > 0;
   }

   function PromoteFromWaitlist($EnrollmentId)
   {
     $sql = "SELECT IsWaitlisted FROM Enrollments WHERE EnrollmentId = ?";
     $enrollment = Database::getInstance()->fetchOne($sql, [$EnrollmentId]);
     
     if (!$enrollment || !$enrollment['IsWaitlisted']) {
         return false;
     }
     
     $this->IsWaitlisted = false;
     $this->Status = "Active";
     $enrolledAt = date("Y-m-d H:i:s");
     $sql = "UPDATE Enrollments SET IsWaitlisted = 0, Status = ?, WaitlistPosition = NULL, EnrolledAt = ? WHERE EnrollmentId = ?";
     $params = [$this->Status, $enrolledAt, $EnrollmentId];
     $stmt = Database::getInstance()->query($sql, $params);
     return $stmt && $stmt->rowCount() > 0;
   }
   function GetEnrollmentsByChildId($ChildId)
   {
     $Database = Database::getInstance();
     $sql = "SELECT * FROM Enrollments WHERE ChildId = ?";
     $params = [$ChildId];
     return $Database->fetchAll($sql, $params);
   }
     function GetEnrolledAtByChildID($ChildId)
     {
        $Database = Database::getInstance();
        $sql = "SELECT EnrolledAt FROM Enrollments WHERE ChildId = ? ORDER BY EnrolledAt DESC LIMIT 1";
        $params = [$ChildId];
        return $Database->fetchOne($sql, $params);
     }
 }
?>