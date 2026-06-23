<?php

namespace App\Services;

use Config\Services;

/**
 * Midtrans Payment Service
 * Handle all Midtrans API interactions (Snap Token, Webhooks, etc.)
 */
class MidtransService
{
    private $serverKey;
    private $clientKey;
    private $isProduction;
    private $baseUrl;

    public function __construct()
    {
        // Load Midtrans configuration from environment
        $this->serverKey = getenv('MIDTRANS_SERVER_KEY') ?: '';
        $this->clientKey = getenv('MIDTRANS_CLIENT_KEY') ?: '';
        $this->isProduction = getenv('MIDTRANS_IS_PRODUCTION') === 'true';
        $this->baseUrl = $this->isProduction
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }

    /**
     * Get Snap token for payment
     *
     * @param array $params Payment parameters
     * @return array Result with snap_token and order_id
     */
    public function getSnapToken(array $params): array
    {
        $orderId = $params['order_id'] ?? $this->generateOrderId();
        $grossAmount = $params['gross_amount'] ?? 0;
        $customerDetails = $params['customer_details'] ?? [];

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $grossAmount
            ],
            'customer_details' => $customerDetails,
            'enabled_payments' => ['gopay', 'bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'credit_card', 'qris'],
            'credit_card' => [
                'secure' => true
            ],
            'gopay' => [
                'enable_callback' => true
            ]
        ];

        // Add item details if provided
        if (!empty($params['item_details'])) {
            $payload['item_details'] = $params['item_details'];
        }

        return $this->postToMidtrans('/v2charge', $payload, 'POST');
    }

    /**
     * Get Snap redirect URL
     *
     * @param array $params Payment parameters
     * @return array Result with redirect_url
     */
    public function getSnapRedirectUrl(array $params): array
    {
        $orderId = $params['order_id'] ?? $this->generateOrderId();
        $grossAmount = $params['gross_amount'] ?? 0;
        $customerDetails = $params['customer_details'] ?? [];

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $grossAmount
            ],
            'customer_details' => $customerDetails,
            'vt_web' => [
                'finish_redirect_url' => base_url('/payment/finish'),
                'unfinish_redirect_url' => base_url('/payment/unfinish'),
                'error_redirect_url' => base_url('/payment/error')
            ]
        ];

        return $this->postToMidtrans('/v2/snap/transactions', $payload, 'POST');
    }

    /**
     * Handle Midtrans notification (webhook)
     *
     * @param array $notification Body from Midtrans
     * @return array Parsed notification data
     */
    public function handleNotification(array $notification): array
    {
        // Verify notification authenticity
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';

        // Verify signature
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        if ($signatureKey !== $expectedSignature) {
            throw new \Exception('Invalid notification signature');
        }

        return [
            'order_id' => $orderId,
            'status' => $this->mapTransactionStatus($notification['transaction_status'] ?? ''),
            'payment_type' => $notification['payment_type'] ?? '',
            'transaction_time' => $notification['transaction_time'] ?? '',
            'gross_amount' => $notification['gross_amount'] ?? 0,
            'fraud_status' => $notification['fraud_status'] ?? ''
        ];
    }

    /**
     * Get transaction status from Midtrans
     *
     * @param string $orderId Order ID
     * @return array Transaction status
     */
    public function getTransactionStatus(string $orderId): array
    {
        return $this->getFromMidtrans('/v2/' . $orderId . '/status');
    }

    /**
     * Map Midtrans status to our database status
     *
     * @param string $transactionStatus Midtrans transaction status
     * @return string Our database status
     */
    private function mapTransactionStatus(string $transactionStatus): string
    {
        $statusMap = [
            'capture' => 'paid',      // Credit card capture
            'settlement' => 'paid',   // Payment successful
            'pending' => 'pending',   // Waiting for payment
            'deny' => 'failed',      // Payment denied
            'cancel' => 'failed',    // Payment cancelled
            'expire' => 'failed',    // Payment expired
            'refund' => 'refunded'   // Payment refunded
        ];

        return $statusMap[$transactionStatus] ?? 'pending';
    }

    /**
     * Generate unique order ID
     *
     * @return string Order ID in format ZVD-YYYYMMDD-XXXX
     */
    public function generateOrderId(): string
    {
        $date = date('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return "ZVD-{$date}-{$random}";
    }

    /**
     * Make POST request to Midtrans API
     *
     * @param string $endpoint API endpoint
     * @param array $data Request body
     * @param string $method HTTP method
     * @return array API response
     */
    private function postToMidtrans(string $endpoint, array $data, string $method = 'POST'): array
    {
        $ch = curl_init($this->baseUrl . $endpoint);

        curl_setopt_array($ch, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->serverKey . ':')
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception('Midtrans API Error: ' . $error);
        }

        $result = json_decode($response, true);

        if ($httpCode >= 400) {
            throw new \Exception('Midtrans API Error: ' . ($result['status_message'] ?? 'Unknown error'));
        }

        return $result;
    }

    /**
     * Make GET request to Midtrans API
     *
     * @param string $endpoint API endpoint
     * @return array API response
     */
    private function getFromMidtrans(string $endpoint): array
    {
        $ch = curl_init($this->baseUrl . $endpoint);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->serverKey . ':')
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception('Midtrans API Error: ' . $error);
        }

        $result = json_decode($response, true);

        if ($httpCode >= 400) {
            throw new \Exception('Midtrans API Error: ' . ($result['status_message'] ?? 'Unknown error'));
        }

        return $result;
    }

    /**
     * Get Snap JavaScript URL
     *
     * @return string Snap.js URL
     */
    public function getSnapJsUrl(): string
    {
        if ($this->isProduction) {
            return 'https://app.midtrans.com/snap/snap.js';
        }
        return 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    /**
     * Get client key for Snap.js
     *
     * @return string Client key
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    /**
     * Check if Midtrans is configured
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty($this->serverKey) && !empty($this->clientKey);
    }
}