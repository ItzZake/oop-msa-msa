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
      $sql = "INSERT INTO course (name, description, ageMin, ageMax, maxCapacity, price, schedule, isActive)
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
      $sql = "UPDATE course SET name=?, description=?, ageMin=?, ageMax=?, maxCapacity=?, price=?, schedule=?, isActive=? WHERE courseID=?";
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
	function GetCoursesByAge($age)
  	{
      $sql = "SELECT * FROM course WHERE ageMin <= ? AND ageMax >= ? AND isActive = 1";
      $params = [$age, $age];
      return Database::getInstance()->fetchAll($sql, $params);
    	// Code to retrieve courses suitable for a specific age
  	}
  function GetCourseById($courseId)
  {
      $sql = "SELECT * FROM course WHERE courseID = ? AND isActive = 1";
      $params = [$courseId];
      return Database::getInstance()->fetchOne($sql, $params);
    // Code to retrieve course details by ID
  }
   function GetAllActiveCourses()
  {
      $sql = "SELECT * FROM course WHERE isActive = 1";
      return Database::getInstance()->fetchAll($sql);
      // Code to retrieve all active courses
  }
	function GetScheduleforCourses($courseId)
	{
	  $sql = "SELECT schedule FROM course WHERE IsActive = 1 AND courseID = ?";
	  $params = [$courseId];
	  $result = Database::getInstance()->fetchAll($sql, $params);
	  return json_decode($result['schedule'], true);
	// Code to retrieve schedule for a specific courses
	}
   function GetAssignedTeacherId($courseId)
   {
	  $sql = "SELECT AssignedTeacherId FROM course WHERE courseID = ?";
	  $params = [$courseId];
	  $result = Database::getInstance()->fetchOne($sql, $params);
	  return $result ? $result['AssignedTeacherId'] : null;
	// Code to retrieve assigned teacher ID for a specific course
   }
	}
   function GetCourseDetails($courseId)
   {
      $sql = "SELECT * FROM course WHERE courseID = ?";
      $params = [$courseId];
      return Database::getInstance()->fetchOne($sql, $params);
    // Code to retrieve details of a specific course
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
      $sql = "SELECT c.* FROM child c 
              INNER JOIN enrollment e ON c.childID = e.childID 
              WHERE e.courseID = ? AND e.status = 'Active'";
      $params = [$this->courseId];
      return Database::getInstance()->fetchAll($sql, $params);
   }

   function GetAssignedTeacher()
   {
      if (!$this->AssignedTeacherId) {
         return null;
      }
      $sql = "SELECT * FROM teacher WHERE teacherID = ?";
      $params = [$this->AssignedTeacherId];
      return Database::getInstance()->fetchOne($sql, $params);
   }

   function GetAttendanceSessions()
   {
      $sql = "SELECT * FROM attendance WHERE courseID = ? ORDER BY sessionDate DESC";
      $params = [$this->courseId];
      return Database::getInstance()->fetchAll($sql, $params);
   }

   function AddToWaitlist($CourseId, $childID, $ParentID)
   {
      if ($this->Isfull()) {
        $WaitlistEntry = new Waitlist();
        return $WaitlistEntry->AssignWaitlist($CourseId, $childID, $ParentID);
      }
      return false;
   }
  }
?>