<?php
 require_once 'User.php';
class Child
{
  Private $ChildId;
  private $ParentId;
  private $DateOfBirth;
  private $Gender;
  Private $allergies;
  Private $MedicalNotes;
  private $EmergencyContact;
  private $EnrollmentStatus;
  Private $PhotoPath;

  function GetAge()
  {
    // Code to calculate age from DateOfBirth
  }

  function GetEnrollmentCourses()
  {
    // Code to get courses child is enrolled in
  }

  function GetAttendanceRecords($fromDate, $toDate)
  {
    // Code to get attendance records for child
  }

  function GetAttendancePrecentage()
  {
    // Code to calculate attendance percentage
  }

  function GetMilestones()
  {
    // Code to get milestones achieved by child
  }

  function GetProgressReports()
  {
    // Code to get progress reports for child
  }

  function GetMedicalAlerts()
  {
    // Code to get medical alerts for child
  }

  function UpdateStatus($status)
  {
    // Code to update enrollment status
  }
}
?>