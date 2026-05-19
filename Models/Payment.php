<?php

require_once 'Database.php';
require_once 'IpaymentStrategy.php';

/**
 * Payment - Manages payment processing using strategy pattern
 * Supports multiple payment gateways (Fawry, Paymob, etc.)
 */
class Payment
{
    private $paymentId;
    private $subscriptionId;
    private $amount;
    private $parentId;
    private $gateway;
    private $gatewayTXId;
    private $status; // Pending, Completed, Failed, Cancelled, Refunded
    private $paidAt;
    private $invoicePath;
    private $lineItems;
    private $paymentStrategy;
    private $createdAt;
    private $updatedAt;

    public function __construct($paymentId = null)
    {
        if ($paymentId) {
            $this->loadPaymentFromDatabase($paymentId);
        }
    }

    /**
     * Load payment details from database
     */
    private function loadPaymentFromDatabase($paymentId)
    {
        $sql = "SELECT * FROM Payment WHERE paymentID = ?";
        $params = [$paymentId];
        $result = Database::getInstance()->fetchOne($sql, $params);

        if ($result) {
            $this->paymentId = $result['paymentID'];
            $this->subscriptionId = $result['subscriptionID'];
            $this->amount = $result['amount'];
            $this->parentId = $result['parentID'];
            $this->gateway = $result['gateway'];
            $this->gatewayTXId = $result['gatewayTxID'];
            $this->status = $result['status'];
            $this->paidAt = $result['paidAt'];
            $this->invoicePath = $result['invoicePath'];
            $this->lineItems = $result['lineItems'] ? json_decode($result['lineItems'], true) : [];
        }
    }

    /**
     * Load line items for this payment
     */
    private function loadLineItems()
    {
        if (!$this->paymentId) {
            $this->lineItems = [];
            return;
        }

        $result = Database::getInstance()->fetchOne("SELECT lineItems FROM Payment WHERE paymentID = ?", [$this->paymentId]);
        $this->lineItems = $result && $result['lineItems'] ? json_decode($result['lineItems'], true) : [];
    }

    /**
     * Set payment strategy
     */
    public function SetPaymentStrategy(IpaymentStrategy $strategy)
    {
        $this->paymentStrategy = $strategy;
        $this->gateway = $strategy->GetGatewayName();
    }

    /**
     * Get payment strategy
     */
    public function GetPaymentStrategy()
    {
        return $this->paymentStrategy;
    }

    /**
     * Process payment with the configured strategy
     */
    public function ProcessPayment($paymentData)
    {
        if (!$this->paymentStrategy) {
            return [
                'status' => 'error',
                'message' => 'No payment strategy configured',
                'code' => 'NO_STRATEGY'
            ];
        }

        try {
            // Process payment using strategy
            $result = $this->paymentStrategy->ProcessPayment($paymentData);

            if ($result['status'] === 'success') {
                // Save payment record to database
                $this->CreatePaymentRecord($paymentData, $result);
                return $result;
            }

            return $result;
        } catch (Exception $e) {
            error_log("Payment Processing Error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Payment processing failed: ' . $e->getMessage(),
                'code' => 'PROCESSING_ERROR'
            ];
        }
    }

    /**
     * Create payment record in database
     */
    private function CreatePaymentRecord($paymentData, $result)
    {
        $lineItems = isset($paymentData['items']) && is_array($paymentData['items']) ? json_encode($paymentData['items']) : null;
        $sql = "INSERT INTO Payment (subscriptionID, parentID, amount, gateway, status, lineItems) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $params = [
            $paymentData['subscriptionId'] ?? null,
            $paymentData['parentId'] ?? null,
            $paymentData['amount'],
            $this->gateway,
            'Pending',
            $lineItems
        ];

