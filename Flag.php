<?php
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
        // Code
    }

    function Clear($adminID, $reason)
    {
        // Code
    }

    function GetActive()
    {
        // Code
    }
 }
?>