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

    $sql = "UPDATE Milestones SET Status = ?, TeacherNote = ?, AchivedAt = ? WHERE MilestoneId = ?";
    $params = [$this->Status, $this->TeacherNote, $this->AchivedAt, $this->MilestoneId];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  function MarkinProgress($note)
  {
    $this->Status = "In Progress";
    $this->TeacherNote = $note;
    $sql = "UPDATE Milestones SET Status = ?, TeacherNote = ? WHERE MilestoneId = ?";
    $params = [$this->Status, $this->TeacherNote, $this->MilestoneId];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  function GetByDomain($domain)
  {
    $Database = Database::getInstance();
    $sql = "SELECT * FROM Milestones WHERE Domain = ?";
    $params = [$domain];
    return $Database->fetchAll($sql, $params);
    // Code to get milestones by domain
  }
  function GetMilestonesByChildId($ChildId)
  {
    $Database = Database::getInstance();
    $sql = "SELECT * FROM Milestones WHERE ChildId = ?";
    $params = [$ChildId];
    return $Database->fetchAll($sql, $params);
    // Code to get milestones for child
  }
 }
?>  