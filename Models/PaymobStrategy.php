<?php

require_once 'IpaymentStrategy.php';

/**
 * PaymobStrategy - Implements payment processing via Paymob gateway
 * 
 * API Documentation: https://docs.paymob.com
 * Supported Payment Methods:
 *   - Credit/Debit Cards (Visa, MasterCard, Maestro)
 *   - Mobile Wallets (Vodafone Cash, Orange Money, Etisalat Cash)
 *   - Bank Installments
 *   - BNPL (Buy Now Pay Later)
 */
class PaymobStrategy implements IpaymentStrategy
{
    private $apiKey;
    private $integrationId;
    private $iframeId;
    private $apiUrl = "https://accept.paymob.com/api";
    private $authTokenUrl = "/auth/tokens";
    private $orderUrl = "/ecommerce/orders";
    private $paymentKeyUrl = "/acceptance/payment_keys";
    private $transactionCheckUrl = "/acceptance/transactions";
    private $refundUrl = "/acceptance/refunds";
    private $token = null;

    public function __construct($apiKey, $integrationId, $iframeId = null)
    {
        $this->apiKey = $apiKey;
        $this->integrationId = $integrationId;
        $this->iframeId = $iframeId;
    }

    /**
     * Validate gateway credentials
     */
    public function ValidateCredentials()
    {
        if (!$this->apiKey || !$this->integrationId) {
            return false;
        }

        // Try to authenticate to verify credentials
        $token = $this->getAuthToken();
        return !empty($token);
    }

    /**
     * Get gateway name
     */
    public function GetGatewayName()
    {
        return "Paymob";
    }

    /**
     * Generate unique reference number
     */
    public function GenerateReference()
    {
        return uniqid('PAYMOB_') . '_' . time();
    }

    /**
     * Process payment via Paymob API
     * 
     * @param array $paymentData Contains:
     *   - amount (required): Payment amount in EGP (in cents)
     *   - currency (optional): Currency code (default: EGP)
     *   - customerName (required): Customer full name
     *   - customerEmail (required): Customer email
     *   - customerPhone (required): Customer phone
     *   - orderId (optional): Order/Reference ID
     *   - items (optional): Array of items in order
     *   - description (optional): Order description
     * 
     * @return array Response with payment URL and reference
     */
    public function ProcessPayment($paymentData)
    {
        if (!$this->ValidateCredentials()) {
            return [
                'status' => 'error',
                'message' => 'Invalid gateway credentials',
                'code' => 'INVALID_CREDENTIALS'
            ];
        }

        // Validate required fields
        $required = ['amount', 'customerName', 'customerEmail', 'customerPhone'];
        foreach ($required as $field) {
            if (!isset($paymentData[$field])) {
                return [
                    'status' => 'error',
                    'message' => "Missing required field: {$field}",
                    'code' => 'MISSING_REQUIRED_FIELDS'
                ];
            }
        }

        // Authenticate and get token
        $this->token = $this->getAuthToken();
        if (!$this->token) {
            return [
                'status' => 'error',
                'message' => 'Failed to authenticate with Paymob',
                'code' => 'AUTH_FAILED'
            ];
        }

        try {
            // Step 1: Create Order
            $orderId = $paymentData['orderId'] ?? $this->GenerateReference();
            $orderResponse = $this->CreateOrder(
                $orderId,
                (int)$paymentData['amount'],
                $paymentData['items'] ?? [],
                $paymentData['description'] ?? 'Order payment'
            );

            if (!isset($orderResponse['id'])) {
                throw new Exception('Failed to create order: ' . json_encode($orderResponse));
            }

            $paymobOrderId = $orderResponse['id'];

            // Step 2: Create Payment Key
            $paymentKeyResponse = $this->CreatePaymentKey(
                $paymobOrderId,
                (int)$paymentData['amount'],
                $paymentData['customerEmail'],
                $paymentData['customerPhone'],
                $paymentData['customerName'],
                $this->buildBillingData($paymentData)
            );

            if (!isset($paymentKeyResponse['token'])) {
                throw new Exception('Failed to create payment key: ' . json_encode($paymentKeyResponse));
            }

            $paymentToken = $paymentKeyResponse['token'];

            // Step 3: Generate Payment URL
            $paymentUrl = $this->generatePaymentUrl($paymentToken);

            return [
                'status' => 'success',
                'message' => 'Payment initialized successfully',
                'code' => 'PAYMENT_INITIALIZED',
                'data' => [
                    'referenceNumber' => $orderId,
                    'orderId' => $paymobOrderId,
                    'paymentToken' => $paymentToken,
                    'paymentUrl' => $paymentUrl,
                    'amount' => $paymentData['amount'],
                    'currency' => $paymentData['currency'] ?? 'EGP',
                    'iframeUrl' => "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentToken}",
                ]
            ];

        } catch (Exception $e) {
            error_log("Paymob ProcessPayment Error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 'PAYMENT_PROCESSING_ERROR'
            ];
        }
    }

