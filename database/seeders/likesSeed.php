<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\user;
use App\Models\interest;
use App\Models\likes;
class likesSeed extends Seeder
{
    
    public function run()
    {
        \App\Models\likes::factory()
        ->has(user::factory(), 'id_user')
        ->has(interest::factory(),'id_interest')
        ->count(15)
        ->create();

        
        \App\Models\likes::factory()
        ->count(1)
        ->create([
            "id" => "16",
            "id_user" => "1",
            "id_interest" => "1",
        ]);
    
    }
}
