<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();
        return response()->json($transactions);
    }

    public function webIndex()
    {
        $transactions = Transaction::latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required',
            'amount' => 'required|numeric',
            'transaction_id' => 'required',
            'phone' => 'required',
            'status' => 'required',
        ]);

        $transaction = Transaction::create($validated);

        return response()->json($transaction, 201);
    }
    public function initiatePayment(Request $request, Invoice $invoice)
{
    Log::info('Request data:', $request->all()); // Log the request data

    $amount = number_format($invoice->amount, 0, '', ''); 
    $phone = $this->formatPhoneNumber($request->phone);

    Log::info('Initiating payment for invoice ID: ' . $invoice->id);
    Log::info('Adjusted values - Amount: ' . $amount . ', Phone: ' . $phone);

    $response = $this->sendStkPush($phone, $amount, $invoice->id);

    if ($response->successful()) {
        $transaction = Transaction::create([
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'transaction_id' => $response['CheckoutRequestID'],
            'phone' => $request->phone, 
            'status' => 'Success',
        ]);

        $invoice->update([
            'transaction_id' => $transaction->transaction_id,
            'payment_status' => 'Success',
        ]);

        return response()->json(['message' => 'Payment initiated successfully', 'transaction' => $transaction]);
    } else {
        Log::error('Payment initiation failed', ['response' => $response->body()]);
        return response()->json(['message' => 'Failed to initiate payment'], 500);
    }
}

     

    private function sendStkPush($phone, $amount, $invoiceId)
    {
        Log::info('Sending STK Push request');
        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $accessToken = $this->getAccessToken();
        Log::info('Access token: ' . $accessToken);

        $timestamp = date('YmdHis');
        $shortcode = config('services.mpesa.shortcode');
        $passkey = config('services.mpesa.passkey');
        $password = base64_encode($shortcode . $passkey . $timestamp);
        $callbackUrl = 'https://2cb1-102-0-7-24.ngrok-free.app/mpesa/callback'; // ngrok

      
        $phone = $this->formatPhoneNumber($phone);

        Log::info('STK Push request payload: ' . json_encode([
            'BusinessShortCode' => $shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone, 
            'PartyB' => $shortcode,
            'PhoneNumber' => $phone, 
            'CallBackURL' => $callbackUrl,
            'AccountReference' => $invoiceId,
            'TransactionDesc' => 'Payment for Invoice ' . $invoiceId
        ]));

        $response = Http::withToken($accessToken)->post($url, [
            'BusinessShortCode' => $shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone, 
            'PartyB' => $shortcode,
            'PhoneNumber' => $phone, 
            'CallBackURL' => $callbackUrl,
            'AccountReference' => $invoiceId,
            'TransactionDesc' => 'Payment for Invoice ' . $invoiceId
        ]);

        Log::info('STK Push response: ' . $response->body());
        return $response;
    }

    private function getAccessToken()
    {
        Log::info('Getting access token');
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $consumerKey = config('services.mpesa.consumer_key');
        $consumerSecret = config('services.mpesa.consumer_secret');

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)->get($url);

        if ($response->successful()) {
            Log::info('Access token retrieved: ' . $response['access_token']);
            return $response['access_token'];
        } else {
            Log::error('Failed to get access token. Response: ' . $response->body());
            return null;
        }
    }

    private function formatPhoneNumber($phone)
    {
        Log::info('Original phone number: ' . $phone); // Log original phone number
    
        
        if (substr($phone, 0, 1) == '0') {
            $formattedPhone = '254' . substr($phone, 1);
        } elseif (substr($phone, 0, 3) != '254') {
            $formattedPhone = '254' . $phone;
        } else {
            $formattedPhone = $phone;
        }
    
        Log::info('Formatted phone number: ' . $formattedPhone); // Log formatted phone number
        return $formattedPhone;
    }
}
