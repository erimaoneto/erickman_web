<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'fleet_id',
        'service_date',
        'service_type',
        'cost',
        'workshop',
        'odometer_km',
        'description',
    ];

    protected $casts = [
        'service_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }
}
