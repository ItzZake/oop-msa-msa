<?php
require_once 'Parent.php';
require_once 'Event.php';
require_once 'Child.php';
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
        // Code
    }

    function IsPending()
    {
        if($this->issigned == false)
            return true;
        return false;
    }
    function ExportPDF()
    {
        // Code 
    }
 }
?>