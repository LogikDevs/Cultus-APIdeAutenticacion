<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
#use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use App\Models\interest;
use App\Models\country;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class user extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $table = "users";
    
    public function homeland(): BelongsTo
    {
        return $this->belongsTo(country::class, 'homeland');
    }

    public function residence(): BelongsTo
    {
        return $this->belongsTo(country::class, 'residence');
    }

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(interest::class, 'likes', 'id_user', 'id_interest')->whereNull('likes.deleted_at');
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
