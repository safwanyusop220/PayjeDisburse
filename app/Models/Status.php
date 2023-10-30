<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function allocation()
    {
        return $this->hasMany(Allocation::class);
    }

    public function program()
    {
        return $this->hasMany(Program::class);
    }
}
