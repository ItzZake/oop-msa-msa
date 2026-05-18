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

        $sql = "INSERT INTO Flags (ChildID, Type, Details, IsActive, CreatedAt) VALUES (?, ?, ?, ?, ?)";
        $params = [$this->childID, $this->type, $this->details, $this->isActive, $this->createdat];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function Clear($adminID, $reason)
    {
        $this->isActive = 0;
        $this->clearedby = $adminID;
        $this->clearReason = $reason;
        $sql = "UPDATE Flags SET IsActive = 0, ClearedBy = ?, ClearReason = ?, ClearedAt = ? WHERE FlagID = ?";
        $params = [$adminID, $reason, date('Y-m-d H:i:s'), $this->flagID];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetActive()
    {
        $sql = "SELECT * FROM Flags WHERE IsActive = 1";
        return Database::getInstance()->fetchAll($sql, []);
    }
 }
?>