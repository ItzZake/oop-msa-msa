<?php
 class Message
 {
    private $messageID;
    private $senderID;
    private $recipientID;
    private $content;
    private $isRead;
    private $sentat;
    private $readat;

    function Send($data)
    {
        $sql = "INSERT INTO Messages (SenderID, RecipientID, Content, IsRead, SentAt) VALUES (?, ?, ?, ?, ?)";
        $params = [
            $data['SenderID'],
            $data['RecipientID'],
            $data['Content'],
            0,
            date('Y-m-d H:i:s')
        ];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function MarkRead($messageID)
    {
        $sql = "UPDATE Messages SET IsRead = 1, ReadAt = ? WHERE MessageID = ?";
        $params = [date('Y-m-d H:i:s'), $messageID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetThread($userA, $userB)
    {
        $sql = "SELECT * FROM Messages WHERE 
                (SenderID = ? AND RecipientID = ?) OR (SenderID = ? AND RecipientID = ?) 
                ORDER BY SentAt ASC";
        $params = [$userA, $userB, $userB, $userA];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function GetUnread($userID)
    {
        $sql = "SELECT * FROM Messages WHERE RecipientID = ? AND IsRead = 0 ORDER BY SentAt DESC";
        return Database::getInstance()->fetchAll($sql, [$userID]);
    }
   
 }
?>