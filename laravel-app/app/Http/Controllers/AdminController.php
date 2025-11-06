<?php

namespace App\Http\Controllers;

use App\Http\Controllers\OrderController;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductPhoto;
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
            'unread_notifications' => Notification::where('is_read', false)->count(),
            'recent_notifications' => Notification::with('product', 'order')->latest()->take(10)->get(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with('photos');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $products = $query->latest()->paginate(15)->withQueryString();
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
            'price_per_gram_retail' => 'required|numeric|min:0',
            'price_per_gram_wholesale' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::create($validated);
        
        // Handle photo uploads
        if ($request->hasFile('photos')) {
            $order = 0;
            foreach ($request->file('photos') as $photo) {
                if ($photo->isValid()) {
                    $path = $photo->store('products/photos', 'public');
                    $product->photos()->create([
                        'photo_path' => $path,
                        'order' => $order++,
                    ]);
                }
            }
        }

        // Check if stock is 0 and create notification
        if ($product->stock == 0) {
            Notification::create([
                'type' => 'product_out_of_stock',
                'title' => __('admin.notification_product_out_of_stock_title'),
                'message' => __('admin.notification_product_out_of_stock_message', ['name' => $product->name]),
                'product_id' => $product->id,
            ]);
        }
        
        return redirect()->route('admin.products.index', app()->getLocale())
            ->with('success', __('admin.product_created'));
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('reviews.user', 'orderItems');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $product->load('photos');
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_gram_retail' => 'required|numeric|min:0',
            'price_per_gram_wholesale' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $oldStock = $product->stock;
        $product->update($validated);

        // Check if stock became 0 and create notification
        if ($oldStock > 0 && $product->stock == 0) {
            Notification::create([
                'type' => 'product_out_of_stock',
                'title' => __('admin.notification_product_out_of_stock_title'),
                'message' => __('admin.notification_product_out_of_stock_message', ['name' => $product->name]),
                'product_id' => $product->id,
            ]);
        }
        
        return redirect()->route('admin.products.index', app()->getLocale())
            ->with('success', __('admin.product_updated'));
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('admin.products.index', app()->getLocale())
            ->with('success', __('admin.product_deleted'));
    }

    /**
     * Display all orders
     */
    public function orders(Request $request)
    {
        $query = Order::with('user', 'items.product');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // If search is numeric, try exact ID match, otherwise try LIKE
                if (is_numeric($search)) {
                    $q->where('id', $search);
                } else {
                    $q->whereRaw('CAST(id AS CHAR) LIKE ?', ["%{$search}%"]);
                }
                $q->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->latest()->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'tracking' => 'nullable|string|max:255',
        ]);

        $oldStatus = $order->status;
        $order->update($validated);

        // Generate invoice when order is shipped
        if ($validated['status'] === 'shipped' && $oldStatus !== 'shipped') {
            try {
                $orderController = app(OrderController::class);
                $orderController->generateInvoice($order);
                \Log::info('Invoice generated for order', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                \Log::error('Failed to generate invoice for order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Create notification for status changes (pending, confirmed, cancelled, shipped)
        $notifiableStatuses = ['pending', 'confirmed', 'cancelled', 'shipped'];
        if (in_array($validated['status'], $notifiableStatuses) && $oldStatus != $validated['status']) {
            $statusLabels = [
                'pending' => __('admin.status_pending'),
                'confirmed' => __('admin.status_confirmed'),
                'cancelled' => __('admin.status_cancelled'),
                'shipped' => __('admin.status_shipped'),
            ];

            Notification::create([
                'type' => 'order_status_changed',
                'title' => __('admin.notification_order_status_changed_title'),
                'message' => __('admin.notification_order_status_changed_message', [
                    'order_id' => $order->id,
                    'status' => $statusLabels[$validated['status']] ?? $validated['status']
                ]),
                'order_id' => $order->id,
                'status' => $validated['status'],
            ]);
        }
        
        return redirect()->back()->with('success', __('admin.order_updated'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    /**
     * Display all reviews
     */
    public function reviews(Request $request)
    {
        $query = Review::with('user', 'product');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $reviews = $query->latest()->paginate(20)->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Store a new photo for a product
     */
    public function storePhoto(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $file = $request->file('photo');

            if (!$file->isValid()) {
                return redirect()->back()->with('error', __('admin.photo_upload_error') . ': ' . $file->getError());
            }

            $path = $file->store('products/photos', 'public');
            
            if (!$path) {
                return redirect()->back()->with('error', __('admin.photo_storage_error'));
            }

            $maxOrder = $product->photos()->max('order') ?? -1;
            
            $photo = $product->photos()->create([
                'photo_path' => $path,
                'order' => $maxOrder + 1,
            ]);

            \Log::info('Photo created successfully', [
                'photo_id' => $photo->id,
                'path' => $path,
            ]);

            return redirect()->back()->with('success', __('admin.photo_uploaded'));
        } catch (\Exception $e) {
            \Log::error('Photo upload error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'product_id' => $product->id ?? null,
            ]);
            
            return redirect()->back()->with('error', __('admin.photo_upload_error') . ': ' . $e->getMessage());
        }
    }

    /**
     * Delete a product photo
     */
    public function deletePhoto(Product $product, ProductPhoto $photo)
    {
        // Verify the photo belongs to this product
        if ($photo->product_id !== $product->id) {
            return redirect()->back()->with('error', __('admin.photo_not_found'));
        }

        // Delete the file from storage
        if (Storage::disk('public')->exists($photo->photo_path)) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        // Delete the record
        $photo->delete();

        return redirect()->back()->with('success', __('admin.photo_deleted'));
    }

    /**
     * Update photo order
     */
    public function updatePhotoOrder(Request $request, Product $product, ProductPhoto $photo)
    {
        $request->validate([
            'order' => 'required|integer|min:0',
        ]);

        $photo->update(['order' => $request->order]);

        return response()->json(['success' => true]);
    }
}
