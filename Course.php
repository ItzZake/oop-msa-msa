<?php
  require_once 'Database.php';
  require_once 'Enrollment.php';
  require_once 'Teacher.php';
  require_once 'Parent.php';
  require_once 'Child.php';
  require_once 'Waitlist.php';
  class Course
  {
   private $courseId;
   private $Name;
   private $Description;
   private $AgeMin;
   private $AgeMax;
   private $MaxCapacity;
   private $CurrentEnrollment;
   private $AssignedTeacherId;
   private $Price;
   private $Schedule; //json
   private $IsActive;

   function Create($data)
   {
      $Name = $data['Name'];
      $Description = $data['Description'];
      $AgeMin = $data['AgeMin'];
      $AgeMax = $data['AgeMax'];
      $MaxCapacity = $data['MaxCapacity'];
      $Price = $data['Price'];
      $Schedule = json_encode($data['Schedule']);
      $IsActive = true;
      $sql = "INSERT INTO Courses (Name, Description, AgeMin, AgeMax, MaxCapacity, Price, Schedule, IsActive)
       VALUES (?,?,?,?,?,?,?,?)";
       $params = [$Name, $Description, $AgeMin, $AgeMax, $MaxCapacity, $Price, $Schedule, $IsActive];
       $stmt = Database::getInstance()->query($sql, $params);
       if ($stmt && $stmt->rowCount() > 0) {
         return true;
      }
   }
   function Edit($courseId, $data)
   {
      $Name = $data['Name'];
      $Description = $data['Description'];
      $AgeMin = $data['AgeMin'];
      $AgeMax = $data['AgeMax'];
      $MaxCapacity = $data['MaxCapacity'];
      $Price = $data['Price'];
      $Schedule = json_encode($data['Schedule']);
      $IsActive = isset($data['IsActive']) ? (bool)$data['IsActive'] : true;
      $sql = "UPDATE Courses SET Name=?, Description=?, AgeMin=?, AgeMax=?, MaxCapacity=?, Price=?, Schedule=?, IsActive=? WHERE CourseId=?";
       $params = [$Name, $Description, $AgeMin, $AgeMax, $MaxCapacity, $Price, $Schedule, $IsActive, $courseId];
       $stmt = Database::getInstance()->query($sql, $params);
       if ($stmt && $stmt->rowCount() > 0) {
         return true;
      }
   }
   function CheckSeats()
   {
      if($this->CurrentEnrollment < $this->MaxCapacity)
        return true;
      return false;
    // Code to check if seats are available in course
   }

   function Isfull()
   {
      if($this->CurrentEnrollment >= $this->MaxCapacity)
        return true;
      return false;
    // Code to check if course is full
   }

   function IsEligible($childAge)
   {
      if($childAge >= $this->AgeMin && $childAge <= $this->AgeMax)
        return true;
      return false;
    // Code to check if child is eligible for course based on age
   }

   function GetEnrolledChildren()
   {
      Database::getInstance()->fetchAll("SELECT * FROM Enrollments WHERE CourseId = ?", [$this->courseId]);
    // Code to get list of children enrolled in course
   }

   function GetAssignedTeacher()
   {
      Database::getInstance()->fetchOne("SELECT * FROM Teachers WHERE TeacherId = ?", [$this->AssignedTeacherId]);
    // Code to get teacher assigned to course
   }

   function GetAttendanceSessions()
   {
      Database::getInstance()->fetchAll("SELECT * FROM AttendanceSessions WHERE CourseId = ?", [$this->courseId]);
      // Code to get attendance sessions for course
   }

   function AddToWaitlist($CourseId, $childID, $ParentID)
   {
      if($this->Isfull())
      {
        $WaitlistEntry = new Waitlist();
        // Code to add child to waitlist for course
        $WaitlistEntry->AssignWaitlist($CourseId, $childID, $ParentID);
      }
   }
  }
?>