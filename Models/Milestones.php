<?php
require_once 'Database.php';
 class Milestones
 {
  private $MilestoneId;
  private $TeacherId;
  private $ChildId;
  private $Domain; //Physical, Cognitive, Social, Emotional
  private $MileStoneTitle;
  private $Status;
  private $AgeGroup;
  private $TeacherNote;
  private $AchivedAt;

  function MarkAchieved($note)
  {
    $this->Status = "Achieved";
    $this->TeacherNote = $note;
    $this->AchivedAt = date('Y-m-d H:i:s');

    $sql = "UPDATE milestone SET status = ?, teacherNote = ?, achievedAt = ? WHERE milestoneID = ?";
    $params = [$this->Status, $this->TeacherNote, $this->AchivedAt, $this->MilestoneId];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  function MarkinProgress($note)
  {
    $this->Status = "InProgress";
    $this->TeacherNote = $note;
    $sql = "UPDATE milestone SET status = ?, teacherNote = ? WHERE milestoneID = ?";
    $params = [$this->Status, $this->TeacherNote, $this->MilestoneId];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  function GetByDomain($domain)
  {
    $Database = Database::getInstance();
    $sql = "SELECT * FROM milestone WHERE domain = ?";
    $params = [$domain];
    return $Database->fetchAll($sql, $params);
    // Code to get milestones by domain
  }
  function GetMilestonesByChildId($ChildId)
  {
    $Database = Database::getInstance();
    $sql = "SELECT * FROM milestone WHERE childID = ?";
    $params = [$ChildId];
    return $Database->fetchAll($sql, $params);
    // Code to get milestones for child
  }
 }
?>  