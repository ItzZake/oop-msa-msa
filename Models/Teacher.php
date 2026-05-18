<?php

require_once 'User.php';
require_once 'Database.php';
require_once 'Message.php';
require_once 'ProgressReport.php';
require_once 'Milestones.php';

class Teacher extends User
{
  private $TeacherId;
  private $UserId;
  private $PhoneNumber;
  private $Qualifications;
  private $Experience;
  private $Specialization;
  private $AssignedCourses; //array of courseIds
  private $AssignedTimetable; //json
  
  public function CreateAssignment($courseId, $data)
  {
    $sql = "INSERT INTO Assignments (CourseId, Title, Description, DueDate, CreatedBy, CreatedAt) VALUES (?, ?, ?, ?, ?, ?)";
    $params = [
      $courseId,
      $data['Title'],
      $data['Description'],
      $data['DueDate'],
      $this->UserId,
      date('Y-m-d H:i:s')
    ];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  public function GradeSubmission($assignId, $childId, $grade)
  {
    $sql = "UPDATE Submissions SET Grade = ?, Status = 'graded', GradedAt = ? WHERE AssignmentId = ? AND ChildId = ?";
    $params = [$grade, date('Y-m-d H:i:s'), $assignId, $childId];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  public function SubmitProgressReport($childId, $data)
  {
    $report = new ProgressReport();
    return $report->SubmitReport(array_merge($data, ['ChildId' => $childId, 'TeacherId' => $this->UserId]));
  }

  public function MarkMilestone($childId, $milestoneId, $status)
  {
    $sql = "UPDATE Milestones SET Status = ?, UpdatedAt = ? WHERE MilestoneID = ? AND ChildID = ?";
    $params = [$status, date('Y-m-d H:i:s'), $milestoneId, $childId];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  public function ActivateARModel()
  {
    $sql = "UPDATE Teachers SET ARModelEnabled = 1 WHERE UserId = ?";
    $stmt = Database::getInstance()->query($sql, [$this->UserId]);
    return $stmt && $stmt->rowCount() > 0;
  }

  public function ViewTimeTable()
  {
    $sql = "SELECT * FROM CourseAssignments WHERE TeacherID = ? ORDER BY DayOfWeek, StartTime";
    return Database::getInstance()->fetchAll($sql, [$this->UserId]);
  }

  public function SendMessage($parentId, $content)
  {
    $msg = new Message();
    return $msg->Send(['SenderID' => $this->UserId, 'RecipientID' => $parentId, 'Content' => $content]);
  }

  public function FillOverdueForm($parentId, $childId, $data)
  {
    $sql = "INSERT INTO OverdueForms (TeacherID, ParentID, ChildID, Notes, CreatedAt) VALUES (?, ?, ?, ?, ?)";
    $params = [$this->UserId, $parentId, $childId, $data['Notes'] ?? '', date('Y-m-d H:i:s')];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  public function UploadARAssest($file)
  {
    if (!isset($file['tmp_name']) || !isset($file['name'])) {
      return false;
    }

    $uploadDir = '/uploads/ar_assets/';
    if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $uploadDir)) {
      mkdir($_SERVER['DOCUMENT_ROOT'] . $uploadDir, 0755, true);
    }

    $fileName = uniqid('arasset_' . $this->UserId . '_') . '_' . basename($file['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $filePath)) {
      $sql = "INSERT INTO ARAssets (CourseID, Name, FilePath, FileType, FileSizeKB, UploadedBy, UploadedAt, IsActive) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
      $params = [
        $this->AssignedCourses ? json_encode($this->AssignedCourses) : null,
        $file['name'],
        $filePath,
        $file['type'] ?? pathinfo($file['name'], PATHINFO_EXTENSION),
        isset($file['size']) ? round($file['size'] / 1024, 2) : 0,
        $this->UserId,
        date('Y-m-d H:i:s')
      ];
      $stmt = Database::getInstance()->query($sql, $params);
      return $stmt && $stmt->rowCount() > 0;
    }
    return false;
  }
}
?>