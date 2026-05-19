<?php

require_once 'ISubject.php';
require_once 'Payment.php';
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

    public function __construct($subscriptionID = null)
    {
        if ($subscriptionID) {
            $this->loadSubscriptionFromDatabase($subscriptionID);
        }
    }

    private function loadSubscriptionFromDatabase($subscriptionID)
    {
        $sql = "SELECT * FROM Subscription WHERE subscriptionID = ?";
        $result = Database::getInstance()->fetchOne($sql, [$subscriptionID]);
        if (!$result) {
            return false;
        }
        $this->subscriptionID = $result['subscriptionID'];
        $this->parentID = $result['parentID'];
        $this->childID = $result['childID'];
        $this->planname = $result['planName'] ?? null;
        $this->baseprice = $result['basePrice'] ?? 0;
        $this->startdate = $result['startDate'] ?? null;
        $this->duedate = $result['dueDate'] ?? null;
        $this->status = $result['status'] ?? null;
        $this->billingcycle = $result['billingCycle'] ?? null;
        $this->isoverdue = (bool) ($result['isOverdue'] ?? false);
        $this->daysoverdue = $result['daysOverdue'] ?? 0;
        return true;
    }

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

    function CalculateNextDueDate()
    {
        return $this->CalculateNextDue();
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
        $amount = $this->GetTotalAmount();
        
        $sql = "INSERT INTO payment (subscriptionID, parentID, amount, gateway, status)
                VALUES (?, ?, ?, ?, ?)";
        $params = [
            $this->subscriptionID,
            $this->parentID,
            $amount,
            'system',
            'Pending'
        ];
        
        $stmt = Database::getInstance()->query($sql, $params);
        if ($stmt && $stmt->rowCount() > 0) {
            $paymentId = Database::getInstance()->getConnection()->lastInsertId();
            return [
                'status' => 'success',
                'message' => 'Invoice generated successfully',
                'paymentId' => $paymentId,
                'amount' => $amount
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Failed to generate invoice'
        ];
    }

    function Cancel()
    {
        $this->status = 'cancelled';
        
        $sql = "UPDATE subscription SET status = 'cancelled' WHERE subscriptionID = ?";
        $params = [$this->subscriptionID];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            // Try to refund pending payments
            $paymentSql = "SELECT * FROM payment WHERE subscriptionID = ? AND status = 'Pending'";
            $paymentParams = [$this->subscriptionID];
            $pendingPayments = Database::getInstance()->fetchAll($paymentSql, $paymentParams);
            
            if (!empty($pendingPayments)) {
                $refundSql = "UPDATE payment SET status = 'Refunded' WHERE subscriptionID = ? AND status = 'Pending'";
                $refundParams = [$this->subscriptionID];
                Database::getInstance()->query($refundSql, $refundParams);
            }
            
            return [
                'status' => 'success',
                'message' => 'Subscription cancelled successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Failed to cancel subscription'
        ];
    }

    function SaveSubscription($childId, $planId)
    {
        $sql = "SELECT parentID FROM Child WHERE childID = ?";
        $child = Database::getInstance()->fetchOne($sql, [$childId]);
        if (!$child) {
            return false;
        }
        $parentId = $child['parentID'];
        $startDate = date('Y-m-d');
        $dueDate = date('Y-m-d', strtotime('+30 days'));
        $sql = "INSERT INTO Subscription (parentID, childID, planID, basePrice, startDate, dueDate, status, billingCycle)
                VALUES (?, ?, ?, ?, ?, ?, 'Active', 'Monthly')";
        $params = [$parentId, $childId, $planId, 0, $startDate, $dueDate];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetAll()
    {
        $rows = Database::getInstance()->fetchAll("SELECT * FROM Subscription");
        if (!$rows) {
            return [];
        }
        $subscriptions = [];
        foreach ($rows as $row) {
            $subscription = new self();
            $subscription->loadSubscriptionFromDatabase($row['subscriptionID']);
            $subscriptions[] = $subscription;
        }
        return $subscriptions;
    }

    function GetOverdue()
    {
        $rows = Database::getInstance()->fetchAll("SELECT * FROM Subscription WHERE dueDate < CURDATE() AND status != 'Paid'");
        if (!$rows) {
            return [];
        }
        $subscriptions = [];
        foreach ($rows as $row) {
            $subscription = new self();
            $subscription->loadSubscriptionFromDatabase($row['subscriptionID']);
            $subscriptions[] = $subscription;
        }
        return $subscriptions;
    }

    function GetAllActive()
    {
        $rows = Database::getInstance()->fetchAll("SELECT * FROM Subscription WHERE status = 'Active'");
        if (!$rows) {
            return [];
        }
        $subscriptions = [];
        foreach ($rows as $row) {
            $subscription = new self();
            $subscription->loadSubscriptionFromDatabase($row['subscriptionID']);
            $subscriptions[] = $subscription;
        }
        return $subscriptions;
    }

    function QueueReminder($subscriptionId, $nextDueDate)
    {
        if (empty($subscriptionId) || empty($nextDueDate)) {
            return false;
        }
        return true;
    }

    function UpdateDueDate($subscriptionId, $nextDueDate)
    {
        $sql = "UPDATE Subscription SET dueDate = ? WHERE subscriptionID = ?";
        $stmt = Database::getInstance()->query($sql, [$nextDueDate, $subscriptionId]);
        return $stmt && $stmt->rowCount() > 0;
    }

    function FlagAsOverdue($subscriptionId)
    {
        $sql = "UPDATE Subscription SET status = 'Overdue', isOverdue = 1 WHERE subscriptionID = ?";
        $stmt = Database::getInstance()->query($sql, [$subscriptionId]);
        return $stmt && $stmt->rowCount() > 0;
    }

    function RestrictAccess($parentId)
    {
        $sql = "UPDATE Subscription SET status = 'Restricted' WHERE parentID = ?";
        $stmt = Database::getInstance()->query($sql, [$parentId]);
        return $stmt && $stmt->rowCount() > 0;
    }

    function FlagAsUnpaid($subscriptionId)
    {
        $sql = "UPDATE Subscription SET status = 'Unpaid' WHERE subscriptionID = ?";
        $stmt = Database::getInstance()->query($sql, [$subscriptionId]);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetStartDate()
    {
        return $this->startdate;
    }

    function GetSubscriptionId()
    {
        return $this->subscriptionID;
    }

    function GetParentId()
    {
        return $this->parentID;
    }
 }
?>