    /**
     * Verify transaction status
     */
    public function VerifyTransaction($reference)
    {
        $this->token = $this->getAuthToken();
        if (!$this->token) {
            return [
                'status' => 'error',
                'message' => 'Failed to authenticate',
                'code' => 'AUTH_FAILED'
            ];
        }

        // Paymob requires transaction ID, not reference number
        // This is a simplified implementation
        $url = $this->apiUrl . $this->transactionCheckUrl;
        $response = $this->sendRequest($url, [], 'GET');

        if ($response && isset($response['data'])) {
            foreach ($response['data'] as $transaction) {
                if ($transaction['merchant_order_id'] == $reference && $transaction['success']) {
                    return [
                        'status' => 'success',
                        'transaction' => [
                            'referenceNumber' => $reference,
                            'transactionId' => $transaction['id'],
                            'amount' => $transaction['amount_cents'] / 100,
                            'currency' => $transaction['currency'],
                            'paymentMethod' => $transaction['source_data']['type'] ?? 'unknown',
                            'status' => 'completed',
                            'paidAt' => $transaction['created_at'] ?? null
                        ]
                    ];
                }
            }
        }

        return [
            'status' => 'error',
            'message' => 'Transaction not found or not successful',
            'code' => 'TRANSACTION_NOT_FOUND'
        ];
    }

