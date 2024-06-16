<?php
namespace App\Services;

use App\Http\Controllers\AuthController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getAccessToken()
    {
        try {
            // Using AuthController's getToken method to fetch access token
            $response = app(AuthController::class)->getToken(new Request());

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getContent(), true);
                return $data['access_token'];
            } else {
                throw new \Exception('Failed to fetch M-Pesa access token');
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch M-Pesa access token: ' . $e->getMessage());
            throw new \Exception('Failed to fetch M-Pesa access token');
        }
    }

    public function initiatePayment($amount, $phone, $invoiceId)
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = $this->client->request('POST', env('MPESA_PAYMENT_URL'), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'BusinessShortCode' => env('MPESA_BUSINESS_SHORTCODE'),
                    'Password' => base64_encode(env('MPESA_BUSINESS_SHORTCODE') . env('MPESA_PASSWORD') . now()->format('YmdHis')),
                    'Timestamp' => now()->format('YmdHis'),
                    'TransactionType' => 'CustomerPayBillOnline',
                    'Amount' => $amount,
                    'PartyA' => $phone,
                    'PartyB' => env('MPESA_BUSINESS_SHORTCODE'),
                    'PhoneNumber' => $phone,
                    'CallBackURL' => route('mpesa.callback'), 
                    'AccountReference' => $invoiceId,
                    'TransactionDesc' => 'Payment for Invoice ' . $invoiceId,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('Failed to initiate M-Pesa payment: ' . $e->getMessage());
            throw new \Exception('Failed to initiate M-Pesa payment');
        }
    }
}
