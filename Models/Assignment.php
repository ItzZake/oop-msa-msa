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
        
        $sql = "UPDATE assignment SET status = 'Published', createdAt = ? WHERE assignmentID = ?";
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
        $sql = "SELECT DISTINCT u.userID, u.email, c.childID 
                FROM child c
                INNER JOIN enrollment e ON c.childID = e.childID
                INNER JOIN parent p ON c.parentID = p.parentID
                INNER JOIN user u ON p.userID = u.userID
                WHERE e.courseID = ? AND e.status = 'Active'";
        $params = [$this->courseID];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function GetSubmissions()
    {
        $sql = "SELECT s.*, c.name as ChildName FROM submission s
                INNER JOIN child c ON s.childID = c.childID
                WHERE s.assignmentID = ?
                ORDER BY s.submittedAt DESC";
        $params = [$this->assignmentID];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function GetPendingSubmissions()
    {
        $sql = "SELECT s.*, c.name as ChildName FROM submission s
                INNER JOIN child c ON s.childID = c.childID
                WHERE s.assignmentID = ? AND s.status = 'Submitted'
                ORDER BY s.submittedAt ASC";
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
            $sql = "UPDATE assignment SET title=?, instructions=?, dueDate=?, 
                    wordwallEmbedCode=?, attachmentPath=?, status=?, targetTags=? 
                    WHERE assignmentID=?";
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
            $sql = "INSERT INTO assignment (teacherID, courseID, title, instructions, dueDate, 
                    wordwallEmbedCode, attachmentPath, status, createdAt, targetTags) 
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

    function Insert($teacherId, $title, $instructions, $dueDate, $targetTags = [], $attachmentPath = null)
    {
        $this->teacherID = $teacherId;
        $this->title = $title;
        $this->instructions = $instructions;
        $this->duedate = $dueDate;
        $this->targettags = is_array($targetTags) ? json_encode($targetTags) : $targetTags;
        $this->attachmentpath = $attachmentPath;
        $this->status = 'Published';

        $sql = "INSERT INTO assignment (teacherID, title, instructions, dueDate, wordwallEmbedCode, attachmentPath, status, createdAt, targetTags)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $this->teacherID,
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
            return Database::getInstance()->getConnection()->lastInsertId();
        }
        return false;
    }

    function SaveGrade($submissionId, $grade, $feedback)
    {
        $sql = "UPDATE submission SET grade = ?, feedback = ?, status = 'Graded', gradedAt = ? WHERE submissionID = ?";
        $params = [$grade, $feedback, date('Y-m-d H:i:s'), $submissionId];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function CreateRecipientRecord($assignmentId, $recipientIds)
    {
        if (!is_array($recipientIds) || empty($recipientIds)) {
            return false;
        }
        foreach ($recipientIds as $childId) {
            $sql = "INSERT INTO assignment_recipients (assignmentID, childID, assignedAt) VALUES (?, ?, ?)";
            $params = [$assignmentId, $childId, date('Y-m-d H:i:s')];
            Database::getInstance()->query($sql, $params);
        }
        return true;
    }

    function SaveEmbedCode($assignmentId, $embedCode)
    {
        $sql = "UPDATE assignment SET wordwallEmbedCode = ? WHERE assignmentID = ?";
        $stmt = Database::getInstance()->query($sql, [$embedCode, $assignmentId]);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetDueSoon()
    {
        $sql = "SELECT * FROM assignment WHERE dueDate BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY) AND status = 'Published'";
        return Database::getInstance()->fetchAll($sql);
    }

    function GetOverdueUnsubmitted()
    {
        $sql = "SELECT a.* FROM assignment a
                LEFT JOIN submission s ON a.assignmentID = s.assignmentID
                WHERE a.dueDate < CURDATE() AND a.status = 'Published'
                GROUP BY a.assignmentID
                HAVING COUNT(s.submissionID) = 0";
        return Database::getInstance()->fetchAll($sql);
    }

    function SetStatusLate($assignmentId)
    {
        $sql = "UPDATE assignment SET status = 'Late' WHERE assignmentID = ?";
        $stmt = Database::getInstance()->query($sql, [$assignmentId]);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetTeacherAssignments($teacherId)
    {
        $sql = "SELECT a.assignmentID, a.teacherID, a.courseID, a.title, a.instructions as description, 
                a.dueDate, a.wordwallembedcode, a.attachmentpath, a.status, a.createdAt,
                c.name as courseName,
                COUNT(DISTINCT s.submissionID) as submitted,
                COUNT(DISTINCT CASE WHEN s.grade IS NOT NULL THEN s.submissionID END) as graded,
                (SELECT COUNT(DISTINCT e.childID) FROM enrollment e WHERE e.courseID = a.courseID AND e.status = 'Active') as totalStudents
                FROM assignment a
                LEFT JOIN course c ON a.courseID = c.courseID
                LEFT JOIN submission s ON a.assignmentID = s.assignmentID
                WHERE a.teacherID = ?
                GROUP BY a.assignmentID
                ORDER BY a.dueDate DESC, a.createdAt DESC";
        
        return Database::getInstance()->fetchAll($sql, [$teacherId]);
    }

    function GetAssignmentById($assignmentId)
    {
        $sql = "SELECT * FROM assignment WHERE assignmentID = ?";
        $result = Database::getInstance()->fetchAll($sql, [$assignmentId]);
        return !empty($result) ? $result[0] : null;
    }

    function CreateAssignment(array $data): int|false
    {
        $sql = "INSERT INTO assignment (teacherID, courseID, title, instructions, dueDate, wordwallembedcode, createdAt, status) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Draft')";
        
        $params = [
            $data['teacherId'] ?? 0,
            $data['courseId'] ?? 0,
            $data['title'] ?? '',
            $data['instructions'] ?? '',
            $data['dueDate'] ?? '',
            $data['wordwallEmbedCode'] ?? '',
        ];

        try {
            $result = Database::getInstance()->query($sql, $params);
            
            if ($result && $result->rowCount() > 0) {
                // Return the last inserted ID
                return (int) Database::getInstance()->lastInsertId();
            }
            
            return false;
        } catch (Exception $e) {
            error_log('CreateAssignment error: ' . $e->getMessage());
            return false;
        }
    }

    function UpdateAssignment(int $assignmentId, array $data): bool
    {
        $sql = "UPDATE assignment SET 
                courseID = ?, 
                title = ?, 
                instructions = ?, 
                dueDate = ?, 
                wordwallembedcode = ? 
                WHERE assignmentID = ?";
        
        $params = [
            $data['courseId'] ?? 0,
            $data['title'] ?? '',
            $data['instructions'] ?? '',
            $data['dueDate'] ?? '',
            $data['wordwallEmbedCode'] ?? '',
            $assignmentId,
        ];

        try {
            $result = Database::getInstance()->query($sql, $params);
            
            if ($result && $result->rowCount() > 0) {
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log('UpdateAssignment error: ' . $e->getMessage());
            return false;
        }
    }
 }
?>