<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllocationWaitingApprovalController extends Model
{
    use HasFactory;

    protected $fillable = ['allocation_source', 'total_allocation', 'allocation_balance', 'status'];

    public function bank()
    {
        return $this->belongsTo(RefBank::class, 'bank');
    }
}
