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
    $sql = "INSERT INTO assignment (courseID, teacherID, title, instructions, dueDate, createdAt) VALUES (?, ?, ?, ?, ?, ?)";
    $params = [
      $courseId,
      $this->getTeacherId(),
      $data['Title'],
      $data['Description'],
      $data['DueDate'],
      date('Y-m-d H:i:s')
    ];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  public function GradeSubmission($assignId, $childId, $grade)
  {
    $sql = "UPDATE submission SET grade = ?, status = 'Graded', gradedAt = ? WHERE assignmentID = ? AND childID = ?";
    $params = [$grade, date('Y-m-d H:i:s'), $assignId, $childId];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  public function setTeacherId($teacherId)
  {
    $this->TeacherId = $teacherId;
  }

  public function getTeacherId()
  {
    return $this->TeacherId;
  }

  public function SubmitProgressReport($childId, $data)
  {
    $report = new ProgressReport();
    return $report->SubmitReport(array_merge($data, ['ChildId' => $childId, 'TeacherId' => $this->getTeacherId()]));
  }

  public function MarkMilestone($childId, $milestoneId, $status)
  {
    $sql = "UPDATE milestone SET status = ?, achievedAt = ? WHERE milestoneID = ? AND childID = ?";
    $params = [$status, date('Y-m-d H:i:s'), $milestoneId, $childId];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  public function ActivateARModel()
  {
    $sql = "UPDATE teacher SET ARModelEnabled = 1 WHERE userID = ?";
    $stmt = Database::getInstance()->query($sql, [$this->getId()]);
    return $stmt && $stmt->rowCount() > 0;
  }

  public function ViewTimeTable()
  {
    $sql = "SELECT * FROM courseassignment WHERE teacherID = ? ORDER BY DayOfWeek, StartTime";
    return Database::getInstance()->fetchAll($sql, [$this->getTeacherId()]);
  }

  public function SendMessage($parentId, $content)
  {
    $msg = new Message();
    return $msg->Send(['SenderID' => $this->getId(), 'RecipientID' => $parentId, 'Content' => $content]);
  }

  public function FillOverdueForm($parentId, $childId, $data)
  {
    $sql = "INSERT INTO overdueforms (TeacherID, ParentID, ChildID, Notes, CreatedAt) VALUES (?, ?, ?, ?, ?)";
    $params = [$this->getTeacherId(), $parentId, $childId, $data['Notes'] ?? '', date('Y-m-d H:i:s')];
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

    $fileName = uniqid('arasset_' . $this->getId() . '_') . '_' . basename($file['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $filePath)) {
      $sql = "INSERT INTO ARAssets (CourseID, Name, FilePath, FileType, FileSizeKB, UploadedBy, UploadedAt, IsActive) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
      $params = [
        $this->AssignedCourses ? json_encode($this->AssignedCourses) : null,
        $file['name'],
        $filePath,
        $file['type'] ?? pathinfo($file['name'], PATHINFO_EXTENSION),
        isset($file['size']) ? round($file['size'] / 1024, 2) : 0,
        $this->getId(),
        date('Y-m-d H:i:s')
      ];
      $stmt = Database::getInstance()->query($sql, $params);
      return $stmt && $stmt->rowCount() > 0;
    }
    return false;
  }
}
?>