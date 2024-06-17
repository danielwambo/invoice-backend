<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return response()->json($invoices);
    }

    public function webIndex()
    {
        $invoices = Invoice::latest()->get();
        return view('invoices.index', compact('invoices'));
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

        if (request()->wantsJson()) {
            return response()->json($invoice);
        }

        return view('invoices.show', compact('invoice'));
    }
}
