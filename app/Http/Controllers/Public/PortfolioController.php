<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;

class PortfolioController extends Controller
{
    public function index()
    {
        $items = Portfolio::active()
            ->has('images')
            ->with('images')
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(12);

        return view('public.portfolio.index', compact('items'));
    }
}
