<?php

namespace Tests\Feature\Admin;

use App\Enums\ProductCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(ProductImage::DISK);
        $this->admin = User::factory()->admin()->create();
    }

    public function test_user_without_admin_flag_cannot_manage_products(): void
    {
        $product = Product::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->get(route('admin.products.index'))->assertForbidden();
        $this->post(route('admin.products.store'))->assertForbidden();
        $this->delete(route('admin.products.destroy', $product))->assertForbidden();
    }

    public function test_admin_sees_the_product_list(): void
    {
        $product = Product::factory()->create(['name' => 'Apricity', 'price' => 42000]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Products/Index')
            ->has('products.data', 1)
            ->where('products.data.0.id', $product->id)
            ->where('products.data.0.name', 'Apricity')
            ->where('products.data.0.price', 42000)
        );
    }

    public function test_admin_creates_a_product_with_images(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Rosé Bransoletka',
            'category' => ProductCategory::Bracelets->value,
            'price' => '249,99',
            'description' => 'Kwarc różowy i perły.',
            'is_published' => '1',
            'images' => [
                UploadedFile::fake()->image('front.webp', 1600, 1200),
                UploadedFile::fake()->image('side.jpg', 800, 800),
            ],
        ]);

        $product = Product::sole();

        $response->assertRedirect(route('admin.products.edit', $product));
        $this->assertSame('rose-bransoletka', $product->slug);
        $this->assertSame(ProductCategory::Bracelets, $product->category);
        $this->assertSame(24999, $product->price);
        $this->assertTrue($product->is_published);

        $images = $product->images;
        $this->assertCount(2, $images);
        $this->assertSame([1600, 1200], [$images[0]->width, $images[0]->height]);
        Storage::disk(ProductImage::DISK)->assertExists($images->pluck('path')->all());
    }

    public function test_product_creation_is_validated(): void
    {
        Product::factory()->create(['name' => 'Dalia', 'slug' => 'dalia']);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Dalia',
            'category' => 'kolczyki',
            'price' => '12,345',
            'images' => [UploadedFile::fake()->create('notatki.pdf', 100, 'application/pdf')],
        ]);

        $response->assertSessionHasErrors(['slug', 'category', 'price', 'images.0']);
        $this->assertSame(1, Product::count());
    }

    public function test_admin_updates_a_product_and_appends_images(): void
    {
        $product = Product::factory()->create(['slug' => 'solea']);
        $existingImage = ProductImage::factory()->for($product)->create(['sort_order' => 0]);

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name' => 'Solea Naszyjnik',
            'category' => ProductCategory::Necklaces->value,
            'price' => '310',
            'description' => '',
            'is_published' => '0',
            'images' => [UploadedFile::fake()->image('nowe.webp', 1000, 1000)],
        ]);

        $response->assertRedirect(route('admin.products.edit', $product));

        $product->refresh();
        $this->assertSame('Solea Naszyjnik', $product->name);
        $this->assertSame('solea', $product->slug);
        $this->assertSame(31000, $product->price);
        $this->assertFalse($product->is_published);
        $this->assertSame([$existingImage->id], [$product->images->first()->id]);
        $this->assertCount(2, $product->images);
    }

    public function test_admin_deletes_a_product_with_its_image_files(): void
    {
        $product = Product::factory()->create();
        $path = UploadedFile::fake()->image('a.webp')->store('products', ProductImage::DISK);
        ProductImage::factory()->for($product)->create(['path' => $path]);

        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertModelMissing($product);
        $this->assertSame(0, ProductImage::count());
        Storage::disk(ProductImage::DISK)->assertMissing($path);
    }

    public function test_admin_sets_the_main_image(): void
    {
        $product = Product::factory()->create();
        $first = ProductImage::factory()->for($product)->create(['sort_order' => 0]);
        $second = ProductImage::factory()->for($product)->create(['sort_order' => 1]);

        $this->actingAs($this->admin)
            ->patch(route('admin.products.images.update', [$product, $second]))
            ->assertRedirect();

        $this->assertSame([$second->id, $first->id], $product->images()->pluck('id')->all());
        $this->assertSame($second->id, $product->mainImage()->first()->id);
    }

    public function test_admin_deletes_a_single_image(): void
    {
        $product = Product::factory()->create();
        $path = UploadedFile::fake()->image('a.webp')->store('products', ProductImage::DISK);
        $image = ProductImage::factory()->for($product)->create(['path' => $path]);

        $this->actingAs($this->admin)
            ->delete(route('admin.products.images.destroy', [$product, $image]))
            ->assertRedirect();

        $this->assertModelMissing($image);
        Storage::disk(ProductImage::DISK)->assertMissing($path);
    }

    public function test_image_of_another_product_cannot_be_deleted_through_a_different_product(): void
    {
        $product = Product::factory()->create();
        $otherImage = ProductImage::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('admin.products.images.destroy', [$product, $otherImage]))
            ->assertNotFound();

        $this->assertModelExists($otherImage);
    }
}
