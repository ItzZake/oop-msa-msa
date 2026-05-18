<?php
require_once 'Database.php';
 class ARRule
 {
    private $ruleID;
    private $courseID;
    private $assetID;
    private $object1;
    private $object2;
    private $object3;
    private $displayname;
    private $description;
    private $confidencethreshold;
    private $isactive;
    private $createdby;

    function Evaluate($detectedobjects)
    {
        $sql = "SELECT * FROM ARRules WHERE RuleID = ?";
        $rule = Database::getInstance()->fetchOne($sql, [$this->ruleID]);
        if (!$rule || !$rule['IsActive']) {
            return false;
        }

        $conditions = json_decode($rule['TriggerCondition'], true);
        $objects = is_array($detectedobjects) ? $detectedobjects : [];
        $matches = 0;

        foreach ($conditions as $condition) {
            if (in_array($condition, $objects, true)) {
                $matches++;
            }
        }

        return $matches === count($conditions);
    }

    function GetAsset()
    {
        $sql = "SELECT * FROM ARAssets WHERE AssetID = ?";
        return Database::getInstance()->fetchOne($sql, [$this->assetID]);
    }

    function Activate()
    {
        $sql = "UPDATE ARRules SET IsActive = 1 WHERE RuleID = ?";
        $stmt = Database::getInstance()->query($sql, [$this->ruleID]);
        return $stmt && $stmt->rowCount() > 0;
    }

    function Deactivate()
    {
        $sql = "UPDATE ARRules SET IsActive = 0 WHERE RuleID = ?";
        $stmt = Database::getInstance()->query($sql, [$this->ruleID]);
        return $stmt && $stmt->rowCount() > 0;
    }
 }
?>