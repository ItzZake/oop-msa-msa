<?php
require_once 'Database.php';
 class ARAsset
 {
    private $assetID;
    private $courseID;
    private $name;
    private $filepath;
    private $filetype;
    private $filesizekb;
    private $uploadedby;
    private $uploadedat;
    private $isactive;

    function GetURL()
    {
        if (!$this->isactive) {
            return null;
        }
        return $this->filepath;
    }

    function Delete()
    {
        if (empty($this->filepath)) {
            return false;
        }

        $sql = "UPDATE ARAssets SET IsActive = 0 WHERE AssetID = ?";
        $stmt = Database::getInstance()->query($sql, [$this->assetID]);
        if ($stmt && $stmt->rowCount() > 0) {
            $physicalPath = $_SERVER['DOCUMENT_ROOT'] . $this->filepath;
            if (file_exists($physicalPath)) {
                @unlink($physicalPath);
            }
            return true;
        }
        return false;
    }
 }
?>