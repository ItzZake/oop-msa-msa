<?php
 class Submission
 {
    private $submissionID;
    private $assignmentID;
    private $childID;
    private $parentID;
    private $type;
    private $content;
    private $photopath;
    private $submittedat;
    private $status;
    private $grade;
    private $feedback;
    private $gradedat;
    private $gradedby;

    function Submit($type, $content)
    {
        // Code
    }

    function Grade($grade, $feedback, $teacherID)
    {
        // Code
    }

    function MarkLate()
    {
        // Code
    }

    function IsLate()
    {
        // Code 
    }
   
 }
?>