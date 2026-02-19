<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalCopies = $this->faker->numberBetween(1, 10);
        $availableCopies = $this->faker->numberBetween(0, $totalCopies);

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'isbn' => $this->faker->unique()->isbn13(),
            'total_copies' => $totalCopies,
            'available_copies' => $availableCopies,
            'is_available' => $availableCopies > 0,
        ];
    }
}

