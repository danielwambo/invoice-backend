<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Specify the fields that are mass assignable
    protected $fillable = [
        'description', 
        'amount'
    ];

    // Define the relationship with the Transaction model
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
