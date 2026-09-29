<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'total' => Product::count(),
            'active' => Product::active()->count(),
            'featured' => Product::where('is_featured', true)->count(),
            'categories' => Category::count(),
            'latest' => Product::with('category')->latest()->take(5)->get(),
        ]);
    }
}
