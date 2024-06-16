<?php

// database/seeders/TransactionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample transaction data for invoice 1
        Transaction::create([
            'invoice_id' => 1, // Adjust according to your invoice IDs
            'transaction_id' => 'MPESA1234', // Example transaction ID
            'amount' => 2.00,
            'phone' => '254700123456', // Example phone number
            'status' => 'Success',
        ]);

        // Sample transaction data for invoice 2
        Transaction::create([
            'invoice_id' => 2,
            'transaction_id' => 'MPESA5678', // Example transaction ID
            'amount' => 15.00,
            'phone' => '254700987654', // Example phone number
            'status' => 'Success',
        ]);
    }
}