        $stmt = Database::getInstance()->query($sql, $params);
        if ($stmt && $stmt->rowCount() > 0) {
            $this->paymentId = Database::getInstance()->getConnection()->lastInsertId();
        }
    }

    /**
     * Process webhook payload from payment gateway
     */
    public function ProcessWebHook($payload)
    {
        if (!$this->paymentStrategy) {
            return [
                'status' => 'error',
                'message' => 'No payment strategy configured'
            ];
        }

        try {
            $result = $this->paymentStrategy->HandleWebHook($payload);

            if ($result['status'] === 'success' && isset($result['data'])) {
                $paymentStatus = $result['data']['paymentStatus'] ?? 'unknown';
                $this->UpdatePaymentStatus(
                    $result['data']['referenceNumber'],
                    $paymentStatus,
                    $result['data']['transactionStatus'] ?? null
                );
            }

            return $result;
        } catch (Exception $e) {
            error_log("Webhook Processing Error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Webhook processing failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mark payment as paid (completed)
     */
    public function MarkPaid($gatewayTXId = null, $paidAt = null)
    {
        $this->status = 'Paid';
        $this->paidAt = $paidAt ?? date('Y-m-d H:i:s');
        if ($gatewayTXId) {
            $this->gatewayTXId = $gatewayTXId;
        }

        $sql = "UPDATE Payment SET status = 'Paid', gatewayTxID = ?, paidAt = ? WHERE paymentID = ?";
        $params = [$this->gatewayTXId, $this->paidAt, $this->paymentId];
        $stmt = Database::getInstance()->query($sql, $params);

        return $stmt && $stmt->rowCount() > 0;
    }

    /**
     * Mark payment as failed
     */
    public function MarkFailed($reason = null)
    {
        $this->status = 'Failed';

        $sql = "UPDATE Payment SET status = 'Failed' WHERE paymentID = ?";
        $params = [$this->paymentId];
        $stmt = Database::getInstance()->query($sql, $params);

        if ($stmt && $stmt->rowCount() > 0) {
            // Log failure reason if provided
            if ($reason) {
                error_log("Payment {$this->paymentId} failed: {$reason}");
            }
            return true;
        }

        return false;
    }

    /**
     * Mark payment as refunded
     */
    public function MarkRefunded($refundAmount = null, $reason = null)
    {
        $this->status = 'Refunded';

        $sql = "UPDATE Payment SET status = 'Refunded' WHERE paymentID = ?";
        $params = [$this->paymentId];
        $stmt = Database::getInstance()->query($sql, $params);

        if ($stmt && $stmt->rowCount() > 0) {
            // Log refund if provided
            if ($reason) {
                error_log("Payment {$this->paymentId} refunded: {$reason}");
            }
            return true;
        }

        return false;
    }

    public function GetTotalRevenue()
    {
        $sql = "SELECT SUM(amount) AS totalRevenue FROM Payment WHERE status = 'Paid'";
        $result = Database::getInstance()->fetchOne($sql);
        return $result ? (float) $result['totalRevenue'] : 0;
    }

    public function GetReportByDateRange($startDate, $endDate)
    {
        $sql = "SELECT * FROM Payment WHERE createdAt BETWEEN ? AND ?";
        return Database::getInstance()->fetchAll($sql, [$startDate, $endDate]);
    }

    public function GetTransactionsByParentId($parentId)
    {
        return Database::getInstance()->fetchAll("SELECT * FROM Payment WHERE parentID = ? ORDER BY paidAt DESC", [$parentId]);
    }

    public function GetOverdueByDueDate()
    {
        $sql = "SELECT p.* FROM Payment p
                INNER JOIN Subscription s ON p.subscriptionID = s.subscriptionID
                WHERE p.status != 'Paid' AND s.dueDate < CURDATE()";
        $rows = Database::getInstance()->fetchAll($sql);
        $results = [];
        foreach ($rows as $row) {
            $results[] = new self($row['paymentID']);
        }
        return $results;
    }

    public function GetAggregateRevenue()
    {
        $sql = "SELECT SUM(amount) AS totalRevenue FROM Payment WHERE status = 'Paid'";
        $result = Database::getInstance()->fetchOne($sql);
        return $result ? (float) $result['totalRevenue'] : 0;
    }

    public function GetSubscriptionId()
    {
        return $this->subscriptionId;
    }

    private function UpdatePaymentStatus($reference, $paymentStatus, $transactionStatus = null)
    {
        $statusMap = [
            'completed' => 'Paid',
            'pending' => 'Pending',
            'failed' => 'Failed',
            'cancelled' => 'Failed',
            'refunded' => 'Refunded'
        ];

        $newStatus = $statusMap[$paymentStatus] ?? 'Pending';

        $sql = "UPDATE Payment SET status = ? WHERE paymentID = ?";
        $params = [$newStatus, $this->paymentId];
        Database::getInstance()->query($sql, $params);
    }

    /**
     * Verify transaction with gateway
     */
    public function VerifyTransaction($reference)
    {
        if (!$this->paymentStrategy) {
            return [
                'status' => 'error',
                'message' => 'No payment strategy configured'
            ];
        }

        return $this->paymentStrategy->VerifyTransaction($reference);
    }

    /**
     * Refund a payment
     */
    public function RefundPayment($refundAmount = null)
    {
        if (!$this->paymentStrategy) {
            return [
                'status' => 'error',
                'message' => 'No payment strategy configured'
            ];
        }

        $amount = $refundAmount ?? $this->amount;

        $result = $this->paymentStrategy->Refund($this->gatewayTXId ?? $this->paymentId, $amount);

        if ($result['status'] === 'success') {
            $this->MarkRefunded($amount, 'Refund processed successfully');
        }

        return $result;
    }

    /**
     * Generate PDF invoice
     */
    public function GeneratePdf()
    {
        if (!$this->paymentId) {
            return false;
        }

        $invoiceFileName = "invoice_" . $this->paymentId . "_" . time() . ".pdf";
        $invoicePath = "/uploads/invoices/" . $invoiceFileName;

        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads/invoices')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/uploads/invoices', 0755, true);
        }

        $content = "Invoice for Payment " . $this->paymentId . "\n";
        $content .= "Amount: " . number_format($this->amount, 2) . "\n";
        $content .= "Gateway: " . ($this->gateway ?? 'N/A') . "\n";
        $content .= "Reference: " . ($this->gatewayTXId ?? 'N/A') . "\n";
        $content .= "Status: " . ($this->status ?? 'N/A') . "\n";
        $content .= "Generated At: " . date('Y-m-d H:i:s') . "\n";

        $filePath = $_SERVER['DOCUMENT_ROOT'] . $invoicePath;
        file_put_contents($filePath, $content);

        $sql = "UPDATE Payment SET invoicePath = ? WHERE paymentID = ?";
        $params = [$invoicePath, $this->paymentId];
        $stmt = Database::getInstance()->query($sql, $params);

        if ($stmt && $stmt->rowCount() > 0) {
            $this->invoicePath = $invoicePath;
            return true;
        }

        return false;
    }

    /**
     * Add line item to payment
     */
    public function AddLineItem($description, $amount)
    {
        $item = [
            'description' => $description,
            'amount' => $amount
        ];

        if (!$this->paymentId) {
            $this->lineItems[] = $item;
            return true;
        }

        $currentItems = $this->lineItems ?? [];
        $currentItems[] = $item;
        $sql = "UPDATE Payment SET lineItems = ? WHERE paymentID = ?";
        $params = [json_encode($currentItems), $this->paymentId];
        $stmt = Database::getInstance()->query($sql, $params);

        if ($stmt && $stmt->rowCount() > 0) {
            $this->lineItems = $currentItems;
            return true;
        }

        return false;
    }

    /**
     * Get invoice details
     */
    public function GetInvoice()
    {
        return [
            'paymentId' => $this->paymentId,
            'subscriptionId' => $this->subscriptionId,
            'amount' => $this->amount,
            'gateway' => $this->gateway,
            'status' => $this->status,
            'paidAt' => $this->paidAt,
            'invoicePath' => $this->invoicePath,
            'lineItems' => $this->lineItems,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
    }

    /**
     * Get all payments for a subscription
     */
    public static function GetPaymentsBySubscription($subscriptionId)
    {
        $sql = "SELECT * FROM Payment WHERE subscriptionID = ? ORDER BY paymentID DESC";
        $params = [$subscriptionId];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    /**
     * Get all payments for a parent/user
     */
    public static function GetPaymentsByParent($parentId)
    {
        $sql = "SELECT * FROM Payment WHERE parentID = ? ORDER BY paymentID DESC";
        $params = [$parentId];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    /**
     * Get payment by reference number
     */
    public static function GetPaymentByReference($reference)
    {
        $sql = "SELECT * FROM Payment WHERE paymentID = ? OR gatewayTxID = ?";
        $params = [$reference, $reference];
        return Database::getInstance()->fetchOne($sql, $params);
    }

    // Getters
    public function GetPaymentId() { return $this->paymentId; }
    public function GetAmount() { return $this->amount; }
    public function GetStatus() { return $this->status; }
    public function GetGateway() { return $this->gateway; }
    public function GetGatewayTXId() { return $this->gatewayTXId; }
    public function GetLineItems() { return $this->lineItems; }
    public function GetInvoicePath() { return $this->invoicePath; }

    // Setters
    public function SetSubscriptionId($subscriptionId) { $this->subscriptionId = $subscriptionId; }
    public function SetParentId($parentId) { $this->parentId = $parentId; }
    public function SetAmount($amount) { $this->amount = $amount; }
}
