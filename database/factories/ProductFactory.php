<?php

namespace Database\Factories;

use App\Enums\ProductCategory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = ucfirst(fake()->unique()->words(2, true));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category' => fake()->randomElement(ProductCategory::cases()),
            'price' => fake()->numberBetween(150, 600) * 100,
            'description' => fake()->paragraph(),
            'is_published' => true,
        ];
    }

    /**
     * Indicate that the product is hidden from the shop.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }
}
