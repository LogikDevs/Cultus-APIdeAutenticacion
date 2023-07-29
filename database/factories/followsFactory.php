<?php

namespace Database\Factories;
use App\Models\follows;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowsFactory extends Factory
{
    
    public function definition()
    {
        return [
            'id_follower' => \App\Models\user::factory(),
            'id_followed' => \App\Models\user::factory(),
            'friends' => $this->faker->boolean(),
         ];
    }
}
