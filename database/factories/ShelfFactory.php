<?php

namespace Database\Factories;

use App\Models\Shelf;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShelfFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shelf::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'user_id' => \App\Models\User::factory(),
        ];
    }

    /**
     * 
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withBooks($count = 3)
    {
        return $this->hasAttached(
            \App\Models\Book::factory()->count($count),
            [],
            'books'
        );
    }
}
