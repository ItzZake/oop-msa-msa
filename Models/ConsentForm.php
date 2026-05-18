<?php
require_once 'Database.php';
require_once 'Parent.php';
require_once 'Event.php';
require_once 'Child.php';
require_once 'NotificationManager.php';
require_once 'Notification.php';
 class ConsentForm
 {
    private $consentID;
    private $eventID;
    private $parentID;
    private $childID;
    private $issigned;
    private $signedat;
    private $sentat;
    private $ReminderCount;

    function Sign($ParentID, $ChildID,&$EventID)
    {
        $this->issigned = true;
        $this->signedat = date("Y-m-d H:i:s");
        $this->parentID = $ParentID;
        $this->childID = $ChildID;
        $this->eventID = $EventID;
        $sql = "INSERT INTO ConsentForms (EventID, ParentID, ChildID, IsSigned, SignedAt)
         VALUES (?,?,?,?,?)";
         $params = [$this->eventID, $this->parentID, $this->childID, $this->issigned, $this->signedat];
         $stmt = Database::getInstance()->query($sql, $params);
         if ($stmt && $stmt->rowCount() > 0) {
           return true;
        }
    }

    function SendReminder()
    {
        require_once 'NotificationManager.php';
        $notification = new Notification();
        $sql = "SELECT ParentID, ChildID, EventID FROM ConsentForms WHERE ConsentID = ?";
        $form = Database::getInstance()->fetchOne($sql, [$this->consentID]);
        if (!$form) {
            return false;
        }
        $manager = NotificationManager::getInstance();
        return $manager->NotifyUser($form['ParentID'], 'Please sign the consent form for your child.');
    }

    function ExportPDF()
    {
        $sql = "SELECT * FROM ConsentForms WHERE ConsentID = ?";
        $form = Database::getInstance()->fetchOne($sql, [$this->consentID]);
        if (!$form) {
            return ['status' => 'error', 'message' => 'Consent form not found'];
        }

        $fileName = 'consent_form_' . $this->consentID . '_' . date('YmdHis') . '.pdf';
        $filePath = '/uploads/consents/' . $fileName;
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads/consents')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/uploads/consents', 0755, true);
        }

        $content = "Consent Form ID: {$this->consentID}\n";
        $content .= "Event ID: {$form['EventID']}\n";
        $content .= "Parent ID: {$form['ParentID']}\n";
        $content .= "Child ID: {$form['ChildID']}\n";
        $content .= "Signed: " . ($form['IsSigned'] ? 'Yes' : 'No') . "\n";
        $content .= "Signed At: {$form['SignedAt']}\n";

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $filePath, $content);
        return ['status' => 'success', 'filepath' => $filePath];
    }
 }
?>