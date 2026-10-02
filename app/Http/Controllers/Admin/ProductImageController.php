<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductImageController extends Controller
{
    /**
     * Make the image the main one by moving it to the front of the gallery.
     */
    public function update(Product $product, ProductImage $image): RedirectResponse
    {
        DB::transaction(function () use ($product, $image): void {
            $otherImages = $product->images()->whereKeyNot($image->id)->get();

            $image->update(['sort_order' => 0]);

            $otherImages->each(fn (ProductImage $other, int $index) => $other->update(['sort_order' => $index + 1]));
        });

        Inertia::flash('success', 'Ustawiono zdjęcie główne.');

        return back();
    }

    /**
     * Delete the image and its file.
     */
    public function destroy(Product $product, ProductImage $image): RedirectResponse
    {
        $image->delete();

        Storage::disk(ProductImage::DISK)->delete($image->path);

        Inertia::flash('success', 'Usunięto zdjęcie.');

        return back();
    }
}
