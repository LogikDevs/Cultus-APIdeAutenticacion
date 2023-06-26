<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\interest;
use Illuminate\Database\Eloquent\Factories\Factory;

final class interestFactory extends Factory
{

    protected $model = interest::class;

    public function definition(): array
    {
        return [
            'interest' =>$this->faker->word()
        ];
    }
}
