<?php
 class Subscription
 {
    private $subscriptionID;
    private $parentID;
    private $childID;
    private $planname;
    private $baseprice;
    private $startdate;
    private $duedate;
    private $status;
    private $billingcycle;
    private $isoverdue;
    private $daysoverdue;

    function CalculateNextDue()
    {
        // Calculate next due date
    }

    function MarkOverdue()
    {
        // Mark overdue assignments
    }

    function ClearOverdue()
    {
        // Clear Overdue assignments
    }

    function GetTotalAmount()
    {
        // Code 
    }
    function GenerateInvoice()
    {
        // Code
    }
     function Cancel()
    {
        // Code
    }
 }
?>