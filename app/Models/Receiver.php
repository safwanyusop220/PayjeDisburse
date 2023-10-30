<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    use HasFactory;
    const STATUS_APPROVE = 3;
    const STATUS_REJECT = 4;

    protected $fillable = [
        'program_id',
        'status',
        'name',
        'identification_number',
        'address',
        'phone_number',
        'email',
        'bank',
        'account_number',
        'reject_reason'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function bank()
    {
        return $this->belongsTo(RefBank::class, 'bank');
    }

    public function status_id()
    {
        return $this->belongsTo(Status::class, 'status');
    }
}
