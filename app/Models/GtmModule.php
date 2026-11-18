<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GtmModule extends Model
{
    use HasFactory;

    protected $table = 'gtm_modules';

    protected $fillable = [
        'order_number',
        'tube_code',
        'tube_brand',
        'capacity',
        'manufacture_year',
        'fleet_id',
        'hydrotest_expiry',
        'status',
        'notes',
    ];

    protected $casts = [
        'order_number' => 'integer',
        'hydrotest_expiry' => 'date',
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'fleet_id');
    }
}
