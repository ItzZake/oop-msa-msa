<?php
require_once 'Database.php';
 class Flag
 {
    private $flagID;
    private $childID;
    private $type;
    private $details;
    private $isActive;
    private $createdat;
    private $clearedby;
    private $clearReason;

    function Raise($type, $details)
    {
        $this->type = $type;
        $this->details = $details;
        $this->isActive = 1;
        $this->createdat = date('Y-m-d H:i:s');

        $sql = "INSERT INTO flag (childID, type, details, isActive, createdAt) VALUES (?, ?, ?, ?, ?)";
        $params = [$this->childID, $this->type, $this->details, $this->isActive, $this->createdat];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function Clear($adminID, $reason)
    {
        $this->isActive = 0;
        $this->clearedby = $adminID;
        $this->clearReason = $reason;
        $sql = "UPDATE flag SET isActive = 0, clearedBy = ?, clearReason = ?, clearedAt = ? WHERE flagID = ?";
        $params = [$adminID, $reason, date('Y-m-d H:i:s'), $this->flagID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetActive()
    {
        $sql = "SELECT * FROM flag WHERE isActive = 1";
        return Database::getInstance()->fetchAll($sql, []);
    }

    function SetFlag($type, $childId, $details)
    {
        $this->childID = $childId;
        $this->type = $type;
        $this->details = $details;
        $this->isActive = 1;
        $this->createdat = date('Y-m-d H:i:s');

        $sql = "INSERT INTO flag (childID, type, details, isActive, createdAt) VALUES (?, ?, ?, ?, ?)";
        $params = [$this->childID, $this->type, $this->details, $this->isActive, $this->createdat];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetAllActiveFlags()
    {
        $sql = "SELECT * FROM flag WHERE isActive = 1";
        return Database::getInstance()->fetchAll($sql, []);
    }
 }
?>