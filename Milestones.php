<?php
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
    // Code to mark milestone as achieved
  }

  function MarkinProgress($note)
  {
    $this->Status = "In Progress";
    $this->TeacherNote = $note;
    // Code to mark milestone as in progress
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