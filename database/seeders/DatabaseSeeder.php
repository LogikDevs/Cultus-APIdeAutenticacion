<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\user;
use App\Models\country;
use App\Models\interest;
use App\Models\follows;
use Laravel\Passport\Client;

class DatabaseSeeder extends Seeder
{

    public function run()
    {   
        $this->call(interestSeeder::class);
        $this->call(countrySeeder::class);
        $this->call(userSeeder::class);
        $this->call(likesSeed::class);
        $this->call(followsSeeder::class);
        Client::create([
            'id' => 100,
            'name' => 'Tests',
            'secret' => "wsBa0mp4jwSTYssUGHX5xoqD9IC0X95Gfpg0w3uY",
            'redirect' => 'http://localhost',
            'provider' => 'users',
            'personal_access_client' => false,
            'password_client' => true,
            'revoked' => false
        ]);
    }
}
