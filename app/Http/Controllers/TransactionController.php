<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

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
            'amount' => 'required',
            'transaction_id' => 'required',
            'phone' => 'required',
            'status' => 'required',
        ]);

        $transaction = Transaction::create($validated);

        return response()->json($transaction, 201);
    }

    public function mpesaCallback(Request $request)
    {
        $callbackData = $request->all();

        // Handle M-Pesa callback logic
        $transactionData = [
            'invoice_id' => $callbackData['Body']['stkCallback']['MerchantRequestID'] ?? null,
            'amount' => $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'] ?? null,
            'transaction_id' => $callbackData['Body']['stkCallback']['CheckoutRequestID'] ?? null,
            'phone' => $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'] ?? null,
            'status' => ($callbackData['Body']['stkCallback']['ResultCode'] ?? 1) == 0 ? 'Success' : 'Failed'
        ];

        Transaction::create($transactionData);

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
