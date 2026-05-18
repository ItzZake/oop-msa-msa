<?php

require_once 'IpaymentStrategy.php';

/**
 * FawryStrategy - Implements payment processing via Fawry gateway
 * 
 * API Documentation: https://developer.fawry.com
 * Supported Payment Methods: 
 *   - Fawry Wallet
 *   - Bank Card (Visa/MasterCard)
 *   - Mobile Wallets
 *   - Cash at Fawry Stations
 */
class FawryStrategy implements IpaymentStrategy
{
    private $MerchantCode;
    private $SecurityCode;
    private $base_URL;
    private $referenceExpiry;
    private $chargeUrl = "https://www.atfawry.com/api/v2/charges";
    private $statusUrl = "https://www.atfawry.com/api/v2/charges/status";
    private $refundUrl = "https://www.atfawry.com/api/v2/refunds";

    public function __construct($MerchantCode, $SecurityCode, $base_URL = "https://www.atfawry.com", $referenceExpiry = 60)
    {
        $this->MerchantCode = $MerchantCode;
        $this->SecurityCode = $SecurityCode;
        $this->base_URL = $base_URL;
        $this->referenceExpiry = $referenceExpiry;
    }

    /**
     * Validate gateway credentials
     */
    public function ValidateCredentials()
    {
        if (!$this->MerchantCode || !$this->SecurityCode) {
            return false;
        }
        return true;
    }

    /**
     * Get gateway name
     */
    public function GetGatewayName()
    {
        return "Fawry";
    }

    /**
     * Generate unique reference number
     */
    public function GenerateReference()
    {
        return uniqid('FAW_') . '_' . time();
    }

