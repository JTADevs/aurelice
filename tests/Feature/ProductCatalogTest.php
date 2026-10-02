<?php

namespace Tests\Feature;

use App\Enums\ProductCategory;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogue_lists_all_published_products_newest_first(): void
    {
        $this->freezeTime();
        $older = Product::factory()->create(['created_at' => now()->subDays(2)]);
        $newer = Product::factory()->create(['created_at' => now()->subDay()]);
        Product::factory()->unpublished()->create();

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Products')
            ->where('category', null)
            ->has('products.data', 2)
            ->where('products.data.0.id', $newer->id)
            ->where('products.data.1.id', $older->id)
            ->where('canonicalUrl', route('products.index'))
        );
    }

    public function test_catalogue_filters_by_category(): void
    {
        $bracelet = Product::factory()->create(['category' => ProductCategory::Bracelets]);
        Product::factory()->create(['category' => ProductCategory::Necklaces]);

        $response = $this->get(route('products.index', ProductCategory::Bracelets));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->where('category', ['value' => 'bransoletki', 'label' => 'Bransoletki'])
            ->has('products.data', 1)
            ->where('products.data.0.id', $bracelet->id)
            ->where('canonicalUrl', url('/produkty/bransoletki'))
        );
    }

    public function test_unknown_category_returns_not_found(): void
    {
        $this->get('/produkty/kolczyki')->assertNotFound();
    }

    public function test_catalogue_renders_breadcrumb_structured_data(): void
    {
        $response = $this->get(route('products.index', ProductCategory::Sets));

        $response->assertSee('<script type="application/ld+json">', false);
        $response->assertSee('"@type":"BreadcrumbList"', false);
        $response->assertSee('"name":"Komplety"', false);
    }

    public function test_catalogue_is_paginated_with_a_self_referencing_canonical_url(): void
    {
        Product::factory()->count(25)->create();

        $response = $this->get(route('products.index', ['page' => 2]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->has('products.data', 1)
            ->where('products.current_page', 2)
            ->where('canonicalUrl', route('products.index').'?page=2')
        );
    }
}
