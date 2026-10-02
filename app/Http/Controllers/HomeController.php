<?php

namespace App\Http\Controllers;

use App\Enums\ProductCategory;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Number of products shown in the new arrivals section.
     */
    private const int NEW_ARRIVALS_LIMIT = 4;

    /**
     * Show the shop home page with collections and the latest published products.
     */
    public function __invoke(): Response
    {
        $newArrivals = Product::query()
            ->published()
            ->with('mainImage')
            ->latest()
            ->limit(self::NEW_ARRIVALS_LIMIT)
            ->get()
            ->map(fn (Product $product): array => $product->toCardArray());

        return Inertia::render('Home', [
            'collections' => $this->collections(),
            'newArrivals' => $newArrivals,
        ]);
    }

    /**
     * Get every category with the main image of its newest published product that has a photo.
     *
     * @return list<array{value: string, label: string, product: ?string, image: ?array{url: string, width: int, height: int}}>
     */
    private function collections(): array
    {
        return array_map(function (ProductCategory $category): array {
            $newestProduct = Product::query()
                ->published()
                ->where('category', $category)
                ->has('mainImage')
                ->with('mainImage')
                ->latest()
                ->first();

            return [
                'value' => $category->value,
                'label' => $category->label(),
                'product' => $newestProduct?->name,
                'image' => $newestProduct?->mainImage->only(['url', 'width', 'height']),
            ];
        }, ProductCategory::cases());
    }
}
