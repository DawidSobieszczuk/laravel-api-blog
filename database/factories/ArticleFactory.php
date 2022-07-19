<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 0,
            'title' => $this->faker->sentence(),
            'thumbnail' => 'https://picsum.photos/1600/900',
            'excerpt' => join(' ', $this->faker->sentences(4)),
            'content' => $this->faker->paragraphs(24, true),
            'is_draft' => $this->faker->randomElement([true, false]),
            'categories' => $this->faker->words(),
            'tags' => $this->faker->words(),
        ];
    }
}
