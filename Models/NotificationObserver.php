<?php

require_once 'IObserver.php';
require_once 'NotificationDispatcher.php';

class NotificationObserver implements IObserver
{
    private $dispatcher;
    private $userId;

    public function __construct($userId = null)
    {
        $this->dispatcher = new NotificationDispatcher();
        $this->userId = $userId;
    }

    /**
     * Update method called when subject notifies observers
     */
    public function Update(ISubject $subject)
    {
        // Get notification details from subject and send
        // This is called when the subject (e.g., Subscription, Payment, etc.) notifies observers
        
        // Example implementation:
        // $message = $subject->getNotificationMessage();
        // $this->dispatcher->Send($this->userId, $message);
    }

    /**
     * Set the user ID for this observer
     */
    public function SetUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get the dispatcher instance
     */
    public function GetDispatcher()
    {
        return $this->dispatcher;
    }
}
