<?php

/**
 * PAYMENT GATEWAY INTEGRATION GUIDE
 * ==================================
 * 
 * This system implements the Strategy Pattern to support multiple payment gateways
 * (Fawry and Paymob) with a flexible, extensible architecture.
 * 
 * ================================================
 * ARCHITECTURE OVERVIEW:
 * ================================================
 * 
 * 1. IpaymentStrategy (Interface)
 *    - Defines contract for all payment gateway implementations
 *    - Methods: ProcessPayment, HandleWebHook, VerifyTransaction, Refund, etc.
 * 
 * 2. Concrete Strategies:
 *    - FawryStrategy: Implements Fawry payment gateway
 *    - PaymobStrategy: Implements Paymob payment gateway
 * 
 * 3. Payment (Model)
 *    - Manages payment records and uses strategy pattern
 *    - Delegates processing to selected strategy
 * 
 * 4. PaymentProcessor (Factory/Orchestrator)
 *    - Singleton factory for managing strategies
 *    - Handles strategy selection and fallback
 * 
 * ================================================
 * SETUP CONFIGURATION:
 * ================================================
 * 
 * Add these environment variables to your .env or configuration:
 * 
 * FAWRY CONFIGURATION:
 *   FAWRY_MERCHANT_CODE=your_merchant_code
 *   FAWRY_SECURITY_CODE=your_security_code
 * 
 * PAYMOB CONFIGURATION:
 *   PAYMOB_API_KEY=your_api_key
 *   PAYMOB_INTEGRATION_ID=your_integration_id
 *   PAYMOB_IFRAME_ID=your_iframe_id
 * 
 * ================================================
 * USAGE EXAMPLES:
 * ================================================
 * 
 * 1. BASIC PAYMENT PROCESSING:
 * 
 *    $processor = PaymentProcessor::getInstance();
 *    
 *    $paymentData = [
 *        'amount' => 100.00,  // Amount in EGP
 *        'customerName' => 'Ahmed Hassan',
 *        'customerEmail' => 'ahmed@example.com',
 *        'customerPhone' => '+201234567890',
 *        'description' => 'Monthly Subscription',
 *        'subscriptionId' => 123,
 *        'parentId' => 456
 *    ];
 *    
 *    $result = $processor->processPayment('Paymob', $paymentData);
 *    
 *    if ($result['status'] === 'success') {
 *        $paymentUrl = $result['data']['paymentUrl'];
 *        // Redirect user to payment page
 *        header("Location: " . $paymentUrl);
 *    } else {
 *        echo "Payment failed: " . $result['message'];
 *    }
 * 
 * 2. CHECK AVAILABLE GATEWAYS:
 * 
 *    $processor = PaymentProcessor::getInstance();
 *    $gateways = $processor->getAvailableGateways();
 *    // Returns: ['Fawry', 'Paymob']
 * 
 * 3. VALIDATE GATEWAY CREDENTIALS:
 * 
 *    $processor = PaymentProcessor::getInstance();
 *    $isValid = $processor->isGatewayAvailable('Paymob');
 * 
 * 4. PROCESS WITH FALLBACK GATEWAY:
 * 
 *    $processor = PaymentProcessor::getInstance();
 *    
 *    $result = $processor->processPaymentWithFallback(
 *        'Paymob',  // Primary gateway
 *        $paymentData,
 *        ['Fawry']  // Fallback gateway
 *    );
 * 
 * 5. HANDLE WEBHOOK FROM PAYMENT GATEWAY:
 * 
 *    // In your webhook endpoint (e.g., webhook.php)
 *    $payload = json_decode(file_get_contents('php://input'), true);
 *    
 *    $processor = PaymentProcessor::getInstance();
 *    $result = $processor->handleWebhook('Paymob', $payload);
 *    
 *    if ($result['status'] === 'success') {
 *        $data = $result['data'];
 *        $paymentStatus = $data['paymentStatus'];  // 'completed', 'failed', etc.
 *        $referenceNumber = $data['referenceNumber'];
 *        
 *        // Update payment status in database
 *        $payment = new Payment();
 *        // ... update logic
 *    }
 * 
 * 6. VERIFY TRANSACTION:
 * 
 *    $processor = PaymentProcessor::getInstance();
 *    $result = $processor->verifyTransaction('Paymob', 'PAYMOB_123456');
 *    
 *    if ($result['status'] === 'success') {
 *        $transaction = $result['transaction'];
 *        echo "Payment amount: " . $transaction['amount'];
 *        echo "Payment status: " . $transaction['status'];
 *    }
 * 
 * 7. REFUND A PAYMENT:
 * 
 *    $processor = PaymentProcessor::getInstance();
 *    $result = $processor->refundPayment('Paymob', 'transaction_id', 50.00);
 *    
 *    if ($result['status'] === 'success') {
 *        echo "Refund successful: " . $result['data']['refundId'];
 *    }
 * 
 * 8. GET PAYMENT METHODS FOR GATEWAY:
 * 
 *    $processor = PaymentProcessor::getInstance();
 *    $methods = $processor->getPaymentMethods('Paymob');
 *    
 *    // Paymob returns:
 *    // [
 *    //   'cards' => ['visa', 'mastercard', 'maestro'],
 *    //   'wallets' => ['vodafone_cash', 'orange_money', 'etisalat_cash'],
 *    //   'installments' => true,
 *    //   'bnpl' => true
 *    // ]
 * 
 * ================================================
 * PAYMENT FLOW EXAMPLES:
 * ================================================
 * 
 * FAWRY PAYMENT FLOW:
 * 1. Create payment reference
 * 2. Show Fawry payment page (QR code, form, etc.)
 * 3. Customer completes payment
 * 4. Receive webhook with payment status
 * 5. Update payment record
 * 6. Deliver service/send confirmation
 * 
 * PAYMOB PAYMENT FLOW:
 * 1. Create order in Paymob
 * 2. Get payment token
 * 3. Embed payment form in iframe or redirect to payment URL
 * 4. Customer submits card/wallet information
 * 5. Receive webhook with transaction result
 * 6. Verify transaction (optional)
 * 7. Update payment record
 * 8. Deliver service/send confirmation
 * 
 * ================================================
 * WEBHOOK ENDPOINTS EXAMPLE:
 * ================================================
 * 
 * Create webhook handlers for each gateway:
 * 
 * /api/webhooks/fawry-webhook.php:
 * ```php
 * require_once 'Models/PaymentProcessor.php';
 * $payload = json_decode(file_get_contents('php://input'), true);
 * $processor = PaymentProcessor::getInstance();
 * $result = $processor->handleWebhook('Fawry', $payload);
 * echo json_encode(['status' => 'received']);
 * ```
 * 
 * /api/webhooks/paymob-webhook.php:
 * ```php
 * require_once 'Models/PaymentProcessor.php';
 * $payload = json_decode(file_get_contents('php://input'), true);
 * $processor = PaymentProcessor::getInstance();
 * $result = $processor->handleWebhook('Paymob', $payload);
 * echo json_encode(['status' => 'received']);
 * ```
 * 
 * Configure these URLs in your gateway dashboards:
 * - Fawry: https://yoursite.com/api/webhooks/fawry-webhook.php
 * - Paymob: https://yoursite.com/api/webhooks/paymob-webhook.php
 * 
 * ================================================
 * ERROR HANDLING:
 * ================================================
 * 
 * All methods return a standard response format:
 * 
 * SUCCESS RESPONSE:
 * {
 *   'status': 'success',
 *   'message': 'Descriptive message',
 *   'code': 'SUCCESS_CODE',
 *   'data': {
 *     'referenceNumber': 'unique_ref',
 *     'paymentUrl': 'https://...',
 *     'amount': 100.00,
 *     ...
 *   }
 * }
 * 
 * ERROR RESPONSE:
 * {
 *   'status': 'error',
 *   'message': 'Error description',
 *   'code': 'ERROR_CODE',
 *   'data': { ... }
 * }
 * 
 * Common error codes:
 * - INVALID_CREDENTIALS: Gateway credentials are invalid
 * - MISSING_REQUIRED_FIELDS: Required payment data is missing
 * - PAYMENT_FAILED: Payment processing failed
 * - WEBHOOK_PROCESSED: Webhook received and processed
 * - REFUND_SUCCESS: Refund completed
 * - REFUND_FAILED: Refund failed
 * - TRANSACTION_NOT_FOUND: Transaction verification failed
 * 
 * ================================================
 * PAYMENT DATABASE TABLES:
 * ================================================
 * 
 * CREATE TABLE Payments (
 *   PaymentID INT PRIMARY KEY AUTO_INCREMENT,
 *   SubscriptionID INT,
 *   ParentID INT,
 *   Amount DECIMAL(10, 2),
 *   Gateway VARCHAR(50),
 *   GatewayTXID VARCHAR(255),
 *   Status VARCHAR(50), -- Pending, Completed, Failed, Cancelled, Refunded
 *   PaidAt DATETIME,
 *   InvoicePath VARCHAR(255),
 *   CreatedAt DATETIME,
 *   UpdatedAt DATETIME,
 *   FOREIGN KEY (SubscriptionID) REFERENCES Subscriptions(SubscriptionID),
 *   FOREIGN KEY (ParentID) REFERENCES Parents(ParentID)
 * );
 * 
 * CREATE TABLE PaymentLineItems (
 *   LineItemID INT PRIMARY KEY AUTO_INCREMENT,
 *   PaymentID INT,
 *   Description VARCHAR(255),
 *   Amount DECIMAL(10, 2),
 *   FOREIGN KEY (PaymentID) REFERENCES Payments(PaymentID)
 * );
 * 
 * ================================================
 * EXTENDING WITH CUSTOM GATEWAY:
 * ================================================
 * 
 * 1. Create new gateway class:
 * 
 *    class CustomGatewayStrategy implements IpaymentStrategy {
 *        public function ProcessPayment($paymentData) { ... }
 *        public function HandleWebHook($payload) { ... }
 *        public function VerifyTransaction($reference) { ... }
 *        public function Refund($reference, $amount) { ... }
 *        public function GenerateReference() { ... }
 *        public function GetGatewayName() { ... }
 *        public function ValidateCredentials() { ... }
 *    }
 * 
 * 2. Register with processor:
 * 
 *    $processor = PaymentProcessor::getInstance();
 *    $customGateway = new CustomGatewayStrategy(...);
 *    $processor->registerStrategy('CustomGateway', $customGateway);
 * 
 * 3. Use like any other gateway:
 * 
 *    $result = $processor->processPayment('CustomGateway', $paymentData);
 * 
 */

// Example usage:
/*
// Initialize payment processor
require_once 'Models/PaymentProcessor.php';

// Get processor instance
$processor = PaymentProcessor::getInstance();

// Define payment data
$paymentData = [
    'amount' => 100.00,
    'customerName' => 'Ahmed Hassan',
    'customerEmail' => 'ahmed@example.com',
    'customerPhone' => '+201234567890',
    'description' => 'Monthly Subscription',
    'items' => [
        ['name' => 'Subscription', 'price' => 100.00, 'quantity' => 1]
    ]
];

// Process payment
$result = $processor->processPayment('Paymob', $paymentData);

// Handle response
if ($result['status'] === 'success') {
    $paymentData = $result['data'];
    echo "Payment URL: " . $paymentData['paymentUrl'];
} else {
    echo "Error: " . $result['message'];
}
*/
