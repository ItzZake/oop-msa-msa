<?php
require_once 'Database.php';
 class Tag
 {
    private $tagID;
    private $name;
    private $type;
    private $createdby;
    private $createdat;

    function ResolveChildren()
    {
        $sql = "SELECT c.* FROM Children c 
                INNER JOIN ChildTags ct ON c.ChildId = ct.ChildId
                WHERE ct.TagID = ?";
        return Database::getInstance()->fetchAll($sql, [$this->tagID]);
    }

    function AddChild($childID)
    {
        $sql = "INSERT INTO ChildTags (TagID, ChildID, AssignedAt) VALUES (?, ?, ?)";
        $params = [$this->tagID, $childID, date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function RemoveChild($childID)
    {
        $sql = "DELETE FROM ChildTags WHERE TagID = ? AND ChildID = ?";
        $stmt = Database::getInstance()->query($sql, [$this->tagID, $childID]);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetChildCount()
    {
        $sql = "SELECT COUNT(*) as count FROM ChildTags WHERE TagID = ?";
        $result = Database::getInstance()->fetchOne($sql, [$this->tagID]);
        return $result ? (int)$result['count'] : 0;
    }
 }
?>