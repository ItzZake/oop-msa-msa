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
        $sql = "INSERT INTO message (senderID, recipientID, content, isRead, sentAt) VALUES (?, ?, ?, ?, ?)";
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
        $sql = "UPDATE message SET isRead = 1, readAt = ? WHERE messageID = ?";
        $params = [date('Y-m-d H:i:s'), $messageID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetThread($userA, $userB)
    {
        $sql = "SELECT * FROM message WHERE 
                (senderID = ? AND recipientID = ?) OR (senderID = ? AND recipientID = ?) 
                ORDER BY sentAt ASC";
        $params = [$userA, $userB, $userB, $userA];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    function InsertMessage($senderId, $recipientId, $subject, $body)
    {
        $content = trim($subject . "\n" . $body);
        return $this->Send([
            'SenderID' => $senderId,
            'RecipientID' => $recipientId,
            'Content' => $content
        ]);
    }

    function GetConversation($userA, $userB)
    {
        return $this->GetThread($userA, $userB);
    }

    function GetUnread($userID)
    {
        $sql = "SELECT * FROM message WHERE recipientID = ? AND isRead = 0 ORDER BY sentAt DESC";
        return Database::getInstance()->fetchAll($sql, [$userID]);
    }
   
 }
?>