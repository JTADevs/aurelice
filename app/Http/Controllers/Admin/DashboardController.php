<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the administration panel dashboard.
     */
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'productCount' => Product::count(),
            'publishedProductCount' => Product::published()->count(),
        ]);
    }
}
