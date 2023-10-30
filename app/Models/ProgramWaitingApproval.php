<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramWaitingApproval extends Model
{
    use HasFactory;

    protected $fillable = ['id','name','type_id','allocation_rate','status','created_at'];

}
