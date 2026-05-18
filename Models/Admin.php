<?php
 
 require_once 'User.php';
 require_once 'Course.php';
 require_once 'Settings.php';
 require_once 'Database.php';
 require_once 'Teacher.php';
 class Admin extends User
 {
  private $AdminId;
  Private $UserId;

  function ApproveApplication($data)
  {
    // Code to approve application
  }

  function RejectApplication($data)
  {
    // Code to reject application
  }

  function CreateCourse($data)
  {
    $Course = new Course();
    $Course->Create($data);
  }

  function EditCourse($courseId, $data)
  {
    $Course = new Course();
    $Course->Edit($courseId, $data);
    // Code to edit course
  }

  function EditSettings($data)
  {
    $Settings = new Settings();
    $Settings->Edit($data);
    // Code to edit settings
  }

  function ExportReport($type,$filters)
  {
    // Code to export report
  }

  function CreateTimeTable($data, $TeacherId)
  {
    $jSON = json_encode($data);
    $sql = "UPDATE Teachers SET AssignedTimetable=? WHERE TeacherId=?";
    $params = [$jSON, $TeacherId];
    $stmt = Database::getInstance()->query($sql, $params);
    if ($stmt && $stmt->rowCount() > 0) {
      return true;
    }
    // Code to create timetable
  }

  function EditTimeTable($TeacherID, $data)
  {
    $jSON = json_encode($data);
    $sql = "UPDATE Teachers SET AssignedTimetable=? WHERE TeacherId=?";
    $params = [$jSON, $TeacherID];
    $stmt = Database::getInstance()->query($sql, $params);
    if ($stmt && $stmt->rowCount() > 0) {
      return true;
    }
    // Code to edit timetable
  }

  function CreateARRrule($data)
  {
    // Code to create ARR rule
  }

  function AssignTeacher($courseId,$teacherId,$data)
  {
    $Course = new Course();
    $Course->Edit($courseId, $data);
     $sql = "UPDATE Teachers SET AssignedCourses=CONCAT(IFNULL(AssignedCourses, ''), ?) WHERE TeacherId=?";
     $params = [','.$courseId, $teacherId];
     $stmt = Database::getInstance()->query($sql, $params);
     if ($stmt && $stmt->rowCount() > 0) {
       return true;
    }
    // Code to assign teacher to course
  }

  function CreateEvent($data)
  {
    $Event = new Event();
    $Event->Publish($data);
    // Code to create event
  }

  function CreateStaffProfile($data)
  {
      $Name = $data['Name'];
      $Email = $data['Email'];
      $Password = password_hash($data['Password'], PASSWORD_DEFAULT);
      $Role = $data['Role'];
      $sql = "INSERT INTO Users (Name, Email, Password, Role) VALUES (?,?,?,?)";
      $params = [$Name, $Email, $Password, $Role];
      $stmt = Database::getInstance()->query($sql, $params);
      if ($stmt && $stmt->rowCount() > 0) {
        return true;
      }
    // Code to create staff profile
  }

  function ViewDashboard()
  {
    // Code to view admin dashboard
  }

  function ClearFlag($flagId,$reason)
  {
    // Code to clear flag
  }
 }
?>