<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Define web routes for your application here.
|
*/

// Default Laravel welcome view
// Route for web-based view, set as the home page
Route::get('/', [TransactionController::class, 'webIndex'])->name('transactions.webIndex');




// Web route for the transactions dashboard
Route::get('/transactions', [TransactionController::class, 'webIndex'])->name('transactions.webIndex');
// Routes for Invoices
Route::get('/invoices', [InvoiceController::class, 'index']); // List all invoices
Route::post('/invoices', [InvoiceController::class, 'store']); // Create a new invoice
Route::get('/invoices/{id}', [InvoiceController::class, 'show']); // Show a specific invoice details
Route::post('/invoices/{id}/payment', [InvoiceController::class, 'initiatePayment']); // Initiate payment for an invoice

// Routes for Transactions
//Route::get('/transactions', [TransactionController::class, 'index']); // List all transactions
//Route::post('/transactions', [TransactionController::class, 'store']); // Record a new transaction

// Example route for handling M-Pesa callback
Route::post('/mpesa/callback', [TransactionController::class, 'mpesaCallback'])->name('mpesa.callback');
