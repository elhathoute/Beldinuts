<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('product.index', compact('products'));
    }

    public function show($locale, Product $product)
    {
        $product->load('reviews.user');
        return view('product.show', compact('product'));
    }
}
