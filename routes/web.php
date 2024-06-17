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

// Home page showing the list of transactions
Route::get('/', [TransactionController::class, 'webIndex'])->name('transactions.webIndex');

// Routes for Invoices
Route::get('/invoices', [InvoiceController::class, 'webIndex'])->name('invoices.index'); // List all invoices (web view)
Route::post('/invoices', [InvoiceController::class, 'store']); // Create a new invoice
Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show'); // Show a specific invoice details

// Route to initiate payment for an invoice
Route::post('/invoices/{invoice}/pay', [TransactionController::class, 'initiatePayment'])->name('invoices.pay');

// Routes for Transactions
Route::get('/transactions', [TransactionController::class, 'webIndex'])->name('transactions.webIndex'); // List all transactions (web view)
Route::post('/transactions', [TransactionController::class, 'store']); // Record a new transaction

// Route for handling M-Pesa callback
Route::post('/mpesa/callback', [TransactionController::class, 'mpesaCallback'])->name('mpesa.callback');
