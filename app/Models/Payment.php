<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    const STATUS_APPROVE = 3;
    const STATUS_REJECT = 4;

    protected $fillable = [
        'program_id',
        'receiver_id',
        'total_receiver',
        'payment_date',
        'status',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Receiver::class, 'receiver_id');
    }
}
