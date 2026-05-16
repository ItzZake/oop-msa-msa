<?php
 
 require_once 'User.php';

 class Admin extends User
 {
  private $AdminId;
  Private $UserId;

  function ApproveApplication($applicationId)
  {
    // Code to approve application
  }

  function RejectApplication($applicationId)
  {
    // Code to reject application
  }

  function CreateCourse($data)
  {
    // Code to create course
  }

  function EditCourse($courseId, $data)
  {
    // Code to edit course
  }

  function EditSettings($data)
  {
    // Code to edit settings
  }

  function ExportReport($type,$filters)
  {
    // Code to export report
  }

  function CreateTimeTable($data)
  {
    // Code to create timetable
  }

  function EditTimeTable($timetableId, $data)
  {
    // Code to edit timetable
  }

  function CreateARRrule($data)
  {
    // Code to create ARR rule
  }

  function AssignTeacher($courseId,$teacherId,$data)
  {
    // Code to assign teacher to course
  }

  function CreateEvent($details)
  {
    // Code to create event
  }

  function CreateStaffProfile($data)
  {
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