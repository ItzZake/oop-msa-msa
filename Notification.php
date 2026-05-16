<?php
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
    }

    function Send($userID, $type, $message, $channel)
    {
        // Code
        $this->sentat = date("Y-m-d H:i");
        $this->userID = $userID;
        $this->type = $type;
        $this->message = $message;
        $this->channel = $channel;
    }
    function GetUnread($userID)
    {
        // Code
        
    }

    function GetUnreadCount($userID)
    {
        // Code
    }
 }
?>