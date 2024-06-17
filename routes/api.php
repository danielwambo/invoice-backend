<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Invoices routes
Route::post('/mpesa/callback', [InvoiceController::class, 'mpesaCallback'])->name('mpesa.callback');

Route::get('/invoices', [InvoiceController::class, 'index']);
Route::post('/invoices', [InvoiceController::class, 'store']);
Route::get('/invoices/{id}', [InvoiceController::class, 'show']);

// Route to initiate payment for an invoice
#Route::post('/invoices/{id}/payment', [TransactionController::class, 'initiatePayment']);
// Route to initiate payment for an invoice
Route::post('/invoices/{invoice}/pay', [TransactionController::class, 'initiatePayment']);

// Route to fetch access token
Route::post('/auth/token', [AuthController::class, 'getToken']);

// Route for transactions
Route::get('/transactions', [TransactionController::class, 'index']);
