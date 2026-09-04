<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'to_email',
        'to_name',
        'subject',
        'body',
        'status',
        'error_message',
        'inquiry_id',
        'sender_id',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
