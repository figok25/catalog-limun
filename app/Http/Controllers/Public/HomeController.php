<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::active()->orderBy('id')->take(5)->get()->each(function ($c) {
            $c->cover = $c->products()->active()->whereNotNull('image')->latest()->first()?->image_url;
        });
        $featured = Product::active()->with('category')->where('is_featured', true)->latest()->take(8)->get();

        return view('public.home', compact('categories', 'featured'));
    }
}
