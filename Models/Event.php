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
        $sql = "SELECT u.*, r.Response FROM RSVPs r
                INNER JOIN Users u ON r.ParentId = u.UserId
                WHERE r.EventId = ? AND r.Response IN ('attending', 'maybe')
                ORDER BY u.Name ASC";
        $params = [$this->eventID];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function GetRSVPCounts()
    {
        $sql = "SELECT 
                SUM(CASE WHEN Response = 'attending' THEN 1 ELSE 0 END) as attending,
                SUM(CASE WHEN Response = 'not_attending' THEN 1 ELSE 0 END) as not_attending,
                SUM(CASE WHEN Response = 'maybe' THEN 1 ELSE 0 END) as maybe,
                COUNT(*) as total
                FROM RSVPs WHERE EventId = ?";
        $params = [$this->eventID];
        $result = Database::getInstance()->fetchOne($sql, $params);
        
        return [
            'attending' => $result['attending'] ?? 0,
            'not_attending' => $result['not_attending'] ?? 0,
            'maybe' => $result['maybe'] ?? 0,
            'total' => $result['total'] ?? 0
        ];
    }
    function ExportAttendeeList($Format)
    {
        $attendees = $this->GetAttendeeList();
        
        if (empty($attendees)) {
            return ['status' => 'error', 'message' => 'No attendees to export'];
        }
        
        $filename = "attendees_" . $this->eventID . "_" . date('Y-m-d_H-i-s') . "." . strtolower($Format);
        $filepath = "/uploads/exports/" . $filename;
        
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads/exports')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/uploads/exports', 0755, true);
        }
        
        if ($Format === 'CSV') {
            $file = fopen($_SERVER['DOCUMENT_ROOT'] . $filepath, 'w');
            fputcsv($file, ['Name', 'Email', 'RSVP Status']);
            
            foreach ($attendees as $attendee) {
                fputcsv($file, [
                    $attendee['Name'],
                    $attendee['Email'],
                    $attendee['Response']
                ]);
            }
            
            fclose($file);
        }
        
        return ['status' => 'success', 'filepath' => $filepath];
    }
    function ScheduleReminders()
    {
        require_once 'NotificationManager.php';
        
        $attendees = $this->GetAttendeeList();
        if (empty($attendees)) {
            return ['status' => 'error', 'message' => 'No attendees to remind'];
        }
        
        $manager = NotificationManager::getInstance();
        $remindersSent = 0;
        
        foreach ($attendees as $attendee) {
            $manager->NotifyUser(
                $attendee['UserId'],
                "Reminder: Event '{$this->title}' is scheduled for {$this->eventdate} at {$this->location}"
            );
            $remindersSent++;
        }
        
        return ['status' => 'success', 'reminderCount' => $remindersSent];
    }
    function UploadGalleryPhoto($File)
    {
        if (!isset($File['tmp_name']) || !isset($File['name'])) {
            return ['status' => 'error', 'message' => 'Invalid file'];
        }
        
        $uploadDir = '/uploads/event_gallery/';
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $uploadDir)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . $uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $fileName = uniqid('event_' . $this->eventID . '_') . '_' . basename($File['name']);
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($File['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $filePath)) {
            // Store in database
            $sql = "INSERT INTO EventGallery (EventId, PhotoPath, UploadedAt) VALUES (?, ?, ?)";
            $params = [$this->eventID, $filePath, date('Y-m-d H:i:s')];
            $stmt = Database::getInstance()->query($sql, $params);
            
            if ($stmt && $stmt->rowCount() > 0) {
                return ['status' => 'success', 'photoPath' => $filePath];
            }
        }
        
        return ['status' => 'error', 'message' => 'Failed to upload photo'];
    }

 }
?>