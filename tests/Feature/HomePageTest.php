<?php

namespace Tests\Feature;

use App\Enums\ProductCategory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_the_home_inertia_component(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Home'));
    }

    public function test_new_arrivals_show_the_latest_published_products_only(): void
    {
        $this->freezeTime();
        $hidden = Product::factory()->unpublished()->create();
        $older = Product::factory()->count(4)->create(['created_at' => now()->subDay()]);
        $newest = Product::factory()->create(['name' => 'Apricity', 'price' => 42000]);
        ProductImage::factory()->for($newest)->create(['path' => 'products/apricity.webp', 'width' => 1600, 'height' => 1200]);

        $response = $this->get(route('home'));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->has('newArrivals', 4)
            ->where('newArrivals.0.name', 'Apricity')
            ->where('newArrivals.0.price', 42000)
            ->where('newArrivals.0.image.url', Storage::disk(ProductImage::DISK)->url('products/apricity.webp'))
            ->where('newArrivals.0.image.width', 1600)
            ->where('newArrivals', fn ($products) => ! collect($products)->contains('id', $hidden->id))
        );
    }

    public function test_collections_show_the_newest_published_product_photo_of_each_category(): void
    {
        $this->freezeTime();
        $olderBracelet = Product::factory()->create(['category' => ProductCategory::Bracelets, 'created_at' => now()->subDays(2)]);
        ProductImage::factory()->for($olderBracelet)->create(['path' => 'products/older.webp']);
        $newestBracelet = Product::factory()->create(['name' => 'Rosé', 'category' => ProductCategory::Bracelets, 'created_at' => now()->subDay()]);
        ProductImage::factory()->for($newestBracelet)->create(['path' => 'products/rose.webp', 'width' => 1333, 'height' => 2000]);
        Product::factory()->create(['category' => ProductCategory::Bracelets]);
        $hiddenBracelet = Product::factory()->unpublished()->create(['category' => ProductCategory::Bracelets]);
        ProductImage::factory()->for($hiddenBracelet)->create(['path' => 'products/hidden.webp']);

        $response = $this->get(route('home'));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->has('collections', count(ProductCategory::cases()))
            ->where('collections.1.value', ProductCategory::Bracelets->value)
            ->where('collections.1.product', 'Rosé')
            ->where('collections.1.image.url', Storage::disk(ProductImage::DISK)->url('products/rose.webp'))
            ->where('collections.1.image.width', 1333)
            ->where('collections.0.value', ProductCategory::Necklaces->value)
            ->where('collections.0.image', null)
        );
    }
}
