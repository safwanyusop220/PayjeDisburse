<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles  ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serializati~on.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user_id()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function audit_trail()
    {
         return $this->hasMany(AuditTrail::class);
    }

    public function log($message, $action=null)
    {
        $message = ucwords($message);

        $data = [
            'user_id' => $this->id,
            'name'    => $this->name,
            'date'    => Carbon::parse(now()->toDateString()),
            'activity'=> "$message",
            'action'  => $action
        ];

        AuditTrail::query()->create($data);

    }
}
