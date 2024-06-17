<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MpesaService;
use App\Models\Transaction;

class PaymentController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    public function initiatePayment(Request $request, $invoiceId)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
        ]);

        $amount = 100; 
        $phone = $validated['phone'];

        try {
            $response = $this->mpesaService->initiatePayment($amount, $phone, $invoiceId);

            // Log the transaction in the database
            $transaction = new Transaction();
            $transaction->invoice_id = $invoiceId;
            $transaction->phone = $phone;
            $transaction->amount = $amount;
            $transaction->transaction_status = 'Initiated';
            $transaction->save();

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function mpesaCallback(Request $request)
    {
     
        \Log::info('Mpesa Callback', $request->all());

        
        $callbackData = $request->all();

        // Process the callback and update the transaction status
        $transaction = Transaction::where('invoice_id', $callbackData['invoiceId'])->first();
        if ($transaction) {
            $transaction->transaction_status = $callbackData['ResultCode'] == 0 ? 'Success' : 'Failed';
            $transaction->save();
        }

        return response()->json(['status' => 'success']);
    }
}
