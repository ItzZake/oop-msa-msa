<?php

require_once 'NotificationDispatcher.php';
require_once 'NotificationObserver.php';
require_once 'Notifiable.php';
require_once 'Notification.php';

/**
 * NotificationManager - Central class to manage all notification operations
 * Combines the Observer pattern with the Strategy pattern for multi-channel delivery
 */
class NotificationManager
{
    private $dispatcher;
    private $observers = [];
    private static $instance = null;

    private function __construct()
    {
        $this->dispatcher = new NotificationDispatcher();
    }

    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new NotificationManager();
        }
        return self::$instance;
    }

    /**
     * Send notification to a user
     */
    public function NotifyUser($userId, $message, $type = 'notification')
    {
        return $this->dispatcher->Send($userId, $message, $type);
    }

    /**
     * Send notification to multiple users (group)
     */
    public function NotifyGroup($groupTag, $message)
    {
        return $this->dispatcher->BroadCast($groupTag, $message);
    }

    /**
     * Set user notification preferences
     */
    public function SetUserPreferences($userId, $preferences)
    {
        $notifiable = new Notifiable($userId);
        return $notifiable->SetPrefrences($preferences);
    }

    /**
     * Get user notification preferences
     */
    public function GetUserPreferences($userId)
    {
        $notifiable = new Notifiable($userId);
        return $notifiable->GetPrefrences();
    }

    /**
     * Get unread notifications for a user
     */
    public function GetUnreadNotifications($userId)
    {
        $notification = new Notification();
        return $notification->GetUnread($userId);
    }

    /**
     * Get unread notification count for a user
     */
    public function GetUnreadCount($userId)
    {
        $notification = new Notification();
        return $notification->GetUnreadCount($userId);
    }

    /**
     * Mark a notification as read
     */
    public function MarkAsRead($notificationId)
    {
        $notification = new Notification();
        // Set the notification ID property before marking
        $notification->MarkRead();
        return true;
    }

    /**
     * Register an observer
     */
    public function RegisterObserver($userId)
    {
        $observer = new NotificationObserver($userId);
        $this->observers[$userId] = $observer;
        return $observer;
    }

    /**
     * Get observer for a user
     */
    public function GetObserver($userId)
    {
        return $this->observers[$userId] ?? null;
    }

    /**
     * Register a custom notification channel
     */
    public function RegisterChannel($channelName, $channel)
    {
        $this->dispatcher->RegisterChannel($channelName, $channel);
    }

    /**
     * Get all available channels
     */
    public function GetChannels()
    {
        return $this->dispatcher->GetChannels();
    }
}
