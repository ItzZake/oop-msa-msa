<?php
require_once 'Database.php';
class Event
{
    private $eventID;
    private $adminID;
    private $title;
    private $description;
    private $eventDate;
    private $location;
    private $capacity;
    private $targetTag;
    private $isFieldTrip;
    private $isCancelled;
    private $rsvpDeadline;
    private $cancelReason;

    function Publish($Data)
    {
        $this->title = $Data['title'];
        $this->description = $Data['description'];
        $this->eventDate = $Data['eventdate'];
        $this->location = $Data['location'];
        $this->capacity = $Data['capacity'];
        $this->targetTag = $Data['targettag'];
        $this->isFieldTrip = $Data['isfieldtrip'];
        $this->rsvpDeadline = $Data['rsvpdeadline'];

        $sql = "INSERT INTO Event (adminID, title, description, eventDate, location, capacity, targetTag, isFieldTrip, rsvpDeadline)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$this->adminID, $this->title, $this->description, $this->eventDate, $this->location, $this->capacity, $this->targetTag, $this->isFieldTrip, $this->rsvpDeadline];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function Cancel($Reason, $EventID)
    {
        $this->isCancelled = 1;
        $sql = "UPDATE Event SET isCancelled = ?, cancelReason = ? WHERE eventID = ?";
        $params = [$this->isCancelled, $Reason, $EventID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetAttendeeList()
    {
        $sql = "SELECT u.*, r.response FROM EventRSVP r
                INNER JOIN Parent p ON r.parentID = p.parentID
                INNER JOIN User u ON p.userID = u.userID
                WHERE r.eventID = ? AND r.response IN ('Confirmed', 'Pending')
                ORDER BY u.firstname ASC";
        $params = [$this->eventID];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function GetRSVPCounts()
    {
        $sql = "SELECT 
                SUM(CASE WHEN response = 'Confirmed' THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN response = 'Declined' THEN 1 ELSE 0 END) as declined,
                SUM(CASE WHEN response = 'Pending' THEN 1 ELSE 0 END) as pending,
                COUNT(*) as total
                FROM EventRSVP WHERE eventID = ?";
        $params = [$this->eventID];
        $result = Database::getInstance()->fetchOne($sql, $params);

        return [
            'confirmed' => $result['confirmed'] ?? 0,
            'declined' => $result['declined'] ?? 0,
            'pending' => $result['pending'] ?? 0,
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
                $name = trim(($attendee['firstname'] ?? '') . ' ' . ($attendee['Lastname'] ?? ''));
                fputcsv($file, [
                    $name,
                    $attendee['email'] ?? '',
                    $attendee['response'] ?? ''
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
            $userId = $attendee['userID'] ?? $attendee['UserID'];
            if (!$userId) {
                continue;
            }

            $manager->NotifyUser(
                $userId,
                "Reminder: Event '{$this->title}' is scheduled for {$this->eventDate} at {$this->location}"
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

        $fileName = uniqid('event_' . $this->eventID . '_') . '_' . basename($File['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($File['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $filePath)) {
            $sql = "INSERT INTO EventGallery (eventID, photoPath, uploadedAt) VALUES (?, ?, ?)";
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