<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'isbn' => $this->faker->isbn13(),
            'pages' => $this->faker->numberBetween(50, 900),
            'publisher' => $this->faker->company(),
            'published_date' => $this->faker->date(),
            'cover_image' => null,
            'price' => 0.0,
            'rating' => 0,
            'rating_count' => 0,
            'view_count' => 0,
            'recommended_count' => 0,
        ];
    }
}
