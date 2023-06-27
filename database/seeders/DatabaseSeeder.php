<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\user;
use App\Models\country;
use App\Models\interest;
class DatabaseSeeder extends Seeder
{

    public function run()
    {   
        $this->call(interestSeeder::class);
        $this->call(countrySeeder::class);
        $this->call(userSeeder::class);
        $this->call(likesSeed::class);
    }
}
