<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\user;
use App\Models\interest;
use App\Models\likes;
class likesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *    \App\Models\user::factory()
            ->has(Country::factory(), 'homeland')
            ->has(Country::factory(), 'residence')
            ->count(10)
            ->create();
     * @return void
     */
    public function run()
    {
        \App\Models\likes::factory()
        ->has(user::factory(), 'id_user')
        ->has(interest::factory(),'id_interest')
        ->count(15)
        ->create();
    }
}
