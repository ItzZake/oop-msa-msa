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
        $this->status = 'published';
        
        $sql = "UPDATE Assignments SET Status = 'published', CreatedAt = ? WHERE AssignmentID = ?";
        $params = [date('Y-m-d H:i:s'), $this->assignmentID];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            // Resolve recipients and send notifications
            $recipients = $this->ResolveRecipients();
            if (!empty($recipients)) {
                require_once 'NotificationManager.php';
                $manager = NotificationManager::getInstance();
                foreach ($recipients as $recipient) {
                    $manager->NotifyUser(
                        $recipient['UserId'],
                        "New assignment: {$this->title}. Due: " . $this->duedate
                    );
                }
            }
            return ['status' => 'success', 'message' => 'Assignment published successfully'];
        }
        
        return ['status' => 'error', 'message' => 'Failed to publish assignment'];
    }
    function GetDueDate()
    {
        return $this->duedate;
    }
    function SetDueDate($duedate)
    {
        $this->duedate = $duedate;
        $sql = "UPDATE Assignments SET DueDate = ? WHERE AssignmentID = ?";
        $params = [$this->duedate, $this->assignmentID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }
    function ResolveRecipients()
    {
        // Get children enrolled in the course
        $sql = "SELECT DISTINCT u.UserId, u.Email, c.ChildId 
                FROM Children c
                INNER JOIN Enrollments e ON c.ChildId = e.ChildId
                INNER JOIN Parents p ON c.ParentId = p.ParentId
                INNER JOIN Users u ON p.UserId = u.UserId
                WHERE e.CourseId = ? AND e.Status = 'Active'";
        $params = [$this->courseID];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function GetSubmissions()
    {
        $sql = "SELECT s.*, c.Name as ChildName FROM Submissions s
                INNER JOIN Children c ON s.ChildId = c.ChildId
                WHERE s.AssignmentID = ?
                ORDER BY s.SubmittedAt DESC";
        $params = [$this->assignmentID];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function GetPendingSubmissions()
    {
        $sql = "SELECT s.*, c.Name as ChildName FROM Submissions s
                INNER JOIN Children c ON s.ChildId = c.ChildId
                WHERE s.AssignmentID = ? AND s.Status = 'pending'
                ORDER BY s.SubmittedAt ASC";
        $params = [$this->assignmentID];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function FetchWordwallEmbed($url)
    {
        // Extract embed code from Wordwall URL
        // Wordwall URLs typically look like: https://wordwall.net/resource/xxxxx
        if (strpos($url, 'wordwall.net') === false) {
            return ['status' => 'error', 'message' => 'Invalid Wordwall URL'];
        }
        
        // Extract ID from URL
        preg_match('/wordwall\.net\/resource\/([a-z0-9]+)/i', $url, $matches);
        
        if (!isset($matches[1])) {
            return ['status' => 'error', 'message' => 'Could not extract Wordwall ID'];
        }
        
        $embedCode = $matches[1];
        $embedUrl = "https://wordwall.net/resource/" . $embedCode;
        
        return [
            'status' => 'success',
            'embedCode' => $embedCode,
            'embedUrl' => $embedUrl,
            'iframeCode' => '<iframe src="' . $embedUrl . '/embed" frameborder="0"></iframe>'
        ];
    }

    function ValidateEmbedCode($code)
    {
        // Check if it's a valid Wordwall ID format (alphanumeric)
        if (!preg_match('/^[a-z0-9]+$/i', $code)) {
            return false;
        }
        
        // Validate length (Wordwall IDs are typically 8-16 characters)
        if (strlen($code) < 8 || strlen($code) > 20) {
            return false;
        }
        
        return true;
    }
    function ScheduleReminders()
    {
        require_once 'NotificationManager.php';
        
        $recipients = $this->ResolveRecipients();
        if (empty($recipients)) {
            return ['status' => 'error', 'message' => 'No recipients found'];
        }
        
        $manager = NotificationManager::getInstance();
        $remindersSent = 0;
        
        // Send reminder to each recipient
        foreach ($recipients as $recipient) {
            $manager->NotifyUser(
                $recipient['UserId'],
                "Reminder: Assignment '{$this->title}' is due on {$this->duedate}"
            );
            $remindersSent++;
        }
        
        return [
            'status' => 'success',
            'message' => "Reminders scheduled for {$remindersSent} recipients",
            'reminderCount' => $remindersSent
        ];
    }

    function GetCompletionRate()
    {
        // Get enrolled children count
        $enrolledSql = "SELECT COUNT(DISTINCT e.ChildId) as count 
                       FROM Enrollments e 
                       WHERE e.CourseId = ? AND e.Status = 'Active'";
        $enrolled = Database::getInstance()->fetchOne($enrolledSql, [$this->courseID]);
        $enrolledCount = $enrolled['count'] ?? 0;
        
        if ($enrolledCount == 0) {
            return 0;
        }
        
        // Get submissions count
        $submissionsSql = "SELECT COUNT(DISTINCT ChildId) as count 
                          FROM Submissions 
                          WHERE AssignmentID = ? AND Status IN ('submitted', 'graded')";
        $submissions = Database::getInstance()->fetchOne($submissionsSql, [$this->assignmentID]);
        $submissionCount = $submissions['count'] ?? 0;
        
        return ($submissionCount / $enrolledCount) * 100;
    }

    function SaveAssignment()
    {
        if ($this->assignmentID) {
            // Update existing assignment
            $sql = "UPDATE Assignments SET Title=?, Instructions=?, DueDate=?, 
                    WordwallEmbedCode=?, AttachmentPath=?, Status=?, TargetTags=? 
                    WHERE AssignmentID=?";
            $params = [
                $this->title,
                $this->instructions,
                $this->duedate,
                $this->wordwallembedcode,
                $this->attachmentpath,
                $this->status,
                $this->targettags,
                $this->assignmentID
            ];
            $stmt = Database::getInstance()->query($sql, $params);
            return $stmt && $stmt->rowCount() > 0;
        } else {
            // Insert new assignment
            $sql = "INSERT INTO Assignments (TeacherId, CourseId, Title, Instructions, DueDate, 
                    WordwallEmbedCode, AttachmentPath, Status, CreatedAt, TargetTags) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                $this->teacherID,
                $this->courseID,
                $this->title,
                $this->instructions,
                $this->duedate,
                $this->wordwallembedcode,
                $this->attachmentpath,
                $this->status,
                date('Y-m-d H:i:s'),
                $this->targettags
            ];
            $stmt = Database::getInstance()->query($sql, $params);
            if ($stmt && $stmt->rowCount() > 0) {
                $this->assignmentID = Database::getInstance()->getConnection()->lastInsertId();
                return true;
            }
        }
        return false;
    }
 }
?>