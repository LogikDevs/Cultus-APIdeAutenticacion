<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class follows extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "follows";
    protected $primaryKey = 'id_follows';
    
    public function id_followed(): BelongsTo
    {
        return $this->belongsTo(user::class, 'id_followed');
    }

    public function id_follower(): BelongsTo
    {
        return $this->belongsTo(user::class, 'id_follower');
    }
}
