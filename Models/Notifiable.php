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
        $sql = "INSERT INTO notification (userID, type, title, message, channel, sentAt, isRead) 
                VALUES (?, ?, ?, ?, ?, ?, 0)";
        $params = [$this->userId, 'notification', 'Notification', $message, $channel, date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetPrefrences()
    {
        $sql = "SELECT notifPreferences FROM parent WHERE userID = ?";
        $params = [$this->userId];
        $result = Database::getInstance()->fetchOne($sql, $params);

        if ($result && $result['notifPreferences']) {
            $prefs = json_decode($result['notifPreferences'], true);
            return [
                'email_enabled' => isset($prefs['email_enabled']) ? (bool)$prefs['email_enabled'] : true,
                'whatsapp_enabled' => isset($prefs['whatsapp_enabled']) ? (bool)$prefs['whatsapp_enabled'] : false,
                'in_app_enabled' => isset($prefs['in_app_enabled']) ? (bool)$prefs['in_app_enabled'] : true,
                'email' => $prefs['email'] ?? null,
                'phone' => $prefs['phone'] ?? null
            ];
        }

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
        $current = $this->GetPrefrences();
        $updated = array_merge($current, $prefs);
        $sql = "UPDATE parent SET notifPreferences = ? WHERE userID = ?";
        $params = [json_encode($updated), $this->userId];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }
}
?>