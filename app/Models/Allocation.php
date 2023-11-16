<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;
    const STATUS_RECOMMEND = 2;
    const STATUS_APPROVE = 3;
    const STATUS_REJECT = 4;

    protected $fillable = ['allocation_source', 'total_allocation', 'allocation_balance', 'status', 'reject_reason', 'bank', 'account_number'];

    public function getProgramData($programId)
    {
        $program = Program::findOrFail($programId);
        $allocationName = $program->allocation->name;

        return $allocationName;
    }

    public function program()
    {
        return $this->hasMany(Program::class);
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
