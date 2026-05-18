<?php

/**
 * IpaymentStrategy Interface
 * Defines the contract for payment gateway strategies
 */
interface IpaymentStrategy
{
    /**
     * Process a payment through the gateway
     * @param array $paymentData Payment details
     * @return array Response with payment status and reference
     */
    public function ProcessPayment($paymentData);

    /**
     * Handle webhook payload from payment gateway
     * @param array $payload Webhook payload from gateway
     * @return array Processing result
     */
    public function HandleWebHook($payload);

    /**
     * Generate a unique reference/transaction ID
     * @return string Unique reference number
     */
    public function GenerateReference();

    /**
     * Get the gateway name
     * @return string Gateway identifier
     */
    public function GetGatewayName();

    /**
     * Validate gateway credentials
     * @return bool True if credentials are valid
     */
    public function ValidateCredentials();

    /**
     * Verify a transaction with the gateway
     * @param string $reference Transaction reference
     * @return array Transaction details
     */
    public function VerifyTransaction($reference);

    /**
     * Refund a transaction
     * @param string $reference Transaction reference
     * @param float $amount Amount to refund
     * @return array Refund result
     */
    public function Refund($reference, $amount);
}
