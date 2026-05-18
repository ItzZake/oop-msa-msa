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

    private $cancelReason;

    function Publish($Data)
    {
        $this->title = $Data['title'];
        $this->description = $Data['description'];
        $this->eventdate = $Data['eventdate'];
        $this->location = $Data['location'];
        $this->capacity = $Data['capacity'];
        $this->targettag = $Data['targettag'];
        $this->isfieldtrip = $Data['isfieldtrip'];
        $this->rsvpdeadline = $Data['rsvpdeadline'];
        $database = Database::getInstance();
        $sql = "INSERT INTO Events (AdminID, Title, Description, EventDate, Location, Capacity, TargetTag, IsFieldTrip, RSVPDeadline)
         VALUES (?,?,?,?,?,?,?,?,?)";
         $params = [$this->adminID, $this->title, $this->description, $this->eventdate, $this->location, $this->capacity, $this->targettag, $this->isfieldtrip, $this->rsvpdeadline];
         $stmt = $database->query($sql, $params);
         if ($stmt && $stmt->rowCount() > 0) {
           return true;
        }
        // Publish Event
    }
    function Cancel($Reason, $EventID)
    {
        $this->iscancelled = true;
         $sql = "UPDATE Events SET IsCancelled = ?, CancelReason = ? WHERE EventID = ?";
         $params = [$this->iscancelled, $Reason, $EventID];
         $stmt = Database::getInstance()->query($sql, $params);
         if ($stmt && $stmt->rowCount() > 0) {
           return true;
        }
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