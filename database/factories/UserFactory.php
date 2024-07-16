<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }

    /**
     * Indicate that the user should have shelves with books.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withShelvesAndBooks($shelfCount = 3, $bookCount = 3)
    {
        return $this->has(
            \App\Models\Shelf::factory()
                ->count($shelfCount)
                ->withBooks($bookCount),
            'shelves'
        );
    }
}
