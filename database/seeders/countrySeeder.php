<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class countrySeeder extends Seeder
{

    public function run()
    {
        \App\Models\country::factory()->count(100);
    }
}
