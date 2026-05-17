<?php

require_once 'User.php';

class Teacher extends User
{
  private $TeacherId;
  private $UserId;
  private $PhoneNumber;
  private $Qualifications;
  private $Experience;
  private $Specialization;
  
  public function CreateAssignment($courseId, $data)
  {
    // Code to create assignment for course
  }

  public function GradeSubmission($assignId, $childId, $grade)
  {
    // Code to grade assignment submission
  }

  public function SubmitProgressReport($childId, $data)
  {
    // Code to submit progress report for child
  }

  public function MarkMilestone($childId, $milestoneId, $status)
  {
    // Code to mark milestone achieved for child
  }

  public function ActivateARModel()
  {
    // Code to activate AR model for classroom
  }

  public function ViewTimeTable()
  {
    // Code to view timetable for teacher
  }

  public function SendMessage($parentId, $content)
  {
    // Code to send message to parent
  }

  public function FillOverdueForm($parentId, $childId, $data)
  {
    // Code to fill overdue form for child
  }

  public function UploadARAssest($file)
  {
    // Code to upload AR asset for classroom
  }
}
?>