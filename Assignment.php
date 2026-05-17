<?php
 class Assignment
 {
    private $assignmentID;
    private $teacherID;
    private $courseID;
    private $title;
    private $instructions;
    private $duedate;
    private $wordwallembedcode;
    private $attachmentpath;
    private $status;
    private $createdat;
    private $targettags;

    function Publish()
    {
        // Code
    }
    function GetDueDate()
    {
        return $this->duedate;
    }
    function SetDueDate($duedate)
    {
        $this->duedate = $duedate;
        // Code to update due date in database
    }
    function ResolveRecipients()
    {
        // Code
    }

    function GetSubmissions()
    {
        // Code
    }

    function GetPendingSubmissions()
    {
        // Code 
    }

    function FetchWordwallEmbed($url)
    {
        // Code
    }

    function ValidateEmbedCode($code)
    {
        // Code 
    }
    function ScheduleReminders()
    {
        // Code
    }

    function GetCompletionRate()
    {
        // Code
    }

    function SaveAssignment()
    {
        // Code 
    }
 }
?>