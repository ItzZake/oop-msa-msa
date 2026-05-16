<?php

require_once 'User.php';

class Teacher extends User
{
  private $TeacherId;
  private $UserId;
  private $PhoneNumber;
  private $Qualifications;

  function MarkAttendance(sessionId, childId, status)
  {
    // Code to mark attendance for child in session
  }

  function CreateAssignment(courseId, data)
  {
    // Code to create assignment for course
  }

  function GradeSubmission(assignId, childId, grade)
  {
    // Code to grade assignment submission
  }

  function SubmitProgressReport(childId, data)
  {
    // Code to submit progress report for child
  }

  function MarkMilestone(childId, milestoneId, status)
  {
    // Code to mark milestone achieved for child
  }

  function ActivateARModel()
  {
    // Code to activate AR model for classroom
  }

  function ViewTimeTable()
  {
    // Code to view timetable for teacher
  }

  function SendMessage(parentId,content)
  {
    // Code to send message to parent
  }

  function FillOverdueForm(parentId,childId,data)
  {
    // Code to fill overdue form for child
  }

  function UploadARAssest(file)
  {
    // Code to upload AR asset for classroom
  }
}
?>