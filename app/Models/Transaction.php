<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'category',
        'amount',
        'transaction_date',
        'fleet_id',
        'reference_invoice',
        'description',
        'receipt_file',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
