<?php

namespace App\Http\Controllers;

use App\Enums\ProductCategory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Number of products per catalogue page.
     */
    private const int PER_PAGE = 24;

    /**
     * Number of products from the same category shown under a product.
     */
    private const int RELATED_LIMIT = 4;

    /**
     * Show published products, newest first, optionally limited to one category.
     */
    public function index(Request $request, ?ProductCategory $category = null): Response
    {
        $products = Product::query()
            ->published()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->with('mainImage')
            ->latest()
            ->latest('id')
            ->paginate(self::PER_PAGE)
            ->through(fn (Product $product): array => $product->toCardArray());

        $listUrl = route('products.index', $category);
        $currentPage = $products->currentPage();

        return Inertia::render('Products', [
            'category' => $category ? ['value' => $category->value, 'label' => $category->label()] : null,
            'categories' => ProductCategory::options(),
            'products' => $products,
            'canonicalUrl' => $currentPage > 1 ? "{$listUrl}?page={$currentPage}" : $listUrl,
            'ogImage' => $products->first()['image']['url'] ?? null,
        ])->withViewData([
            'structuredData' => $this->structuredData([$this->breadcrumbList($category)]),
        ]);
    }

    /**
     * Show a single published product with its gallery and related products.
     */
    public function show(Product $product): Response
    {
        abort_unless($product->is_published, 404);

        $product->load('images');

        $relatedProducts = Product::query()
            ->published()
            ->where('category', $product->category)
            ->whereKeyNot($product->id)
            ->with('mainImage')
            ->latest()
            ->limit(self::RELATED_LIMIT)
            ->get()
            ->map(fn (Product $related): array => $related->toCardArray());

        $productUrl = route('products.show', $product);

        return Inertia::render('Product', [
            'product' => [
                'name' => $product->name,
                'category' => ['value' => $product->category->value, 'label' => $product->category->label()],
                'price' => $product->price,
                'fulfillment_days' => $product->fulfillment_days,
                'description' => $product->description,
                'images' => $product->images->map(fn (ProductImage $image): array => $image->only(['id', 'url', 'width', 'height'])),
            ],
            'relatedProducts' => $relatedProducts,
            'canonicalUrl' => $productUrl,
            'metaDescription' => $this->metaDescription($product),
        ])->withViewData([
            'structuredData' => $this->structuredData([
                $this->productSchema($product, $productUrl),
                $this->breadcrumbList($product->category, $product),
            ]),
        ]);
    }

    /**
     * Build a meta description (max 160 characters) from the product description.
     */
    private function metaDescription(Product $product): string
    {
        $fallback = "{$product->name} – ręcznie wykonana biżuteria Aurelice z naturalnych kamieni i pereł.";
        $text = Str::squish((string) $product->description);

        return Str::limit($text !== '' ? $text : $fallback, 157);
    }

    /**
     * Wrap schema.org nodes in a single JSON-LD graph.
     *
     * @param  list<array<string, mixed>>  $nodes
     * @return array<string, mixed>
     */
    private function structuredData(array $nodes): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => $nodes,
        ];
    }

    /**
     * Build the schema.org Product node.
     *
     * @return array<string, mixed>
     */
    private function productSchema(Product $product, string $productUrl): array
    {
        return array_filter([
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->description ?: null,
            'image' => $product->images->pluck('url')->all() ?: null,
            'category' => $product->category->label(),
            'brand' => ['@type' => 'Brand', 'name' => 'Aurelice Jewellery'],
            'url' => $productUrl,
            'offers' => [
                '@type' => 'Offer',
                'price' => number_format($product->price / 100, 2, '.', ''),
                'priceCurrency' => 'PLN',
                'availability' => 'https://schema.org/InStock',
                'url' => $productUrl,
            ],
        ], fn ($value) => $value !== null);
    }

    /**
     * Build the schema.org BreadcrumbList node for catalogue and product pages.
     *
     * @return array<string, mixed>
     */
    private function breadcrumbList(?ProductCategory $category, ?Product $product = null): array
    {
        $items = [
            ['name' => 'Strona główna', 'url' => route('home')],
            ['name' => 'Produkty', 'url' => route('products.index')],
        ];

        if ($category) {
            $items[] = ['name' => $category->label(), 'url' => route('products.index', $category)];
        }

        if ($product) {
            $items[] = ['name' => $product->name, 'url' => route('products.show', $product)];
        }

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(fn (array $item, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ], $items, array_keys($items)),
        ];
    }
}
