<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Services\MpesaService;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric'
        ]);

        $invoice = Invoice::create($validated);

        return response()->json($invoice, 201);
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return response()->json($invoice);
    }

    public function initiatePayment($id, Request $request, MpesaService $mpesaService)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $phone = $request->input('phone');

            $response = $mpesaService->initiatePayment($invoice->amount, $phone, $invoice->id);

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function mpesaCallback(Request $request)
    {
        // Log the incoming callback request (optional but recommended)
        Log::info('M-Pesa Callback Received: ' . $request->getContent());

        // Process the callback data as per your application's requirements
        // Example: Update invoice status based on the callback data

        // Return a response to M-Pesa acknowledging receipt of the callback
        return response()->json(['message' => 'M-Pesa Callback Received'], 200);
    }
}
