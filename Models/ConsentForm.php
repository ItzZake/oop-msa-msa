<?php
require_once 'Database.php';
require_once 'NotificationManager.php';
class ConsentForm
{
    private $consentID;
    private $eventID;
    private $parentID;
    private $childID;
    private $isSigned;
    private $signedAt;
    private $sentAt;
    private $reminderCount;

    function Sign($parentID, $childID, $eventID)
    {
        $this->isSigned = 1;
        $this->signedAt = date("Y-m-d H:i:s");
        $this->parentID = $parentID;
        $this->childID = $childID;
        $this->eventID = $eventID;

        $sql = "INSERT INTO ConsentForm (eventID, parentID, childID, isSigned, signedAt)
                VALUES (?, ?, ?, ?, ?)";
        $params = [$this->eventID, $this->parentID, $this->childID, $this->isSigned, $this->signedAt];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function SendReminder()
    {
        $sql = "SELECT parentID, childID, eventID FROM ConsentForm WHERE consentID = ?";
        $form = Database::getInstance()->fetchOne($sql, [$this->consentID]);
        if (!$form) {
            return false;
        }

        $manager = NotificationManager::getInstance();
        return $manager->NotifyUser($form['parentID'], 'Please sign the consent form for your child.');
    }

    function ExportPDF()
    {
        $sql = "SELECT * FROM ConsentForm WHERE consentID = ?";
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
        $content .= "Event ID: {$form['eventID']}\n";
        $content .= "Parent ID: {$form['parentID']}\n";
        $content .= "Child ID: {$form['childID']}\n";
        $content .= "Signed: " . ($form['isSigned'] ? 'Yes' : 'No') . "\n";
        $content .= "Signed At: {$form['signedAt']}\n";

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $filePath, $content);
        return ['status' => 'success', 'filepath' => $filePath];
    }
}
?>