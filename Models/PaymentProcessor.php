<?php

require_once 'Payment.php';
require_once 'FawryStrategy.php';
require_once 'PaymobStrategy.php';

/**
 * PaymentProcessor - Factory and orchestrator for payment processing
 * Manages payment strategy selection and execution
 */
class PaymentProcessor
{
    private static $instance = null;
    private $strategies = [];
    private $defaultGateway = 'Paymob';

    private function __construct()
    {
        $this->registerDefaultStrategies();
    }

    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new PaymentProcessor();
        }
        return self::$instance;
    }

    /**
     * Register default payment strategies
     */
    private function registerDefaultStrategies()
    {
        // Register Fawry strategy
        $fawryStrategy = new FawryStrategy(
            getenv('FAWRY_MERCHANT_CODE') ?: 'YOUR_MERCHANT_CODE',
            getenv('FAWRY_SECURITY_CODE') ?: 'YOUR_SECURITY_CODE'
        );
        $this->registerStrategy('Fawry', $fawryStrategy);

        // Register Paymob strategy
        $paymobStrategy = new PaymobStrategy(
            getenv('PAYMOB_API_KEY') ?: 'YOUR_API_KEY',
            getenv('PAYMOB_INTEGRATION_ID') ?: 'YOUR_INTEGRATION_ID',
            getenv('PAYMOB_IFRAME_ID') ?: 'YOUR_IFRAME_ID'
        );
        $this->registerStrategy('Paymob', $paymobStrategy);
    }

    /**
     * Register a payment strategy
     */
    public function registerStrategy($gatewayName, IpaymentStrategy $strategy)
    {
        $this->strategies[$gatewayName] = $strategy;
    }

    /**
     * Get a payment strategy by gateway name
     */
    public function getStrategy($gatewayName)
    {
        if (!isset($this->strategies[$gatewayName])) {
            throw new Exception("Payment strategy '{$gatewayName}' not found");
        }
        return $this->strategies[$gatewayName];
    }

    /**
     * Get available payment gateways
     */
    public function getAvailableGateways()
    {
        return array_keys($this->strategies);
    }

    /**
     * Check if a gateway is available and credentials are valid
     */
    public function isGatewayAvailable($gatewayName)
    {
        try {
            $strategy = $this->getStrategy($gatewayName);
            return $strategy->ValidateCredentials();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Process payment using specified gateway
     */
    public function processPayment($gatewayName, $paymentData)
    {
        try {
            $strategy = $this->getStrategy($gatewayName);

            if (!$strategy->ValidateCredentials()) {
                return [
                    'status' => 'error',
                    'message' => "Gateway '{$gatewayName}' credentials are invalid",
                    'code' => 'INVALID_CREDENTIALS'
                ];
            }

            // Create payment record and set strategy
            $payment = new Payment();
            $payment->SetPaymentStrategy($strategy);

            // Process payment
            $result = $payment->ProcessPayment($paymentData);

            // Store payment ID in result for reference
            if ($result['status'] === 'success' && $payment->GetPaymentId()) {
                $result['paymentId'] = $payment->GetPaymentId();
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
     * Process payment with fallback strategies
     */
    public function processPaymentWithFallback($primaryGateway, $paymentData, $fallbackGateways = [])
    {
        $gateways = [$primaryGateway];
        if (!empty($fallbackGateways)) {
            $gateways = array_merge($gateways, $fallbackGateways);
        }

        foreach ($gateways as $gatewayName) {
            try {
                $result = $this->processPayment($gatewayName, $paymentData);
                if ($result['status'] === 'success') {
                    return $result;
                }
                error_log("Payment failed with gateway: {$gatewayName}");
            } catch (Exception $e) {
                error_log("Gateway {$gatewayName} error: " . $e->getMessage());
                continue;
            }
        }

        return [
            'status' => 'error',
            'message' => 'Payment failed with all available gateways',
            'code' => 'ALL_GATEWAYS_FAILED'
        ];
    }

    /**
     * Handle webhook from payment gateway
     */
    public function handleWebhook($gatewayName, $payload)
    {
        try {
            $strategy = $this->getStrategy($gatewayName);
            return $strategy->HandleWebHook($payload);
        } catch (Exception $e) {
            error_log("Webhook handling error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Webhook handling failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify transaction with gateway
     */
    public function verifyTransaction($gatewayName, $reference)
    {
        try {
            $strategy = $this->getStrategy($gatewayName);
            return $strategy->VerifyTransaction($reference);
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Transaction verification failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Refund a payment
     */
    public function refundPayment($gatewayName, $reference, $amount)
    {
        try {
            $strategy = $this->getStrategy($gatewayName);
            return $strategy->Refund($reference, $amount);
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Refund failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get payment methods for a specific gateway
     */
    public function getPaymentMethods($gatewayName)
    {
        try {
            $strategy = $this->getStrategy($gatewayName);
            if (method_exists($strategy, 'GetAvailablePaymentMethods')) {
                return $strategy->GetAvailablePaymentMethods();
            }
            return [];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Set default gateway
     */
    public function setDefaultGateway($gatewayName)
    {
        if (isset($this->strategies[$gatewayName])) {
            $this->defaultGateway = $gatewayName;
            return true;
        }
        return false;
    }

    /**
     * Get default gateway
     */
    public function getDefaultGateway()
    {
        return $this->defaultGateway;
    }
}
