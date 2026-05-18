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
        if($this->billingcycle == "Monthly") {
            $this->duedate = date("Y-m-d", strtotime($this->startdate . ' +1 month'));
        } elseif ($this->billingcycle == "Quarterly") {
            $this->duedate = date("Y-m-d", strtotime($this->startdate . ' +3 month'));
        } elseif ($this->billingcycle == "Annually") {
            $this->duedate = date("Y-m-d", strtotime($this->startdate . ' +1 year'));
        }
    }

    function MarkOverdue()
    {
        if(date("Y-m-d") > $this->duedate) {
            $this->isoverdue = true;
            $this->daysoverdue = (strtotime(date("Y-m-d")) - strtotime($this->duedate)) / (60 * 60 * 24);
        }
    }

    function ClearOverdue()
    {
        if($this->status == "paid") {
            $this->isoverdue = false;
            $this->daysoverdue = 0;
        }
    }

    function GetTotalAmount()
    {
        $total = $this->baseprice;
        if($this->isoverdue) {
            $total += $this->daysoverdue * 5; // $5 per day overdue
        }
        return $total;
    }
    function GenerateInvoice()
    {
        // Code to generate invoice for subscription
    }
     function Cancel()
    {
        // Code
    }
 }
?>