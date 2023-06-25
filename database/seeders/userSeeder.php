<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class userSeeder extends Seeder
{
    
    public function run()
    {
    
        \App\Models\user::factory()
        ->hasOne(\App\Models\country::factory(), 'homeland')
        ->hasOne(\App\Models\country::factory(), 'residence')
        ->count(10);
                    

    }
}
