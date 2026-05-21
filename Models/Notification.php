<?php

require_once 'Database.php';
require_once 'Subscription.php';
require_once 'NotificationDispatcher.php';
 class Notification
 {
    private $notificationID;
    private $userID;
    private $type;
    private $message;
    private $channel;
    private $isread;
    private $sentat;
    private $readat;
    private $relatedID;
    private $relatedtype;

    function MarkRead()
    {
        // Code
        $this->isread = true;
        $this->readat = date("Y-m-d H:i");
        
        // Update in database
        $sql = "UPDATE notification SET isRead = 1, readAt = ? WHERE notificationID = ?";
        $params = [$this->readat, $this->notificationID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function Send($userID, $type, $message, $channel)
    {
        // Code
        $this->sentat = date("Y-m-d H:i");
        $this->userID = $userID;
        $this->type = $type;
        $this->message = $message;
        $this->channel = $channel;
        $this->isread = false;
        
        // Store in database
        $sql = "INSERT INTO notification (userID, type, title, message, channel, sentAt, isRead) 
                VALUES (?, ?, ?, ?, ?, ?, 0)";
        $params = [$this->userID, $this->type, 'Notification', $this->message, $this->channel, $this->sentat];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            $conn = Database::getInstance()->getConnection();
            if (is_object($conn) && method_exists($conn, 'lastInsertId')) {
                $this->notificationID = $conn->lastInsertId();
            }
            return true;
        }
        return false;
    }

    function SendBroadcast($targetTag, $message)
    {
        $dispatcher = new NotificationDispatcher();
        return $dispatcher->BroadCast($targetTag, $message) > 0;
    }

    function SendEmailReminder($userId, $message)
    {
        $email = null;
        $sql = "SELECT email FROM Users WHERE userID = ? LIMIT 1";
        $result = Database::getInstance()->fetchOne($sql, [$userId]);
        if (!empty($result['email'])) {
            $email = $result['email'];
        }

        if (empty($email)) {
            $sql = "SELECT email FROM User WHERE userID = ? LIMIT 1";
            $result = Database::getInstance()->fetchOne($sql, [$userId]);
            $email = $result['email'] ?? null;
        }

        if (empty($email)) {
            $sql = "SELECT u.email FROM Parent p INNER JOIN Users u ON p.userID = u.userID WHERE p.parentID = ? LIMIT 1";
            $result = Database::getInstance()->fetchOne($sql, [$userId]);
            $email = $result['email'] ?? null;
        }

        if (empty($email)) {
            $sql = "SELECT u.email FROM Teacher t INNER JOIN Users u ON t.userID = u.userID WHERE t.teacherID = ? LIMIT 1";
            $result = Database::getInstance()->fetchOne($sql, [$userId]);
            $email = $result['email'] ?? null;
        }

        if (empty($email)) {
            return false;
        }

        $dispatcher = new NotificationDispatcher();
        return $dispatcher->SendEmail($email, 'Reminder', $message);
    }

    function SendInAppNotification($userId, $title, $message)
    {
        $dispatcher = new NotificationDispatcher();
        return $dispatcher->SendInApp($userId, $message);
    }

    function NotifyAdmin($message)
    {
        $sql = "SELECT userID FROM Users WHERE role = 'admin'";
        $admins = Database::getInstance()->fetchAll($sql, []);
        if (empty($admins)) {
            $sql = "SELECT userID FROM User WHERE role = 'admin'";
            $admins = Database::getInstance()->fetchAll($sql, []);
        }

        if (empty($admins)) {
            return false;
        }

        $dispatcher = new NotificationDispatcher();
        $sent = false;
        foreach ($admins as $admin) {
            if ($dispatcher->Send($admin['userID'], $message)) {
                $sent = true;
            }
        }
        return $sent;
    }

    function SendAssignmentNotification($assignmentId, $tags)
    {
        require_once 'NotificationManager.php';

        if (empty($assignmentId) || empty($tags) || !is_array($tags)) {
            return false;
        }

        $tagPlaceholders = implode(',', array_fill(0, count($tags), '?'));
        $sql = "SELECT DISTINCT ct.childID, c.parentID
                FROM ChildTag ct
                INNER JOIN Child c ON ct.childID = c.childID
                WHERE ct.tagID IN ($tagPlaceholders)";
        $children = Database::getInstance()->fetchAll($sql, $tags);

        if (empty($children)) {
            return false;
        }

        $manager = NotificationManager::getInstance();
        $sent = false;
        foreach ($children as $child) {
            if (empty($child['parentID'])) {
                continue;
            }
            if ($manager->NotifyUser($child['parentID'], "A new assignment has been posted.", 'assignment')) {
                $sent = true;
            }
        }
        return $sent;
    }

    function SendDueReminder($assignmentId, $childId = null)
    {
        if (!empty($assignmentId) && !empty($childId)) {
            $sql = "SELECT p.userID FROM Child c
                    INNER JOIN Parent p ON c.parentID = p.parentID
                    WHERE c.childID = ? LIMIT 1";
            $parent = Database::getInstance()->fetchOne($sql, [$childId]);
            if (empty($parent['userID'])) {
                return false;
            }
            return $this->Send($parent['userID'], 'due_reminder', "Assignment {$assignmentId} is due soon.", 'system');
        }

        if (is_numeric($assignmentId)) {
            return $this->Send($assignmentId, 'due_reminder', 'A subscription or assignment is due soon.', 'system');
        }

        return false;
    }

    function SendAbsenceAlert($parentId, $childId, $streakCount = null)
    {
        if (empty($parentId) || empty($childId)) {
            return false;
        }

        $message = "Your child (ID: {$childId}) is absent.";
        if (!empty($streakCount)) {
            $message .= " This is absence streak #{$streakCount}.";
        }

        return $this->Send($parentId, 'absence_alert', $message, 'system');
    }

    function SendOverdueNotification($parentId)
    {
        if (empty($parentId)) {
            return false;
        }
        return $this->Send($parentId, 'overdue_payment', 'Your payment or subscription is overdue.', 'system');
    }

    function SendSubmissionAlert($assignmentId, $childId)
    {
        if (empty($assignmentId) || empty($childId)) {
            return false;
        }

        $sql = "SELECT p.userID FROM Child c
                INNER JOIN Parent p ON c.parentID = p.parentID
                WHERE c.childID = ? LIMIT 1";
        $parent = Database::getInstance()->fetchOne($sql, [$childId]);

        if (empty($parent['userID'])) {
            return false;
        }

        return $this->Send($parent['userID'], 'submission_alert', "New submission received for assignment {$assignmentId}.", 'system');
    }

    function GetUnread($userID)
    {
        // Code
        $sql = "SELECT * FROM notification WHERE userID = ? AND isRead = 0 ORDER BY sentAt DESC";
        $params = [$userID];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function GetUnreadCount($userID)
    {
        // Code
        $sql = "SELECT COUNT(*) as count FROM notification WHERE userID = ? AND isRead = 0";
        $params = [$userID];
        $result = Database::getInstance()->fetchOne($sql, $params);
        return $result ? (int)$result['count'] : 0;
    }
 }
?>