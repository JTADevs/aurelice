<?php

namespace App\Http\Controllers;

use App\Enums\ProductCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Number of products per catalogue page.
     */
    private const int PER_PAGE = 24;

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
            'structuredData' => $this->breadcrumbs($category),
        ]);
    }

    /**
     * Build the BreadcrumbList structured data for the catalogue page.
     *
     * @return array<string, mixed>
     */
    private function breadcrumbs(?ProductCategory $category): array
    {
        $items = [
            ['name' => 'Strona główna', 'url' => route('home')],
            ['name' => 'Produkty', 'url' => route('products.index')],
        ];

        if ($category) {
            $items[] = ['name' => $category->label(), 'url' => route('products.index', $category)];
        }

        return [
            '@context' => 'https://schema.org',
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
