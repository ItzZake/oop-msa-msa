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

  function MarkAchieved(note)
  {
    // Code to mark milestone as achieved
  }

  function MarkinProgress(note)
  {
    // Code to mark milestone as in progress
  }

  function GetByDomain(domain)
  {
    // Code to get milestones by domain
  }
 }
?>  