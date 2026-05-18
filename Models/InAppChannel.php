<?php

require_once 'INotificationChannel.php';

class InAppChannel implements INotificationChannel
{
    public function Send($recipient, $message)
    {
        // Implementation for in-app notifications
        // Typically stores in database for display in the app
        
        $sql = "INSERT INTO InAppNotifications (UserID, Message, IsRead, CreatedAt) 
                VALUES (?, ?, 0, ?)";
        $params = [$recipient, $message, date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        
        return $stmt && $stmt->rowCount() > 0;
    }
}
