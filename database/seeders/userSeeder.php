<?php

namespace Database\Seeders;
use App\Models\user;
use App\Models\country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class userSeeder extends Seeder
{
    
    public function run()
    {
        {
          
            \App\Models\user::factory()
            ->has(Country::factory(), 'homeland')
            ->has(Country::factory(), 'residence')
            ->count(10)
            ->create();     
        }

        
        /*
        \App\Models\user::factory()
        ->has(\App\Models\country::factory()->count(1), 'homeland')
        ->has(\App\Models\country::factory()->count(1), 'residence')
        ->count(10)
        ->create();
    */
    }
}
