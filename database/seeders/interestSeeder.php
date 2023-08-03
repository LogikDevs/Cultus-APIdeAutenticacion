<?php

namespace Database\Seeders;
use App\Models\interest;
use Illuminate\Database\Seeder;

class interestSeeder extends Seeder
{
    public function run()
    {
        \App\Models\interest::factory()->create([
            "id_label" => "1",
            "interest" => "gonzalito",
        ]);
        \App\Models\interest::factory()->count(100)->create();

      
    }
}
