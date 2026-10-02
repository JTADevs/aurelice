<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display the list of products.
     */
    public function index(): Response
    {
        $products = Product::query()
            ->with('mainImage')
            ->latest()
            ->paginate(20)
            ->through(fn (Product $product): array => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->label(),
                'price' => $product->price,
                'is_published' => $product->is_published,
                'thumbnail' => $product->mainImage?->url,
            ]);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => ProductCategory::options(),
        ]);
    }

    /**
     * Store a newly created product with its images.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = DB::transaction(function () use ($request): Product {
            $product = Product::create([
                ...$request->safe()->only(['name', 'slug', 'category', 'description', 'is_published']),
                'price' => $request->priceInGrosze(),
            ]);

            $this->storeImages($product, $request->file('images', []));

            return $product;
        });

        Inertia::flash('success', "Dodano produkt „{$product->name}”.");

        return redirect()->route('admin.products.edit', $product);
    }

    /**
     * Show the form for editing the product.
     */
    public function edit(Product $product): Response
    {
        $product->load('images');

        return Inertia::render('Admin/Products/Edit', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'category' => $product->category->value,
                'price' => $product->price,
                'description' => $product->description,
                'is_published' => $product->is_published,
                'images' => $product->images->map(fn (ProductImage $image): array => [
                    'id' => $image->id,
                    'url' => $image->url,
                    'width' => $image->width,
                    'height' => $image->height,
                ]),
            ],
            'categories' => ProductCategory::options(),
        ]);
    }

    /**
     * Update the product and append newly uploaded images.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product): void {
            $product->update([
                ...$request->safe()->only(['name', 'category', 'description', 'is_published']),
                'price' => $request->priceInGrosze(),
            ]);

            $this->storeImages($product, $request->file('images', []));
        });

        Inertia::flash('success', 'Zapisano zmiany.');

        return redirect()->route('admin.products.edit', $product);
    }

    /**
     * Delete the product together with its image files.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $paths = $product->images()->pluck('path')->all();

        $product->delete();

        Storage::disk(ProductImage::DISK)->delete($paths);

        Inertia::flash('success', "Usunięto produkt „{$product->name}”.");

        return redirect()->route('admin.products.index');
    }

    /**
     * Store uploaded files on the public disk and attach them after the existing images.
     *
     * @param  array<int, UploadedFile>  $files
     */
    private function storeImages(Product $product, array $files): void
    {
        $nextSortOrder = (int) $product->images()->max('sort_order') + 1;

        foreach ($files as $file) {
            [$width, $height] = getimagesize($file->getRealPath());

            $product->images()->create([
                'path' => $file->store('products', ProductImage::DISK),
                'width' => $width,
                'height' => $height,
                'sort_order' => $nextSortOrder++,
            ]);
        }
    }
}
