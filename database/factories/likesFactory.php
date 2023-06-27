<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class likesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_user' => \App\Models\user::factory(),
            'id_interest' => \App\Models\interest::factory()
        ];
    }
}
