<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    const STATUS_RECOMMENDED = 2;
    const STATUS_APPROVE = 3;
    const STATUS_REJECT = 4;

    protected $fillable = [
        'name',
        'type_id',
        'allocation_rate',
        'status',
        'latest_payment',
        'payment_date',
        'total_month',
        'total_year',
        'end_date',
        'allocation',
        'frequency',
        'reject_reason',
        'installment_status',
        'installment_payment_date',
        'installment_name'
    ];

    public function program_type()
    {
        return $this->belongsTo(ProgramType::class, 'type_id');
    }

    public function refBank()
    {
        return $this->belongsTo(RefBank::class, 'bank');
    }

    public function receivers()
    {
        return $this->hasMany(Receiver::class, 'program_id');
    }

    public function installment()
    {
        return $this->hasMany(InstallmentPrograms::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function status_id()
    {
        return $this->belongsTo(Status::class, 'status');
    }

    public function allocation_id()
    {
        return $this->belongsTo(Allocation::class, 'allocation');
    }
}
