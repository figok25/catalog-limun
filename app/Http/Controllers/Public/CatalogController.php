<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->orderBy('name')->get();
        $slug = $request->string('kategori')->toString();
        $q = trim($request->string('q')->toString());

        $products = Product::active()->with('category')
            ->whereHas('category', fn ($c) => $c->where('is_active', true))
            ->when($slug, fn ($x) => $x->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when($q, fn ($x) => $x->where('name', 'like', '%' . $q . '%'))
            ->latest()->paginate(12)->withQueryString();

        return view('public.catalog.index', compact('products', 'categories', 'slug', 'q'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        $product->load('category');
        $related = Product::active()->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)->latest()->take(4)->get();

        return view('public.catalog.show', compact('product', 'related'));
    }
}
