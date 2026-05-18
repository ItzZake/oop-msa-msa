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
        $sql = "SELECT * FROM Payments WHERE PaymentID = ?";
        $params = [$paymentId];
        $result = Database::getInstance()->fetchOne($sql, $params);

        if ($result) {
            $this->paymentId = $result['PaymentID'];
            $this->subscriptionId = $result['SubscriptionID'];
            $this->amount = $result['Amount'];
            $this->parentId = $result['ParentID'];
            $this->gateway = $result['Gateway'];
            $this->gatewayTXId = $result['GatewayTXID'];
            $this->status = $result['Status'];
            $this->paidAt = $result['PaidAt'];
            $this->invoicePath = $result['InvoicePath'];
            $this->createdAt = $result['CreatedAt'];
            $this->updatedAt = $result['UpdatedAt'];
            $this->loadLineItems();
        }
    }

    /**
     * Load line items for this payment
     */
    private function loadLineItems()
    {
        $sql = "SELECT * FROM PaymentLineItems WHERE PaymentID = ?";
        $params = [$this->paymentId];
        $this->lineItems = Database::getInstance()->fetchAll($sql, $params) ?? [];
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
        $sql = "INSERT INTO Payments (SubscriptionID, ParentID, Amount, Gateway, Status, CreatedAt, UpdatedAt) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $paymentData['subscriptionId'] ?? null,
            $paymentData['parentId'] ?? null,
            $paymentData['amount'],
            $this->gateway,
            'Pending',
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ];

        $stmt = Database::getInstance()->query($sql, $params);
        if ($stmt && $stmt->rowCount() > 0) {
            $this->paymentId = Database::getInstance()->getConnection()->lastInsertId();
        }

        // Store line items if provided
        if (isset($paymentData['items']) && is_array($paymentData['items'])) {
            foreach ($paymentData['items'] as $item) {
                $this->AddLineItem($item['description'] ?? '', $item['price'] ?? 0);
            }
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
        $this->status = 'Completed';
        $this->paidAt = $paidAt ?? date('Y-m-d H:i:s');
        if ($gatewayTXId) {
            $this->gatewayTXId = $gatewayTXId;
        }

        $sql = "UPDATE Payments SET Status = 'Completed', GatewayTXID = ?, PaidAt = ?, UpdatedAt = ? WHERE PaymentID = ?";
        $params = [$this->gatewayTXId, $this->paidAt, date('Y-m-d H:i:s'), $this->paymentId];
        $stmt = Database::getInstance()->query($sql, $params);

        return $stmt && $stmt->rowCount() > 0;
    }

    /**
     * Mark payment as failed
     */
    public function MarkFailed($reason = null)
    {
        $this->status = 'Failed';

        $sql = "UPDATE Payments SET Status = 'Failed', UpdatedAt = ? WHERE PaymentID = ?";
        $params = [date('Y-m-d H:i:s'), $this->paymentId];
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

        $sql = "UPDATE Payments SET Status = 'Refunded', UpdatedAt = ? WHERE PaymentID = ?";
        $params = [date('Y-m-d H:i:s'), $this->paymentId];
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

    /**
     * Update payment status based on gateway response
     */
    private function UpdatePaymentStatus($reference, $paymentStatus, $transactionStatus = null)
    {
        $statusMap = [
            'completed' => 'Completed',
            'pending' => 'Pending',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];

        $newStatus = $statusMap[$paymentStatus] ?? 'Unknown';

        $sql = "UPDATE Payments SET Status = ?, UpdatedAt = ? WHERE PaymentID = ?";
        $params = [$newStatus, date('Y-m-d H:i:s'), $this->paymentId];
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

        $sql = "UPDATE Payments SET InvoicePath = ? WHERE PaymentID = ?";
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
        if (!$this->paymentId) {
            $this->lineItems[] = [
                'description' => $description,
                'amount' => $amount
            ];
            return true;
        }

        $sql = "INSERT INTO PaymentLineItems (PaymentID, Description, Amount) 
                VALUES (?, ?, ?)";
        $params = [$this->paymentId, $description, $amount];
        $stmt = Database::getInstance()->query($sql, $params);

        if ($stmt && $stmt->rowCount() > 0) {
            $this->lineItems[] = [
                'description' => $description,
                'amount' => $amount
            ];
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
        $sql = "SELECT * FROM Payments WHERE SubscriptionID = ? ORDER BY CreatedAt DESC";
        $params = [$subscriptionId];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    /**
     * Get all payments for a parent/user
     */
    public static function GetPaymentsByParent($parentId)
    {
        $sql = "SELECT * FROM Payments WHERE ParentID = ? ORDER BY CreatedAt DESC";
        $params = [$parentId];
        return Database::getInstance()->fetchAll($sql, $params);
    }

    /**
     * Get payment by reference number
     */
    public static function GetPaymentByReference($reference)
    {
        $sql = "SELECT * FROM Payments WHERE PaymentID = ? OR GatewayTXID = ?";
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
