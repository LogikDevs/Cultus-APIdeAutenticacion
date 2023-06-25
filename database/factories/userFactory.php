<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\user;
use Illuminate\Database\Eloquent\Factories\Factory;

final class userFactory extends Factory
{

    protected $model = user::class;

    public function definition(): array
    {
        $gender = $faker->randomElement(['male', 'female']);
        return [
            'name' => $this->faker->firstName($gender),
            'surname' => $this->faker->lasName(),
            'age' => $this->faker->numberBetween(0, 100),
            'gender' => $this->faker->optional()->$gender,
            'mail' => $this->faker->email(),
            'passwd' => $this->faker->password(8),
            'profile_pic' => $this->faker->optional()->url(),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}
