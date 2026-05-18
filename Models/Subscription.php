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
        $amount = $this->GetTotalAmount();
        
        $sql = "INSERT INTO Payments (SubscriptionID, ParentID, Amount, Gateway, Status, CreatedAt, UpdatedAt)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $this->subscriptionID,
            $this->parentID,
            $amount,
            'system',
            'Pending',
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
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
        
        $sql = "UPDATE Subscriptions SET Status = 'cancelled' WHERE SubscriptionID = ?";
        $params = [$this->subscriptionID];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            // Try to refund pending payments
            $paymentSql = "SELECT * FROM Payments WHERE SubscriptionID = ? AND Status = 'Pending'";
            $paymentParams = [$this->subscriptionID];
            $pendingPayments = Database::getInstance()->fetchAll($paymentSql, $paymentParams);
            
            if (!empty($pendingPayments)) {
                $refundSql = "UPDATE Payments SET Status = 'Refunded' WHERE SubscriptionID = ? AND Status = 'Pending'";
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
 }
?>