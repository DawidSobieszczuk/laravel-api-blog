<?php

namespace Database\Factories;

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
        $title = $this->faker->sentence();

        return [
            'user_id' => 0,
            'title' => $title,
            'thumbnail' => 'https://picsum.photos/1600/900?random=' . rand(),
            'excerpt' => join(' ', $this->faker->sentences(4)),
            'content' => preg_replace('/\s+/', ' ', '{
                "blocks": [
                    {
                        "type": "header",
                        "data": {
                            "text": "'. $title .'",
                            "level": 2
                        }
                    },
                    {
                        "type": "paragraph",
                        "data": {
                            "text": "'. $this->faker->paragraph() .'"
                        }
                    },
                    {
                        "type": "paragraph",
                        "data": {
                            "text": "'. $this->faker->paragraph() .'"
                        }
                    }
                ] 
            }'),
            'is_draft' => $this->faker->randomElement([true, false]),
            'categories' => $this->faker->randomElement(['Kateoria I', 'Kateoria II', 'Kateoria III']),
            'tags' => $this->faker->randomElements(['foo', 'boo', 'tag'], 2),
        ];
    }
}
