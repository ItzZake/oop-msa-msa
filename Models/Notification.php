<?php

require_once 'Database.php';
require_once 'Subscription.php';
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
            $this->notificationID = Database::getInstance()->getConnection()->lastInsertId();
            return true;
        }
        return false;
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