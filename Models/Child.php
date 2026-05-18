<?php
require_once 'Database.php';
require_once 'User.php';
require_once 'Milestones.php';
class Child
{
  private $childID;
  private $parentID;
  private $name;
  private $dateOfBirth;
  private $gender;
  private $allergies;
  private $medicalNotes;
  private $emergencyContact;
  private $enrollmentStatus;
  private $photoPath;

  function __construct($childID, $parentID, $dateOfBirth, $gender, $allergies = null, $medicalNotes = null, $emergencyContact = null, $enrollmentStatus = 'Pending', $photoPath = null, $name = null)
  {
    $this->childID = $childID;
    $this->parentID = $parentID;
    $this->name = $name;
    $this->dateOfBirth = $dateOfBirth;
    $this->gender = $gender;
    $this->allergies = $allergies;
    $this->medicalNotes = $medicalNotes;
    $this->emergencyContact = $emergencyContact;
    $this->enrollmentStatus = $enrollmentStatus;
    $this->photoPath = $photoPath;
  }

  function GetAge()
  {
    $age = date_diff(date_create($this->dateOfBirth), date_create('today'))->y;
    return $age;
  }
  function GetParentID()
  {
      return $this->parentID;
  }
  function GetChildByID($childID)
  {
    $sql = "SELECT * FROM Child WHERE childID = ?";
    $params = [$childID];
    $stmt = Database::getInstance()->query($sql, $params);
    if ($stmt && $stmt->rowCount() > 0) {
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      return new self(
        $data['childID'],
        $data['parentID'],
        $data['dateOfBirth'],
        $data['gender'],
        $data['allergies'],
        $data['medicalNotes'],
        $data['emergencyContact'],
        $data['enrollmentStatus'],
        $data['photoPath'],
        $data['name']
      );
    }
    return null;
  }
  function GetEnrollmentCourses()
  {
    $enrollment = new Enrollment();
    return $enrollment->GetEnrollmentsByChildId($this->childID);
  }

  function GetAttendanceRecords($fromDate, $toDate)
  {
    $attendance = new Attendance();
    return $attendance->GetAttendanceByChildId($this->childID, $fromDate, $toDate);
  }

  function GetAttendancePercentage()
  {
    $attendance = new Attendance();
    $records = $attendance->GetAttendanceByChildId($this->childID, '2024-01-01', '2024-12-31');
    $totalSessions = count($records);
    $presentCount = 0;
    foreach ($records as $record) {
      if (($record['status'] ?? $record['Status']) === 'Present') {
        $presentCount++;
      }
    }
    if ($totalSessions > 0) {
      return ($presentCount / $totalSessions) * 100;
    }
    return 0;
  }

  function GetMilestones()
  {
    $milestones = new Milestones();
    return $milestones->GetMilestonesByChildId($this->childID);
  }

  function GetProgressReports()
  {
    $progressReport = new ProgressReport();
    return $progressReport->GetProgressReportsByChildId($this->childID);
  }

  function GetMedicalAlerts()
  {
    $alerts = [];
    if (!empty($this->allergies)) {
      $alerts[] = "Allergies: " . $this->allergies;
    }
    if (!empty($this->medicalNotes)) {
      $alerts[] = "Medical Notes: " . $this->medicalNotes;
    }
    if (!empty($this->emergencyContact)) {
      $alerts[] = "Emergency Contact: " . $this->emergencyContact;
    }
    return $alerts;
  }

  function UpdateStatus($status)
  {
    $this->enrollmentStatus = $status;
    $sql = "UPDATE Child SET enrollmentStatus = ? WHERE childID = ?";
    $params = [$this->enrollmentStatus, $this->childID];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }
  function GetChildrenByParentId($parentID)
  {
	$sql = "SELECT * FROM Child WHERE parentID = ?";
	$params = [$parentID];
	$stmt = Database::getInstance()->query($sql, $params);
	$children = [];
	if ($stmt && $stmt->rowCount() > 0) {
	  while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$children[] = new self(
		  $data['childID'],
		  $data['parentID'],
		  $data['dateOfBirth'],
		  $data['gender'],
		  $data['allergies'],
		  $data['medicalNotes'],
		  $data['emergencyContact'],
		  $data['enrollmentStatus'],
		  $data['photoPath'],
		  $data['name']
		);
	  }
	}
}
}
?>