    /**
     * Process payment via Fawry API
     * 
     * @param array $paymentData Contains:
     *   - amount (required): Payment amount in EGP
     *   - customerPhone (required): Customer phone number
     *   - customerEmail (required): Customer email
     *   - customerName (optional): Customer full name
     *   - orderId (optional): Order/Reference ID
     *   - description (optional): Payment description
     *   - items (optional): Array of item objects
     * 
     * @return array Response with payment status and reference
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
        if (!isset($paymentData['amount']) || !isset($paymentData['customerPhone']) || !isset($paymentData['customerEmail'])) {
            return [
                'status' => 'error',
                'message' => 'Missing required payment data (amount, customerPhone, customerEmail)',
                'code' => 'MISSING_REQUIRED_FIELDS'
            ];
        }

        // Generate reference number
        $referenceNumber = $paymentData['orderId'] ?? $this->GenerateReference();
        $expiryDate = date('Y-m-d H:i:s', strtotime("+{$this->referenceExpiry} minutes"));

        // Build charge request
        $chargeRequest = $this->buildChargeRequest([
            'merchantCode' => $this->MerchantCode,
            'referenceNumber' => $referenceNumber,
            'amount' => (float)$paymentData['amount'],
            'customerName' => $paymentData['customerName'] ?? 'Customer',
            'customerMobile' => $paymentData['customerPhone'],
            'customerEmail' => $paymentData['customerEmail'],
            'description' => $paymentData['description'] ?? 'Payment for subscription',
            'expiryDate' => $expiryDate,
            'items' => $paymentData['items'] ?? [
                [
                    'itemId' => 1,
                    'description' => $paymentData['description'] ?? 'Service',
                    'price' => (float)$paymentData['amount'],
                    'quantity' => 1
                ]
            ]
        ]);

        // Generate signature
        $signature = $this->GenerateSignature(
            $this->MerchantCode,
            $referenceNumber,
            number_format((float)$paymentData['amount'], 2, '.', ''),
            $this->SecurityCode
        );

        // Send charge request to Fawry API
        $response = $this->sendChargeRequest($chargeRequest, $signature);

        if ($response && isset($response['statusCode']) && $response['statusCode'] == 200) {
            return [
                'status' => 'success',
                'message' => 'Payment reference generated successfully',
                'code' => 'REFERENCE_GENERATED',
                'data' => [
                    'referenceNumber' => $referenceNumber,
                    'amount' => $paymentData['amount'],
                    'expiryDate' => $expiryDate,
                    'paymentUrl' => "{$this->base_URL}/pay?" . http_build_query([
                        'ref' => $referenceNumber,
                        'amount' => $paymentData['amount']
                    ]),
                    'gatewayResponse' => $response
                ]
            ];
        }

        return [
            'status' => 'error',
            'message' => $response['statusDescription'] ?? 'Failed to process payment',
            'code' => 'PAYMENT_FAILED',
            'data' => $response
        ];
    }

    /**
     * Verify transaction status with Fawry
     */
    public function VerifyTransaction($reference)
    {
        $payload = [
            'merchantCode' => $this->MerchantCode,
            'referenceNumber' => $reference
        ];

        $signature = $this->GenerateSignature(
            $this->MerchantCode,
            $reference,
            '',
            $this->SecurityCode
        );

        $response = $this->sendRequest($this->statusUrl, $payload, $signature, 'POST');

        if ($response && isset($response['statusCode']) && $response['statusCode'] == 200) {
            return [
                'status' => 'success',
                'transaction' => [
                    'referenceNumber' => $reference,
                    'amount' => $response['amount'] ?? null,
                    'paymentMethod' => $response['paymentMethod'] ?? null,
                    'customerEmail' => $response['customerEmail'] ?? null,
                    'status' => $response['transactionStatus'] ?? 'unknown',
                    'paidAt' => $response['paidAt'] ?? null
                ]
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Failed to verify transaction',
            'code' => 'VERIFICATION_FAILED'
        ];
    }

    /**
     * Handle webhook payload from Fawry
     */
    public function HandleWebHook($payload)
    {
        // Validate webhook signature
        if (!$this->validateFawrySignature($payload)) {
            return [
                'status' => 'error',
                'message' => 'Invalid webhook signature',
                'code' => 'INVALID_SIGNATURE'
            ];
        }

        // Extract transaction details
        $referenceNumber = $payload['referenceNumber'] ?? null;
        $transactionStatus = $payload['transactionStatus'] ?? null;
        $amount = $payload['amount'] ?? null;
        $merchantCode = $payload['merchantCode'] ?? null;

        if (!$referenceNumber || !$transactionStatus) {
            return [
                'status' => 'error',
                'message' => 'Missing required webhook data',
                'code' => 'MISSING_WEBHOOK_DATA'
            ];
        }

        // Verify merchant code matches
        if ($merchantCode !== $this->MerchantCode) {
            return [
                'status' => 'error',
                'message' => 'Merchant code mismatch',
                'code' => 'MERCHANT_MISMATCH'
            ];
        }

        // Determine payment status
        $paymentStatus = 'unknown';
        if ($transactionStatus == 'PAID') {
            $paymentStatus = 'completed';
        } elseif ($transactionStatus == 'UNPAID') {
            $paymentStatus = 'pending';
        } elseif ($transactionStatus == 'EXPIRED') {
            $paymentStatus = 'failed';
        } elseif ($transactionStatus == 'CANCELLED') {
            $paymentStatus = 'cancelled';
        }

        return [
            'status' => 'success',
            'message' => 'Webhook processed successfully',
            'code' => 'WEBHOOK_PROCESSED',
            'data' => [
                'referenceNumber' => $referenceNumber,
                'amount' => $amount,
                'paymentStatus' => $paymentStatus,
                'transactionStatus' => $transactionStatus,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * Refund a transaction
     */
    public function Refund($reference, $amount)
    {
        $payload = [
            'merchantCode' => $this->MerchantCode,
            'referenceNumber' => $reference,
            'refundAmount' => (float)$amount,
            'reason' => 'Refund requested'
        ];

        $signature = $this->GenerateSignature(
            $this->MerchantCode,
            $reference,
            number_format((float)$amount, 2, '.', ''),
            $this->SecurityCode
        );

        $response = $this->sendRequest($this->refundUrl, $payload, $signature, 'POST');

        if ($response && isset($response['statusCode']) && $response['statusCode'] == 200) {
            return [
                'status' => 'success',
                'message' => 'Refund processed successfully',
                'code' => 'REFUND_SUCCESS',
                'data' => [
                    'referenceNumber' => $reference,
                    'refundAmount' => $amount,
                    'refundId' => $response['refundId'] ?? null
                ]
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Refund failed',
            'code' => 'REFUND_FAILED',
            'data' => $response
        ];
    }

    /**
     * Build charge request payload
     */
    private function buildChargeRequest($paymentData)
    {
        return [
            'merchantCode' => $paymentData['merchantCode'],
            'merchantRefNumber' => $paymentData['referenceNumber'],
            'amount' => $paymentData['amount'],
            'customerName' => $paymentData['customerName'],
            'customerMobile' => $paymentData['customerMobile'],
            'customerEmail' => $paymentData['customerEmail'],
            'description' => $paymentData['description'],
            'expiryDate' => $paymentData['expiryDate'],
            'items' => $paymentData['items'],
            'chargeItems' => array_map(function($item) {
                return [
                    'itemId' => $item['itemId'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ];
            }, $paymentData['items'])
        ];
    }

    /**
     * Send charge request to Fawry API
     */
    private function sendChargeRequest($chargeData, $signature)
    {
        $payload = array_merge($chargeData, [
            'signature' => $signature
        ]);

        return $this->sendRequest($this->chargeUrl, $payload, $signature, 'POST');
    }

    /**
     * Generic HTTP request handler
     */
    private function sendRequest($url, $payload, $signature, $method = 'POST')
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        // Add signature to headers or payload depending on Fawry API version
        if (!isset($payload['signature'])) {
            $payload['signature'] = $signature;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
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
            error_log("Fawry API Error: " . $error);
            return null;
        }

        return json_decode($response, true);
    }

    /**
     * Generate SHA256 signature for Fawry API
     */
    private function GenerateSignature($merchantCode, $referenceNumber, $amount, $securityCode)
    {
        $signatureString = $merchantCode . $referenceNumber . $amount . $securityCode;
        return hash('sha256', $signatureString);
    }

    /**
     * Validate Fawry webhook signature
     */
    private function validateFawrySignature($payload)
    {
        if (!isset($payload['signature'])) {
            return false;
        }

        $receivedSignature = $payload['signature'];
        
        $signatureString = $this->MerchantCode . 
                          ($payload['referenceNumber'] ?? '') . 
                          ($payload['amount'] ?? '') . 
                          $this->SecurityCode;
        
        $expectedSignature = hash('sha256', $signatureString);

        return hash_equals($expectedSignature, $receivedSignature);
    }

    /**
     * Format amount to required format
     */
    private function FormatAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }
}
