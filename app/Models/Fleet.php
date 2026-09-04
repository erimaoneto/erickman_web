<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Fleet extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'vehicle_name',
        'type',
        'brand',
        'year',
        'capacity',
        'status',
        'driver_name',
        'kir_expiry',
        'stnk_expiry',
        'image_path',
        'notes',
    ];

    protected $casts = [
        'kir_expiry' => 'date',
        'stnk_expiry' => 'date',
    ];

    public function maintenances()
    {
        return $this->hasMany(FleetMaintenance::class)->orderBy('service_date', 'desc');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('transaction_date', 'desc');
    }

    public function getKirStatusAttribute()
    {
        if (!$this->kir_expiry) return ['status' => 'unknown', 'label' => 'Belum Ada Data', 'class' => 'bg-gray-100 text-gray-700'];
        $days = Carbon::now()->diffInDays($this->kir_expiry, false);
        if ($days < 0) {
            return ['status' => 'expired', 'label' => 'KIR Kedaluwarsa (' . abs(round($days)) . ' hari lalu)', 'class' => 'bg-red-100 text-red-800 border-red-300'];
        } elseif ($days <= 30) {
            return ['status' => 'warning', 'label' => 'KIR Jatuh Tempo (' . round($days) . ' hari lagi)', 'class' => 'bg-amber-100 text-amber-800 border-amber-300'];
        }
        return ['status' => 'valid', 'label' => 'KIR Aktif (s/d ' . $this->kir_expiry->format('d/m/Y') . ')', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'];
    }

    public function getStnkStatusAttribute()
    {
        if (!$this->stnk_expiry) return ['status' => 'unknown', 'label' => 'Belum Ada Data', 'class' => 'bg-gray-100 text-gray-700'];
        $days = Carbon::now()->diffInDays($this->stnk_expiry, false);
        if ($days < 0) {
            return ['status' => 'expired', 'label' => 'STNK Kedaluwarsa (' . abs(round($days)) . ' hari lalu)', 'class' => 'bg-red-100 text-red-800 border-red-300'];
        } elseif ($days <= 30) {
            return ['status' => 'warning', 'label' => 'STNK Jatuh Tempo (' . round($days) . ' hari lagi)', 'class' => 'bg-amber-100 text-amber-800 border-amber-300'];
        }
        return ['status' => 'valid', 'label' => 'STNK Aktif (s/d ' . $this->stnk_expiry->format('d/m/Y') . ')', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'];
    }
}
