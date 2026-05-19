<?php

require_once 'Database.php';
 class Settings
 {
    private $settingID;
    private $key;
    private $value;
    private $updatedat;
    private $updatedby;
    private function getTableNames()
    {
        return ['Settings', 'Setting'];
    }

    function Edit($data)
    {
        $key = $data['key'];
        $value = $data['value'];
        foreach ($this->getTableNames() as $table) {
            try {
                $sql = "UPDATE `$table` SET `Value`=? WHERE `Key`=?";
                $params = [$value, $key];
                $stmt = Database::getInstance()->query($sql, $params);
                if ($stmt && $stmt->rowCount() > 0) {
                    return true;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }
        return false;
    }

    function Set($key, $value)
    {
        foreach ($this->getTableNames() as $table) {
            try {
                $sql = "INSERT INTO `$table` (`Key`, `Value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `Value`=?";
                $params = [$key, $value, $value];
                $stmt = Database::getInstance()->query($sql, $params);
                if ($stmt && $stmt->rowCount() > 0) {
                    return true;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }
        return false;
    }

    function Get($key)
    {
        foreach ($this->getTableNames() as $table) {
            try {
                $sql = "SELECT `Value` FROM `$table` WHERE `Key`=?";
                $params = [$key];
                $stmt = Database::getInstance()->query($sql, $params);
                if ($stmt && $stmt->rowCount() > 0) {
                    return $stmt->fetchColumn();
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return null;
    }

    function GetAll()
    {
        foreach ($this->getTableNames() as $table) {
            try {
                $sql = "SELECT `Key`, `Value` FROM `$table`";
                $stmt = Database::getInstance()->query($sql);
                if ($stmt && $stmt->rowCount() > 0) {
                    $settings = [];
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $settings[$row['Key'] ?? $row['key']] = $row['Value'] ?? $row['value'];
                    }
                    return $settings;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return [];
    }

    function Update($key, $value)
    {
        return $this->Set($key, $value);
    }
 }      
?>