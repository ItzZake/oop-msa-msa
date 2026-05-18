<?php

/**
 * NOTIFICATION SYSTEM - DESIGN PATTERN IMPLEMENTATION
 * ================================================
 * 
 * This system implements the Observer Pattern combined with the Strategy Pattern
 * to create a flexible, multi-channel notification system.
 * 
 * ARCHITECTURE:
 * =============
 * 
 * 1. OBSERVER PATTERN (Core)
 *    - ISubject: Interface for observable objects
 *    - IObserver: Interface for observers
 *    - NotificationObserver: Concrete observer implementation
 *
 * 2. STRATEGY PATTERN (Channel Delivery)
 *    - INotificationChannel: Interface for notification channels
 *    - EmailChannel: Strategy for email delivery
 *    - WhatsAppChannel: Strategy for WhatsApp delivery
 *    - InAppChannel: Strategy for in-app delivery
 *
 * 3. SUPPORTING CLASSES
 *    - Notifiable: Manages user notification preferences
 *    - Notification: Represents a single notification
 *    - NotificationDispatcher: Orchestrates multi-channel delivery
 *    - NotificationManager: Singleton facade for all operations
 *
 * ================================================
 * USAGE EXAMPLES:
 * ================================================
 * 
 * 1. SEND NOTIFICATION TO SINGLE USER:
 * 
 *    $manager = NotificationManager::getInstance();
 *    $manager->NotifyUser(123, "Your payment has been confirmed");
 *
 * 2. SEND NOTIFICATION TO GROUP:
 * 
 *    $manager = NotificationManager::getInstance();
 *    $manager->NotifyGroup('teachers', "New assignment submitted");
 *
 * 3. SET USER PREFERENCES:
 * 
 *    $manager = NotificationManager::getInstance();
 *    $preferences = [
 *        'email_enabled' => true,
 *        'whatsapp_enabled' => true,
 *        'in_app_enabled' => true,
 *        'email' => 'user@example.com',
 *        'phone' => '+201234567890'
 *    ];
 *    $manager->SetUserPreferences(123, $preferences);
 *
 * 4. GET USER PREFERENCES:
 * 
 *    $manager = NotificationManager::getInstance();
 *    $prefs = $manager->GetUserPreferences(123);
 *
 * 5. GET UNREAD NOTIFICATIONS:
 * 
 *    $manager = NotificationManager::getInstance();
 *    $unread = $manager->GetUnreadNotifications(123);
 *
 * 6. GET UNREAD COUNT:
 * 
 *    $manager = NotificationManager::getInstance();
 *    $count = $manager->GetUnreadCount(123);
 *
 * 7. USING OBSERVER PATTERN IN YOUR MODELS:
 * 
 *    // In your Payment or Subscription model
 *    class Payment implements ISubject {
 *        private $observers = [];
 *        
 *        public function Attach(IObserver $observer) {
 *            $this->observers[] = $observer;
 *        }
 *        
 *        public function Notify() {
 *            foreach ($this->observers as $observer) {
 *                $observer->Update($this);
 *            }
 *        }
 *        
 *        public function ProcessPayment() {
 *            // ... payment logic ...
 *            $this->Notify(); // Notify all observers
 *        }
 *    }
 *
 * 8. REGISTER CUSTOM NOTIFICATION CHANNEL:
 * 
 *    // Create your custom channel
 *    class SMSChannel implements INotificationChannel {
 *        public function Send($recipient, $message) {
 *            // SMS delivery logic
 *            return true;
 *        }
 *    }
 *    
 *    // Register it
 *    $manager = NotificationManager::getInstance();
 *    $manager->RegisterChannel('sms', new SMSChannel());
 *
 * ================================================
 * CLASS RESPONSIBILITIES:
 * ================================================
 * 
 * ISubject:
 *   - attach(observer)     : Register an observer
 *   - detach(observer)     : Unregister an observer
 *   - notify()             : Notify all registered observers
 *
 * IObserver:
 *   - update(subject)      : Called when subject notifies
 *
 * Notifiable:
 *   - notify()             : Store notification in database
 *   - GetPrefrences()      : Get user notification preferences
 *   - SetPrefrences()      : Update user notification preferences
 *
 * Notification:
 *   - Send()               : Send and store notification
 *   - GetUnread()          : Get unread notifications for user
 *   - GetUnreadCount()     : Get count of unread notifications
 *   - MarkRead()           : Mark notification as read
 *
 * NotificationDispatcher:
 *   - Send()               : Send through preferred channels
 *   - SendEmail()          : Send via email
 *   - SendWhatsApp()       : Send via WhatsApp
 *   - SendInApp()          : Send via in-app
 *   - BroadCast()          : Send to user group
 *   - RegisterChannel()    : Add custom channel
 *
 * NotificationManager (Singleton):
 *   - NotifyUser()         : Notify single user
 *   - NotifyGroup()        : Notify user group
 *   - SetUserPreferences() : Update preferences
 *   - GetUserPreferences() : Retrieve preferences
 *   - GetUnreadNotifications() : Get unread messages
 *   - GetUnreadCount()     : Get unread count
 *   - RegisterObserver()   : Register observer
 *   - RegisterChannel()    : Add custom channel
 *
 * ================================================
 * DATABASE TABLES REQUIRED:
 * ================================================
 * 
 * Notifications:
 *   - NotificationID (PK)
 *   - UserID (FK)
 *   - Type
 *   - Message
 *   - Channel
 *   - IsRead
 *   - SentAt
 *   - ReadAt
 *
 * NotificationPreferences:
 *   - UserID (PK, FK)
 *   - EmailEnabled
 *   - WhatsAppEnabled
 *   - InAppEnabled
 *   - Email
 *   - Phone
 *
 * InAppNotifications:
 *   - NotificationID (PK)
 *   - UserID (FK)
 *   - Message
 *   - IsRead
 *   - CreatedAt
 *
 */
