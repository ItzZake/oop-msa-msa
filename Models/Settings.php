<?php

require_once 'Database.php';
 class Settings
 {
    private $settingID;
    private $key;
    private $value;
    private $updatedat;
    private $updatedby;
    function Edit($data)
    {
        $key = $data['key'];
        $value = $data['value'];
        $sql = "UPDATE Settings SET Value=? WHERE `Key`=?";
         $params = [$value, $key];
         $stmt = Database::getInstance()->query($sql, $params);
         if ($stmt && $stmt->rowCount() > 0) {
           return true;
        }
    }
    function Set($key, $value)
    {
        $sql = "INSERT INTO Settings (`Key`, `Value`) VALUES (?,?) ON DUPLICATE KEY UPDATE Value=?";
         $params = [$key, $value, $value];
         $stmt = Database::getInstance()->query($sql, $params);
         if ($stmt && $stmt->rowCount() > 0) {
           return true;
        }
    }
    function Get($key)
    {
        $sql = "SELECT Value FROM Settings WHERE `Key`=?";
         $params = [$key];
         $stmt = Database::getInstance()->query($sql, $params);
         if ($stmt && $stmt->rowCount() > 0) {
           return $stmt->fetchColumn();
        }
        return null;
    }
    function GetAll()
    {
        $sql = "SELECT `Key`, `Value` FROM Settings";
         $stmt = Database::getInstance()->query($sql);
         if ($stmt && $stmt->rowCount() > 0) {
           $settings = [];
           while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
             $settings[$row['Key']] = $row['Value'];
           }
           return $settings;
        }
        return [];
    }
 }      
?>