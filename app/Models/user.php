<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
#use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class user extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;



     use HasFactory;
    use SoftDeletes;
    use HasApiTokens;
    protected $table = "users";

    
    public function homeland(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'homeland');
    }

    public function residence(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'residence');
    }

    /*

    protected $fillable = [
        'name',
        'email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    */
}
