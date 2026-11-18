<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function fleets()
    {
        return $this->hasMany(Fleet::class, 'type', 'name');
    }
}