    /**
     * Handle webhook payload from Paymob
     * 
     * Paymob sends an HMAC SHA256 signature for validation
     */
    public function HandleWebHook($payload)
    {
        // Validate webhook signature
        if (!$this->ValidateHMACSignature($payload)) {
            return [
                'status' => 'error',
                'message' => 'Invalid webhook signature',
                'code' => 'INVALID_SIGNATURE'
            ];
        }

        $merchantOrderId = $payload['obj']['merchant_order_id'] ?? null;
        $transactionId = $payload['obj']['id'] ?? null;
        $success = $payload['obj']['success'] ?? false;
        $amountCents = $payload['obj']['amount_cents'] ?? null;
        $paymentMethod = $payload['obj']['source_data']['type'] ?? 'unknown';

        if (!$merchantOrderId) {
            return [
                'status' => 'error',
                'message' => 'Missing merchant order ID in webhook',
                'code' => 'MISSING_ORDER_ID'
            ];
        }

        // Determine payment status based on success flag
        $paymentStatus = $success ? 'completed' : 'failed';

        return [
            'status' => 'success',
            'message' => 'Webhook processed successfully',
            'code' => 'WEBHOOK_PROCESSED',
            'data' => [
                'referenceNumber' => $merchantOrderId,
                'transactionId' => $transactionId,
                'amount' => $amountCents ? $amountCents / 100 : null,
                'paymentStatus' => $paymentStatus,
                'paymentMethod' => $paymentMethod,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * Refund a transaction
     */
    public function Refund($reference, $amount)
    {
        $this->token = $this->getAuthToken();
        if (!$this->token) {
            return [
                'status' => 'error',
                'message' => 'Failed to authenticate',
                'code' => 'AUTH_FAILED'
            ];
        }

        // Note: Paymob requires transaction ID for refunds, not merchant order ID
        // This is a simplified implementation
        $url = $this->apiUrl . $this->refundUrl;
        $payload = [
            'transaction_id' => $reference,
            'amount_cents' => (int)($amount * 100)
        ];

        $response = $this->sendRequest($url, $payload, 'POST');

        if ($response && isset($response['id'])) {
            return [
                'status' => 'success',
                'message' => 'Refund processed successfully',
                'code' => 'REFUND_SUCCESS',
                'data' => [
                    'refundId' => $response['id'],
                    'amount' => $amount,
                    'status' => $response['status'] ?? 'pending'
                ]
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Refund failed',
            'code' => 'REFUND_FAILED',
            'data' => $response ?? null
        ];
    }

    /**
     * Get authentication token from Paymob
     */
    private function getAuthToken()
    {
        if ($this->token) {
            return $this->token;
        }

        $url = $this->apiUrl . $this->authTokenUrl;
        $payload = [
            'api_key' => $this->apiKey
        ];

        $response = $this->sendRequest($url, $payload, 'POST');

        if ($response && isset($response['token'])) {
            $this->token = $response['token'];
            return $this->token;
        }

        return null;
    }

    /**
     * Create an order with Paymob
     */
    private function CreateOrder($merchantOrderId, $amountCents, $items = [], $description = '')
    {
        $url = $this->apiUrl . $this->orderUrl;
        
        $payload = [
            'auth_token' => $this->token,
            'merchant_order_id' => $merchantOrderId,
            'amount_cents' => $amountCents,
            'currency' => 'EGP',
            'items' => $this->formatOrderItems($items, $amountCents),
            'shipping_data' => [
                'apartment' => 'NA',
                'email' => 'NA@NA.com',
                'floor' => 'NA',
                'first_name' => 'NA',
                'last_name' => 'NA',
                'phone_number' => 'NA',
                'postal_code' => 'NA',
                'extra_description' => $description,
                'city' => 'NA',
                'country' => 'EG',
                'state' => 'NA'
            ]
        ];

        return $this->sendRequest($url, $payload, 'POST');
    }

    /**
     * Create payment key for Paymob
     */
    private function CreatePaymentKey($orderId, $amountCents, $customerEmail, $customerPhone, $customerName, $billingData)
    {
        $url = $this->apiUrl . $this->paymentKeyUrl;

        $payload = [
            'auth_token' => $this->token,
            'order_id' => $orderId,
            'amount_cents' => $amountCents,
            'expiration' => 3600, // 1 hour
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => $this->integrationId
        ];

        return $this->sendRequest($url, $payload, 'POST');
    }

    /**
     * Format items for Paymob order
     */
    private function formatOrderItems($items, $amountCents)
    {
        if (empty($items)) {
            return [
                [
                    'name' => 'Payment',
                    'amount_cents' => $amountCents,
                    'description' => 'Order payment',
                    'quantity' => 1
                ]
            ];
        }

        return array_map(function($item) {
            return [
                'name' => $item['name'] ?? $item['description'] ?? 'Item',
                'amount_cents' => (int)($item['price'] * 100 ?? 0),
                'description' => $item['description'] ?? '',
                'quantity' => (int)($item['quantity'] ?? 1)
            ];
        }, $items);
    }

    /**
     * Build billing data for payment
     */
    private function buildBillingData($paymentData)
    {
        // Extract phone number for billing
        $phone = $paymentData['customerPhone'] ?? '';
        // Remove any non-numeric characters except leading +
        $phone = preg_replace('/[^\d+]/', '', $phone);

        return [
            'apartment' => $paymentData['apartment'] ?? 'NA',
            'email' => $paymentData['customerEmail'],
            'floor' => $paymentData['floor'] ?? 'NA',
            'first_name' => explode(' ', $paymentData['customerName'])[0] ?? 'Customer',
            'last_name' => explode(' ', $paymentData['customerName'])[1] ?? 'User',
            'phone_number' => $phone,
            'postal_code' => $paymentData['postalCode'] ?? '00000',
            'city' => $paymentData['city'] ?? 'Cairo',
            'country' => 'EG',
            'state' => $paymentData['state'] ?? 'Cairo',
            'extra_description' => $paymentData['description'] ?? ''
        ];
    }

    /**
     * Generate payment URL for Paymob iframe
     */
    private function generatePaymentUrl($paymentToken)
    {
        return "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentToken}";
    }

    /**
     * Send HTTP request to Paymob API
     */
    private function sendRequest($url, $payload = [], $method = 'POST')
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        if (is_object($ch) && method_exists($ch, 'close')) {
            $ch->close();
        } else {
            curl_close($ch);
        }

        if ($error) {
            error_log("Paymob API Error: " . $error);
            return null;
        }

        return json_decode($response, true);
    }

    /**
     * Validate HMAC signature from Paymob webhook
     * 
     * Paymob sends: hmac = SHA256(amount_cents + order_id + integration_id + success + is_refunded + is_captured + is_voided + is_settled + merchant_order_id + transaction_id + token | api_key)
     */
    private function ValidateHMACSignature($payload)
    {
        if (!isset($payload['hmac'])) {
            return false;
        }

        $receivedHmac = $payload['hmac'];
        $obj = $payload['obj'] ?? [];

        // Build the data string in the exact order Paymob expects
        $dataString = implode('', [
            $obj['amount_cents'] ?? '',
            $obj['order_id'] ?? '',
            $obj['integration_id'] ?? '',
            $obj['success'] ? '1' : '0',
            $obj['is_refunded'] ? '1' : '0',
            $obj['is_captured'] ? '1' : '0',
            $obj['is_voided'] ? '1' : '0',
            $obj['is_settled'] ? '1' : '0',
            $obj['merchant_order_id'] ?? '',
            $obj['transaction_id'] ?? '',
            $obj['token'] ?? ''
        ]);

        // Calculate expected HMAC
        $expectedHmac = hash_hmac('sha256', $dataString, $this->apiKey);

        return hash_equals($expectedHmac, $receivedHmac);
    }

    /**
     * Get available payment methods
     */
    public function GetAvailablePaymentMethods()
    {
        return [
            'cards' => ['visa', 'mastercard', 'maestro'],
            'wallets' => ['vodafone_cash', 'orange_money', 'etisalat_cash'],
            'installments' => true,
            'bnpl' => true
        ];
    }
}
