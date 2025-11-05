<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_users' => User::where('role', 'client')->count(),
            'total_reviews' => Review::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Display a listing of products
     */
    public function index()
    {
        $products = Product::latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_gram' => 'required|numeric|min:0',
            'retail_price' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'weight_per_piece' => 'required|integer|min:1',
            'unit' => 'required|in:piece,gram',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|url',
        ]);

        Product::create($validated);
        
        return redirect()->route('admin.products.index', app()->getLocale())
            ->with('success', __('admin.product_created'));
    }

    /**
     * Display the specified product
     */
    public function show($locale, Product $product)
    {
        $product->load('reviews.user', 'orderItems');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($locale, Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $locale, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_gram' => 'required|numeric|min:0',
            'retail_price' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'weight_per_piece' => 'required|integer|min:1',
            'unit' => 'required|in:piece,gram',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|url',
        ]);

        $product->update($validated);
        
        return redirect()->route('admin.products.index', app()->getLocale())
            ->with('success', __('admin.product_updated'));
    }

    /**
     * Remove the specified product
     */
    public function destroy($locale, Product $product)
    {
        $product->delete();
        
        return redirect()->route('admin.products.index', app()->getLocale())
            ->with('success', __('admin.product_deleted'));
    }

    /**
     * Display all orders
     */
    public function orders()
    {
        $orders = Order::with('user', 'items.product')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, $locale, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'tracking' => 'nullable|string|max:255',
        ]);

        $order->update($validated);
        
        return redirect()->back()->with('success', __('admin.order_updated'));
    }

    /**
     * Display all reviews
     */
    public function reviews()
    {
        $reviews = Review::with('user', 'product')->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }
}
