<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Passport\HasApiTokens;
class user extends Model
{
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
}
