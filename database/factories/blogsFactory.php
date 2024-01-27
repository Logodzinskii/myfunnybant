<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BlogsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'blog_author_name' => $this->faker->name(),
            'blog_author_link' => $this->faker->unique()->safeEmail(),
            'blog_header' => $this->faker->text(),
            'blog_desrypion' => $this->faker->text(),
            'blog_content' => $this->faker->text(),
        ];
    }
}
