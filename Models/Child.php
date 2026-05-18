<?php
 require_once 'User.php';
class Child
{
  private $ChildId;
  private $ParentId;
  private $DateOfBirth;
  private $Gender;
  private $allergies;
  private $MedicalNotes;
  private $EmergencyContact;
  private $EnrollmentStatus;
  private $PhotoPath;

  function __construct($ChildId, $ParentId, $DateOfBirth, $Gender, $allergies = null, $MedicalNotes = null, $EmergencyContact = null, $EnrollmentStatus = 'pending', $PhotoPath = null)
  {
    $this->ChildId = $ChildId;
    $this->ParentId = $ParentId;
    $this->DateOfBirth = $DateOfBirth;
    $this->Gender = $Gender;
    $this->allergies = $allergies;
    $this->MedicalNotes = $MedicalNotes;
    $this->EmergencyContact = $EmergencyContact;
    $this->EnrollmentStatus = $EnrollmentStatus;
    $this->PhotoPath = $PhotoPath;
  }
  function GetAge()
  {
    // Code to calculate age from DateOfBirth
    $Age = date_diff(date_create($this->DateOfBirth), date_create('today'))->y;
    return $Age;
  }

  function GetEnrollmentCourses()
  {
    $Enrollment = new Enrollment();
    return $Enrollment->GetEnrollmentsByChildId($this->ChildId);
    // Code to get courses child is enrolled in
  }

  function GetAttendanceRecords($fromDate, $toDate)
  {
    $Attendance = new Attendance();
    return $Attendance->GetAttendanceByChildId($this->ChildId, $fromDate, $toDate);
    // Code to get attendance records for child
  }

  function GetAttendancePrecentage()
  {
      $Attendance = new Attendance();
      $records = $Attendance->GetAttendanceByChildId($this->ChildId, '2024-01-01', '2024-12-31');
      $totalSessions = count($records);
      $presentCount = 0;
      foreach ($records as $record) {
        if ($record['Status'] == 'Present') {
          $presentCount++;
        }
      }
      if ($totalSessions > 0) {
        return ($presentCount / $totalSessions) * 100;
      }
      return 0;
    // Code to calculate attendance percentage
  }

  function GetMilestones()
  {
    $Milestone = new Milestone();
    return $Milestone->GetMilestonesByChildId($this->ChildId);
  }

  function GetProgressReports()
  {
      $ProgressReport = new ProgressReport();
      return $ProgressReport->GetProgressReportsByChildId($this->ChildId);
    // Code to get progress reports for child
  }

  function GetMedicalAlerts()
  {
    $alerts = [];
    if (!empty($this->allergies)) {
      $alerts[] = "Allergies: " . $this->allergies;
    }
    if (!empty($this->MedicalNotes)) {
      $alerts[] = "Medical Notes: " . $this->MedicalNotes;
    }
    if (!empty($this->EmergencyContact)) {
      $alerts[] = "Emergency Contact: " . $this->EmergencyContact;
    }
    return $alerts;
    // Code to get medical alerts for child
  }

  function UpdateStatus($status)
  {
    $this->EnrollmentStatus = $status;
    // Code to update enrollment status in database
    $sql = "UPDATE Children SET EnrollmentStatus = ? WHERE ChildId = ?";
    $params = [$this->EnrollmentStatus, $this->ChildId];
    $stmt = Database::getInstance()->query($sql, $params);
    if ($stmt && $stmt->rowCount() > 0) {
      return true;
    }
    return false;
  }
}
?>