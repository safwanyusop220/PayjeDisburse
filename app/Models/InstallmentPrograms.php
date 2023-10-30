<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPrograms extends Model
{
    use HasFactory;
    protected $fillable = ['program_id', 'amount', 'installment_payment_date', 'installment_name'];
}
