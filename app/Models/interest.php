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
    

}
