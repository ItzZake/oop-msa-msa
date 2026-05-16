<?php
 class Event
 {
    private $eventID;
    private $adminID;
    private $title;
    private $description;
    private $eventdate;
    private $location;
    private $capacity;
    private $targettag;
    private $isfieldtrip;
    private $iscancelled;
    private $rsvpdeadline;

    function Publish()
    {
        // Publish Event
    }

    function Cancel($Reason)
    {
        // Cancel Event with Reason
    }

    function GetAttendeeList()
    {
        // Get attendee list of said event
    }

    function GetRSVPCounts()
    {
        // Code 
    }
    function ExportAttendeeList($Format)
    {
        // Code
    }
    function ScheduleReminders()
    {
        // Code
    }
    function UploadGalleryPhoto($File)
    {
        // Code
    }

 }
?>