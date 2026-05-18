<?php

require_once 'Notification.php';
require_once 'INotificationChannel.php';
require_once 'EmailChannel.php';
require_once 'WhatsAppChannel.php';
require_once 'InAppChannel.php';
require_once 'Notifiable.php';

class NotificationDispatcher
{
    private $channels = [];
    private $notification;

    public function __construct()
    {
        $this->notification = new Notification();
        // Register available channels
        $this->channels['email'] = new EmailChannel();
        $this->channels['whatsapp'] = new WhatsAppChannel();
        $this->channels['in_app'] = new InAppChannel();
    }

    /**
     * Send notification to a user through specified channels based on preferences
     */
    public function Send($userId, $message, $type = 'notification')
    {
        $notifiable = new Notifiable($userId);
        $preferences = $notifiable->GetPrefrences();
        
        $sentChannels = [];
        
        // Send through enabled channels
        if ($preferences['email_enabled'] && $preferences['email']) {
            $this->SendEmail($preferences['email'], 'Notification', $message);
            $sentChannels[] = 'email';
        }
        
        if ($preferences['whatsapp_enabled'] && $preferences['phone']) {
            $this->SendWhatsApp($preferences['phone'], $message);
            $sentChannels[] = 'whatsapp';
        }
        
        if ($preferences['in_app_enabled']) {
            $this->SendInApp($userId, $message);
            $sentChannels[] = 'in_app';
        }
        
        // Log notification in database
        $this->notification->Send($userId, $type, $message, implode(',', $sentChannels));
        
        return $sentChannels;
    }

    /**
     * Send email notification
     */
    public function SendEmail($to, $subject, $body)
    {
        if (isset($this->channels['email'])) {
            return $this->channels['email']->Send($to, $body);
        }
        return false;
    }

    /**
     * Send WhatsApp notification
     */
    public function SendWhatsApp($phone, $message)
    {
        if (isset($this->channels['whatsapp'])) {
            return $this->channels['whatsapp']->Send($phone, $message);
        }
        return false;
    }

    /**
     * Send in-app notification
     */
    public function SendInApp($userId, $message)
    {
        if (isset($this->channels['in_app'])) {
            return $this->channels['in_app']->Send($userId, $message);
        }
        return false;
    }

    /**
     * Broadcast notification to multiple users by tag
     */
    public function BroadCast($groupTag, $message)
    {
        // Get all users with the specified tag
        $sql = "SELECT u.UserID FROM Users u 
                JOIN UserTags ut ON u.UserID = ut.UserID 
                JOIN Tags t ON ut.TagID = t.TagID 
                WHERE t.TagName = ?";
        $params = [$groupTag];
        $users = Database::getInstance()->fetchAll($sql, $params);
        
        $sentCount = 0;
        foreach ($users as $user) {
            $sentChannels = $this->Send($user['UserID'], $message);
            if (!empty($sentChannels)) {
                $sentCount++;
            }
        }
        
        return $sentCount;
    }

    /**
     * Register a custom notification channel
     */
    public function RegisterChannel($channelName, INotificationChannel $channel)
    {
        $this->channels[$channelName] = $channel;
    }

    /**
     * Get all registered channels
     */
    public function GetChannels()
    {
        return array_keys($this->channels);
    }
}
 
?>