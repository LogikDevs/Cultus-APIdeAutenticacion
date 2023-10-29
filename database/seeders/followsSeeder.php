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
        ->count(50)
        ->create(); 

        \App\Models\follows::factory(1)
        ->count(1)
        ->create([
            "id_follower" => 2,
            "id_followed" => 1,
            "friends" => true
        ]);

        \App\Models\follows::factory(1)
        ->count(1)
        ->create([
            "id_follower" => 1,
            "id_followed" => 2,
            "friends" => true
        ]);
    }
}
