<?php

require_once 'Database.php';
require_once 'ISubject.php';
class Notifiable
{
  private $userId;

  public function __construct($userId)
  {
    $this->userId = $userId;
  }

  function notify($message, $channel)
  {
    // Store notification in database
    $sql = "INSERT INTO Notifications (UserID, Type, Message, Channel, SentAt) 
            VALUES (?, ?, ?, ?, ?)";
    $params = [$this->userId, 'notification', $message, $channel, date('Y-m-d H:i:s')];
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }

  function GetPrefrences()
  {
    $sql = "SELECT * FROM NotificationPreferences WHERE UserID = ?";
    $params = [$this->userId];
    $result = Database::getInstance()->fetchOne($sql, $params);
    
    if ($result) {
      return [
        'email_enabled' => (bool)$result['EmailEnabled'],
        'whatsapp_enabled' => (bool)$result['WhatsAppEnabled'],
        'in_app_enabled' => (bool)$result['InAppEnabled'],
        'email' => $result['Email'],
        'phone' => $result['Phone']
      ];
    }
    
    // Return default preferences if none exist
    return [
      'email_enabled' => true,
      'whatsapp_enabled' => false,
      'in_app_enabled' => true,
      'email' => null,
      'phone' => null
    ];
  }

  function SetPrefrences($prefs)
  {
    $sql = "INSERT INTO NotificationPreferences (UserID, EmailEnabled, WhatsAppEnabled, InAppEnabled, Email, Phone) 
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            EmailEnabled = VALUES(EmailEnabled),
            WhatsAppEnabled = VALUES(WhatsAppEnabled),
            InAppEnabled = VALUES(InAppEnabled),
            Email = VALUES(Email),
            Phone = VALUES(Phone)";
    
    $params = [
      $this->userId,
      isset($prefs['email_enabled']) ? (int)$prefs['email_enabled'] : 1,
      isset($prefs['whatsapp_enabled']) ? (int)$prefs['whatsapp_enabled'] : 0,
      isset($prefs['in_app_enabled']) ? (int)$prefs['in_app_enabled'] : 1,
      isset($prefs['email']) ? $prefs['email'] : null,
      isset($prefs['phone']) ? $prefs['phone'] : null
    ];
    
    $stmt = Database::getInstance()->query($sql, $params);
    return $stmt && $stmt->rowCount() > 0;
  }
}
?>