<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Specifying the fields that are mass assignable
    protected $fillable = [
        'description', 
        'amount',
        'payment_status',   
        'transaction_id'    
    ];

    // Defining the relationship with the Transaction model
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
