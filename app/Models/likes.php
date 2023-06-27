<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class likes extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "likes";
    
    public function id_user(): BelongsTo
    {
        return $this->belongsTo(user::class, 'id_user');
    }

    public function id_interest(): BelongsTo
    {
        return $this->belongsTo(interest::class, 'id_interst');
    }
}
