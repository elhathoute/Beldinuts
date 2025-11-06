<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class OrderController extends Controller
{
    public function create()
    {
        $products = Product::where('stock', '>', 0)->with('reviews.user', 'photos')->get();
        return view('order.create', compact('products'));
    }

    public function store(Request $request)
    {
        $itemsData = json_decode($request->items, true);
        if (!is_array($itemsData)) {
            return back()->withErrors(['items' => __('messages.invalid_items')])->withInput();
        }
        
        $validator = Validator::make([
            'items' => $itemsData,
            'phone' => $request->phone,
            'address' => $request->address,
        ], [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_grams' => 'required|integer|min:50',
            'items.*.sale_type' => 'nullable|in:retail,wholesale',
            'items.*.quantity_pieces' => 'nullable|integer|min:1',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $total = 0;
        $items = [];

        $saleType = 'retail'; // Default to retail
        
        foreach ($itemsData as $item) {
            $product = Product::findOrFail($item['product_id']);
            $quantityGrams = $item['quantity_grams'];
            $saleType = $item['sale_type'] ?? 'retail';
            $quantityPieces = $item['quantity_pieces'] ?? null;
            
            // Calculate price based on sale type
            $unitPrice = 0;
            if ($saleType === 'wholesale' && $product->wholesale_price) {
                // Wholesale: price per piece
                if ($quantityPieces) {
                    $unitPrice = $product->wholesale_price;
                    $subtotal = $unitPrice * $quantityPieces;
                } else {
                    // Fallback: calculate from grams
                    $weightPerPiece = $product->weight_per_piece ?? 40;
                    $pieces = ceil($quantityGrams / $weightPerPiece);
                    $unitPrice = $product->wholesale_price;
                    $subtotal = $unitPrice * $pieces;
                }
            } else {
                // Retail: use retail_price or price_per_gram
                if ($product->retail_price && $quantityPieces) {
                    $unitPrice = $product->retail_price;
                    $subtotal = $unitPrice * $quantityPieces;
                } else {
                    // Calculate by grams
                    if ($product->retail_price && $product->weight_per_piece) {
                        $pricePerGram = $product->retail_price / $product->weight_per_piece;
                    } else {
                        $pricePerGram = $product->price_per_gram;
                    }
                    $unitPrice = $pricePerGram;
                    $subtotal = $pricePerGram * $quantityGrams;
                }
            }
            
            $total += $subtotal;
            
            $items[] = [
                'product' => $product,
                'quantity_grams' => $quantityGrams,
                'quantity_pieces' => $quantityPieces,
                'sale_type' => $saleType,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ];
        }

        // Validate minimum order based on sale type
        $minOrder = $saleType === 'wholesale' ? 500 : 100;
        if ($total < $minOrder) {
            return back()->withErrors(['total' => __('messages.minimum_order') . ': ' . $minOrder . ' DH'])->withInput();
        }

        // Get or create user by phone number
        $userId = null;
        if (Auth::check()) {
            // If user is logged in, use their ID
            $userId = Auth::id();
            // Update address if provided
            if ($request->address) {
                Auth::user()->update(['address' => $request->address]);
            }
        } else {
            // Find or create user by phone number
            $user = User::where('phone', $request->phone)->first();
            
            if (!$user) {
                // Create new guest user
                $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
                $email = 'guest_' . $cleanPhone . '@beldinuts.local';
                
                // Ensure email is unique
                $counter = 1;
                while (User::where('email', $email)->exists()) {
                    $email = 'guest_' . $cleanPhone . '_' . $counter . '@beldinuts.local';
                    $counter++;
                }
                
                $user = User::create([
                    'name' => 'Guest ' . substr($request->phone, -4), // Use last 4 digits as name
                    'email' => $email,
                    'password' => Hash::make(uniqid()), // Random password since they won't login
                    'role' => 'client',
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);
            } else {
                // Update address if user exists
                if ($request->address) {
                    $user->update(['address' => $request->address]);
                }
            }
            
            $userId = $user->id;
        }

        // Create order
        $order = Order::create([
            'user_id' => $userId,
            'total' => $total,
            'status' => 'pending',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Create order items
        foreach ($items as $item) {
            $order->items()->create([
                'product_id' => $item['product']->id,
                'sale_type' => $item['sale_type'],
                'quantity_grams' => $item['quantity_grams'],
                'quantity_pieces' => $item['quantity_pieces'],
                'unit_price' => $item['unit_price'],
            ]);
        }

        // Load order items for notification
        $order->load('items.product');

        // Create notification for new order
        Notification::create([
            'type' => 'new_order',
            'title' => __('admin.notification_new_order_title'),
            'message' => __('admin.notification_new_order_message', [
                'order_id' => $order->id,
                'total' => number_format($order->total, 2),
                'customer' => $order->user->name ?? $order->phone
            ]),
            'order_id' => $order->id,
        ]);

        // Generate WhatsApp notification URL
        $whatsappUrl = $this->sendWhatsAppNotification($order);

        $locale = app()->getLocale();
        return redirect()->to("/{$locale}/order/{$order->id}")
            ->with('success', __('messages.order_success'))
            ->with('whatsapp_url', $whatsappUrl);
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('order.show', compact('order'));
    }

    /**
     * Generate and save PDF invoice for order
     */
    public function generateInvoice(Order $order)
    {
        $order->load('items.product', 'user');
        
        // Configure DomPDF options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new Dompdf($options);
        
        // Get the logo path
        $logoPath = public_path('beldi-nuts-logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }
        
        // Generate HTML for invoice
        $html = View::make('order.invoice', [
            'order' => $order,
            'logo' => $logoBase64,
        ])->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Save PDF to storage
        $invoicePath = 'invoices/invoice-' . $order->id . '-' . time() . '.pdf';
        Storage::disk('public')->put($invoicePath, $dompdf->output());
        
        // Update order with invoice path
        $order->update(['invoice_path' => $invoicePath]);
        
        return $invoicePath;
    }

    /**
     * Generate invoice from view (POST request)
     */
    public function generateInvoiceFromView(Order $order)
    {
        try {
            $this->generateInvoice($order);
            return redirect()->back()->with('success', __('messages.invoice_generated_successfully'));
        } catch (\Exception $e) {
            \Log::error('Failed to generate invoice from view', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', __('messages.invoice_generation_failed'));
        }
    }

    /**
     * Download PDF invoice for order
     */
    public function invoice(Order $order)
    {
        $order->load('items.product', 'user');
        
        // If invoice already exists, use it
        if ($order->invoice_path && Storage::disk('public')->exists($order->invoice_path)) {
            return Storage::disk('public')->download($order->invoice_path, 'invoice-' . $order->id . '.pdf');
        }
        
        // Otherwise generate new invoice
        // Configure DomPDF options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new Dompdf($options);
        
        // Get the logo path
        $logoPath = public_path('beldi-nuts-logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }
        
        // Generate HTML for invoice
        $html = View::make('order.invoice', [
            'order' => $order,
            'logo' => $logoBase64,
        ])->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->stream('invoice-' . $order->id . '.pdf', ['Attachment' => true]);
    }

    public function index(Request $request)
    {
        if (Auth::check()) {
            // If user is authenticated, show their orders
            $orders = Auth::user()->orders()->with('items.product')->latest()->get();
            return view('order.index', compact('orders'));
        } else {
            // If not authenticated, show search form
            $orders = collect(); // Empty collection
            $phone = $request->get('phone', '');
            return view('order.index', compact('orders', 'phone'));
        }
    }

    public function searchByPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        // Find user by phone number
        $user = User::where('phone', $request->phone)->first();
        $phone = $request->phone;
        
        if ($user) {
            $orders = $user->orders()->with('items.product')->latest()->get();
        } else {
            $orders = collect(); // Empty collection if no user found
        }

        return view('order.index', compact('orders', 'phone'));
    }

    private function sendWhatsAppNotification(Order $order)
    {
        // Default WhatsApp number: +212 615919437 (Morocco)
        // Format: 212615919437 (international format without +)
        $phone = env('WHATSAPP_PHONE', '212615919437');
        
        // Clean phone number (remove spaces, dashes, +, etc.)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        $message = "🛒 *Nouvelle Commande BeldiNuts*\n\n";
        $message .= "Commande #{$order->id}\n";
        $message .= "Total: {$order->total} DH\n\n";
        $message .= "Produits:\n";
        
        foreach ($order->items as $item) {
            $message .= "• {$item->product->name}: {$item->quantity_grams}g\n";
        }
        
        $message .= "\nClient: {$order->phone}\n";
        $message .= "Adresse: {$order->address}";
        
        $url = "https://wa.me/{$phone}?text=" . urlencode($message);
        
        // In production, you would use a service like Twilio or a WhatsApp Business API
        // For now, this returns the WhatsApp URL that can be opened to send the message
        return $url;
    }
}
