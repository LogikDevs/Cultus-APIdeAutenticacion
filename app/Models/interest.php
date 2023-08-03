<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class interest extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "interest_label";
    protected $primaryKey = 'id_label';
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'likes', 'id_interest', 'id_user');
    }
}
