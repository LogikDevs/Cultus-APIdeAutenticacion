<?php

namespace Database\Seeders;
use App\Models\follows;
use App\Models\user;

use Illuminate\Database\Seeder;

class followsSeeder extends Seeder
{

    public function run()
    {
        \App\Models\follows::factory()
        ->has(user::factory(), 'id_follower')
        ->has(user::factory(), 'id_followed')
        ->count(200)
        ->create(); 
    }
}
