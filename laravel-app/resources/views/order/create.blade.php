@extends('layouts.app')

@section('title', __('messages.nav_order'))

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-warm-brown to-light-gold py-16 pt-40 mb-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
            {{ __('messages.page_title') }}
        </h1>
        <p class="text-xl text-cream mb-6">
            {{ __('messages.page_subtitle') }}
        </p>
    </div>
</section>

<!-- Products Selection -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search Bar with Currency Selector -->
        <div class="mb-12">
            <div class="max-w-4xl mx-auto">
                <div class="grid md:grid-cols-2 gap-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="product-search" 
                               placeholder="{{ __('messages.search_products') }}" 
                               class="w-full pl-10 pr-4 py-4 border border-gray-300 rounded-full text-lg focus:ring-2 focus:ring-warm-brown focus:border-transparent shadow-lg"
                               onkeyup="filterProducts(this.value)">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button onclick="clearSearch()" id="clear-search" class="text-gray-400 hover:text-warm-brown hidden">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Currency Selector -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-coins text-gray-400"></i>
                        </div>
                        <select id="currency-select" onchange="changeCurrency(this.value)" 
                                class="w-full pl-10 pr-4 py-4 border border-gray-300 rounded-full text-lg focus:ring-2 focus:ring-warm-brown focus:border-transparent shadow-lg bg-white appearance-none cursor-pointer">
                            <option value="MAD">🇲🇦 DH (Maroc)</option>
                            <option value="EUR">🇪🇺 EUR (Europe)</option>
                            <option value="USD">🇺🇸 USD (Amérique)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2 text-center">
                    {{ __('messages.search_currency_hint') }}
                </p>
            </div>
        </div>

        <!-- Sale Type Selector -->
        <div class="mb-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-warm-brown mb-4 text-center">{{ __('messages.sale_type_title') }}</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <button id="retail-btn" onclick="setSaleType('retail')" 
                                class="sale-type-btn bg-light-gold text-white py-4 px-6 rounded-full font-semibold transition-all duration-300 hover:bg-yellow-600 active">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            <span>{{ __('messages.retail_sale') }}</span>
                            <div class="text-sm opacity-90">{{ __('messages.retail_desc') }}</div>
                        </button>
                        <button id="wholesale-btn" onclick="setSaleType('wholesale')" 
                                class="sale-type-btn bg-gray-200 text-gray-700 py-4 px-6 rounded-full font-semibold transition-all duration-300 hover:bg-gray-300">
                            <i class="fas fa-boxes mr-2"></i>
                            <span>{{ __('messages.wholesale_sale') }}</span>
                            <div class="text-sm opacity-90">{{ __('messages.wholesale_desc') }}</div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Products Grid -->
            <div class="lg:col-span-2">
                <div class="grid md:grid-cols-2 gap-8" id="products-grid">
            @foreach($products as $product)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden product-card" data-product-name="{{ strtolower($product->name) }}" data-product-id="{{ $product->id }}">
                <div class="h-48 bg-cover bg-center relative group" style="background-image: url('{{ $product->image ?? asset('beldi-nuts-logo.png') }}')">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                        <a href="{{ route('products.show', [app()->getLocale(), $product]) }}" class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white bg-opacity-90 hover:bg-opacity-100 text-warm-brown p-3 rounded-full shadow-lg transform hover:scale-110 transition-transform">
                            <i class="fas fa-eye text-xl"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-warm-brown mb-3">{{ $product->name }}</h3>
                    <p class="text-gray-600 mb-4 text-sm">{{ $product->description }}</p>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl font-bold text-warm-brown price-display" 
                              data-retail-price="{{ $product->retail_price ?? $product->price_per_gram }}" 
                              data-wholesale-price="{{ $product->wholesale_price ?? $product->price_per_gram }}"
                              data-weight-per-piece="{{ $product->weight_per_piece ?? 40 }}"
                              data-unit="{{ $product->unit ?? 'piece' }}">
                            <span class="price-value">{{ number_format($product->retail_price ?? $product->price_per_gram, 2) }}</span> 
                            <span class="currency-symbol">DH</span>
                            <span class="price-unit">/{{ $product->unit == 'piece' ? __('messages.per_piece') : 'g' }}</span>
                        </span>
                        <span class="text-sm text-gray-500">{{ __('messages.stock') }}: {{ $product->stock }}g</span>
                    </div>
                    
                    <!-- Suggested Quantities -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">{{ __('messages.suggested_quantities') }}:</p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="setSuggestedQuantity('{{ $product->id }}', 100)" class="bg-gray-100 hover:bg-warm-brown hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                                100g
                            </button>
                            <button onclick="setSuggestedQuantity('{{ $product->id }}', 200)" class="bg-gray-100 hover:bg-warm-brown hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                                200g
                            </button>
                            <button onclick="setSuggestedQuantity('{{ $product->id }}', 250)" class="bg-gray-100 hover:bg-warm-brown hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                                250g
                            </button>
                            <button onclick="setSuggestedQuantity('{{ $product->id }}', 500)" class="bg-gray-100 hover:bg-warm-brown hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                                500g
                            </button>
                            <button onclick="setSuggestedQuantity('{{ $product->id }}', 1000)" class="bg-gray-100 hover:bg-warm-brown hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                                1000g
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <button onclick="decreaseQuantity('{{ $product->id }}')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <div class="relative">
                                <input type="number" id="qty-{{ $product->id }}" value="0" min="0" max="{{ $product->stock }}" step="50" 
                                       onchange="updateQuantity('{{ $product->id }}', this.value)" 
                                       oninput="updateQuantity('{{ $product->id }}', this.value)"
                                       class="text-lg font-semibold w-24 text-center border border-gray-300 rounded px-2 py-1 pr-12 focus:ring-2 focus:ring-warm-brown focus:border-transparent">
                                <span class="absolute right-2 top-1/2 transform -translate-y-1/2 text-sm text-gray-500 font-medium">g</span>
                            </div>
                            <button onclick="increaseQuantity('{{ $product->id }}')" class="bg-warm-brown hover:bg-brown-700 text-white w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                        </div>
                        <span id="total-{{ $product->id }}" class="text-lg font-bold text-warm-brown">0 <span class="currency-symbol">DH</span></span>
                    </div>
                    
                    <!-- Reviews Section -->
                    @if($product->reviews->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        @php
                            $avgRating = $product->reviews->avg('rating');
                            $reviewCount = $product->reviews->count();
                        @endphp
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= round($avgRating) ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600 ml-2">{{ number_format($avgRating, 1) }} ({{ $reviewCount }} {{ __('messages.reviews') }})</span>
                            </div>
                        </div>
                        
                        <!-- Recent Reviews -->
                        <div class="space-y-2">
                            @foreach($product->reviews->take(2) as $review)
                            <div class="text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-gray-800">{{ substr($review->user->name, 0, 10) }}.</span>
                                    <div class="flex text-yellow-400 text-xs">
                                        @for($i = 1; $i <= $review->rating; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                <p class="text-gray-600 text-xs mt-1">"{{ Str::limit($review->comment, 50) }}"</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-8 sticky top-24">
                    <!-- Customer Information -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-warm-brown mb-6">{{ __('messages.customer_info_title') }}</h2>
                        
                        <form method="POST" action="{{ route('order.store', app()->getLocale()) }}" id="order-form">
                            @csrf
                            <input type="hidden" name="items" id="order-items">
                            
                            <div class="space-y-4">
                                @if(!auth()->check())
                                <div>
                                    <label for="customer-name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.name') }} *</label>
                                    <input type="text" id="customer-name" name="name" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warm-brown focus:border-transparent text-sm">
                                </div>
                                
                                <div>
                                    <label for="customer-email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.email') }} *</label>
                                    <input type="email" id="customer-email" name="email" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warm-brown focus:border-transparent text-sm">
                                </div>
                                @endif
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.phone') }} *</label>
                                    <input type="tel" name="phone" id="phone" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warm-brown focus:border-transparent text-sm"
                                           value="{{ auth()->user()->phone ?? old('phone') }}">
                                    @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.address') }} *</label>
                                    <textarea name="address" id="address" rows="3" required 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warm-brown focus:border-transparent text-sm"
                                              placeholder="{{ __('messages.address_placeholder') }}">{{ auth()->user()->address ?? old('address') }}</textarea>
                                    @error('address')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="border-t pt-8">
                        <h2 class="text-2xl font-bold text-warm-brown mb-6">{{ __('messages.order_summary') }}</h2>
                        
                        <div id="cart-items" class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                            <p class="text-gray-500 text-center">{{ __('messages.empty_cart') }}</p>
                        </div>
                        
                        <div class="border-t pt-4 mb-6">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">{{ __('messages.subtotal') }}:</span>
                                <span id="subtotal" class="font-semibold">0.00 <span class="currency-symbol">DH</span></span>
                            </div>
                            <div class="flex justify-between text-xl font-bold text-warm-brown mb-2">
                                <span>{{ __('messages.total') }}:</span>
                                <span id="total">0.00 <span class="currency-symbol">DH</span></span>
                            </div>
                            <p id="min-order-text" class="text-xs text-gray-500 mt-2">{{ __('messages.minimum_order') }}: 100 DH (50g)</p>
                        </div>
                        
                        @if($errors->has('total'))
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-600">{{ $errors->first('total') }}</p>
                            </div>
                        @endif
                        
                        <button type="submit" form="order-form" id="submit-btn" disabled
                                class="w-full bg-light-gold hover:bg-yellow-600 text-white font-semibold py-4 px-6 rounded-full transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed text-lg">
                            @auth
                                <i class="fas fa-shopping-cart mr-2"></i>{{ __('messages.place_order') }}
                            @else
                                <i class="fab fa-whatsapp mr-2"></i>{{ __('messages.send_via_whatsapp') }}
                            @endauth
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
let cart = {};
let currentCurrency = 'MAD';
let saleType = 'retail'; // 'retail' or 'wholesale'
const exchangeRates = {
    'MAD': 1,
    'EUR': 0.09,
    'USD': 0.10
};

@php
    $productsArray = $products->keyBy('id')->map(function($p) {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'price_per_gram' => (float)$p->price_per_gram,
            'retail_price' => (float)($p->retail_price ?? $p->price_per_gram),
            'wholesale_price' => (float)($p->wholesale_price ?? $p->price_per_gram),
            'weight_per_piece' => (int)($p->weight_per_piece ?? 40),
            'unit' => $p->unit ?? 'piece',
            'stock' => $p->stock
        ];
    })->toArray();
@endphp
const products = @json($productsArray);

function getCurrencySymbol() {
    const symbols = {
        MAD: 'DH',
        EUR: '€',
        USD: '$'
    };
    return symbols[currentCurrency];
}

function setSaleType(type) {
    saleType = type;
    
    // Update buttons
    document.querySelectorAll('.sale-type-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-light-gold', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    if (type === 'retail') {
        document.getElementById('retail-btn').classList.add('active', 'bg-light-gold', 'text-white');
        document.getElementById('retail-btn').classList.remove('bg-gray-200', 'text-gray-700');
    } else {
        document.getElementById('wholesale-btn').classList.add('active', 'bg-light-gold', 'text-white');
        document.getElementById('wholesale-btn').classList.remove('bg-gray-200', 'text-gray-700');
    }
    
    // Reset cart
    cart = {};
    
    // Update all prices
    updateAllPrices();
    updateCartDisplay();
    updateOrderForm();
}

function getPrice(productId) {
    const product = products[productId];
    if (!product) return 0;
    
    if (saleType === 'wholesale') {
        return product.wholesale_price || 0;
    } else {
        return product.retail_price || product.price_per_gram || 0;
    }
}

function updateAllPrices() {
    document.querySelectorAll('.price-display').forEach(el => {
        const productCard = el.closest('.product-card');
        if (!productCard) return;
        
        const productId = productCard.dataset.productId;
        if (!productId) return;
        
        const product = products[productId];
        if (!product) return;
        
        const price = getPrice(productId);
        const convertedPrice = price * exchangeRates[currentCurrency];
        const unit = product.unit || 'piece';
        const unitText = unit === 'piece' ? '{{ __('messages.per_piece') }}' : 'g';
        
        const priceValue = el.querySelector('.price-value');
        const priceUnit = el.querySelector('.price-unit');
        
        if (priceValue) {
            priceValue.textContent = convertedPrice.toFixed(2);
        }
        if (priceUnit) {
            priceUnit.textContent = '/' + unitText;
        }
    });
}

function setSuggestedQuantity(productId, quantity) {
    document.getElementById('qty-' + productId).value = quantity;
    updateQuantity(productId, quantity);
}

function increaseQuantity(productId) {
    const input = document.getElementById('qty-' + productId);
    const current = parseInt(input.value) || 0;
    const step = parseInt(input.step) || 50;
    const max = parseInt(input.max) || 10000;
    input.value = Math.min(current + step, max);
    updateQuantity(productId, input.value);
}

function decreaseQuantity(productId) {
    const input = document.getElementById('qty-' + productId);
    const current = parseInt(input.value) || 0;
    const step = parseInt(input.step) || 50;
    input.value = Math.max(current - step, 0);
    updateQuantity(productId, input.value);
}

function updateQuantity(productId, quantity) {
    quantity = parseInt(quantity) || 0;
    if (quantity > 0) {
        cart[productId] = quantity;
    } else {
        delete cart[productId];
    }
    updateProductDisplay(productId);
    updateCartDisplay();
    updateOrderForm();
}

function updateProductDisplay(productId) {
    const product = products[productId];
    if (!product) return;
    
    const quantity = cart[productId] || 0;
    const basePrice = getPrice(productId);
    const price = basePrice * exchangeRates[currentCurrency];
    
    // Calculate total based on unit
    let total;
    if (product.unit === 'piece') {
        // If selling by piece, quantity is number of pieces
        total = price * quantity;
    } else {
        // If selling by gram, calculate price per gram from piece price
        const weightPerPiece = product.weight_per_piece || 40;
        const pricePerGram = price / weightPerPiece;
        total = pricePerGram * quantity;
    }
    
    const totalEl = document.getElementById('total-' + productId);
    if (totalEl) {
        totalEl.innerHTML = total.toFixed(2) + ' <span class="currency-symbol">' + getCurrencySymbol() + '</span>';
    }
}

function updateCartDisplay() {
    const container = document.getElementById('cart-items');
    const subtotalEl = document.getElementById('subtotal');
    const totalEl = document.getElementById('total');
    const submitBtn = document.getElementById('submit-btn');
    const minOrderText = document.getElementById('min-order-text');
    
    let subtotal = 0;
    container.innerHTML = '';
    
    if (Object.keys(cart).length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center">{{ __("messages.empty_cart") }}</p>';
        subtotalEl.innerHTML = '0.00 <span class="currency-symbol">DH</span>';
        totalEl.innerHTML = '0.00 <span class="currency-symbol">DH</span>';
        submitBtn.disabled = true;
        return;
    }
    
    Object.keys(cart).forEach(productId => {
        const product = products[productId];
        const quantity = cart[productId];
        const basePrice = getPrice(productId);
        const price = basePrice * exchangeRates[currentCurrency];
        
        let itemTotal;
        let quantityText;
        
        if (product.unit === 'piece') {
            // Selling by piece
            itemTotal = price * quantity;
            quantityText = `${quantity} {{ __('messages.per_piece') }}`;
        } else {
            // Selling by gram
            const weightPerPiece = product.weight_per_piece || 40;
            const pricePerGram = price / weightPerPiece;
            itemTotal = pricePerGram * quantity;
            quantityText = `${quantity}g`;
        }
        
        subtotal += itemTotal;
        
        const itemDiv = document.createElement('div');
        itemDiv.className = 'flex justify-between items-center text-sm pb-2 border-b border-gray-100';
        itemDiv.innerHTML = `
            <div>
                <p class="font-semibold text-gray-800">${product.name}</p>
                <p class="text-gray-500 text-xs">${quantityText}</p>
            </div>
            <span class="font-semibold text-warm-brown">${itemTotal.toFixed(2)} <span class="currency-symbol">${getCurrencySymbol()}</span></span>
        `;
        container.appendChild(itemDiv);
    });
    
    const total = subtotal;
    const currencySymbol = getCurrencySymbol();
    subtotalEl.innerHTML = subtotal.toFixed(2) + ' <span class="currency-symbol">' + currencySymbol + '</span>';
    totalEl.innerHTML = total.toFixed(2) + ' <span class="currency-symbol">' + currencySymbol + '</span>';
    
    // Update minimum order text based on sale type
    const minOrderAmountMAD = saleType === 'wholesale' ? 500 : 100;
    const minOrderAmount = minOrderAmountMAD * exchangeRates[currentCurrency];
    if (minOrderText) {
        const minOrderDesc = saleType === 'wholesale' ? '{{ __('messages.wholesale_sale') }}' : '50g';
        minOrderText.textContent = `{{ __('messages.minimum_order') }}: ${minOrderAmount.toFixed(2)} ${currencySymbol} (${minOrderDesc})`;
    }
    
    // Enable submit if minimum order met
    const totalInMAD = subtotal / exchangeRates[currentCurrency];
    submitBtn.disabled = totalInMAD < minOrderAmountMAD;
}

function updateOrderForm() {
    const items = [];
    Object.keys(cart).forEach(productId => {
        const product = products[productId];
        const quantity = cart[productId];
        
        let quantityGrams = quantity;
        let quantityPieces = null;
        
        if (product.unit === 'piece') {
            quantityPieces = quantity;
            quantityGrams = quantity * (product.weight_per_piece || 40);
        }
        
        items.push({
            product_id: parseInt(productId),
            quantity_grams: quantityGrams,
            quantity_pieces: quantityPieces,
            sale_type: saleType
        });
    });
    document.getElementById('order-items').value = JSON.stringify(items);
}

function changeCurrency(currency) {
    currentCurrency = currency;
    document.querySelectorAll('.currency-symbol').forEach(el => {
        el.textContent = getCurrencySymbol();
    });
    
    // Update all prices
    updateAllPrices();
    
    // Update all product totals
    Object.keys(cart).forEach(productId => {
        updateProductDisplay(productId);
    });
    
    updateCartDisplay();
}

function filterProducts(search) {
    const searchLower = search.toLowerCase();
    const clearBtn = document.getElementById('clear-search');
    
    if (searchLower.length > 0) {
        clearBtn.classList.remove('hidden');
    } else {
        clearBtn.classList.add('hidden');
    }
    
    document.querySelectorAll('.product-card').forEach(card => {
        const productName = card.dataset.productName;
        card.style.display = productName.includes(searchLower) ? 'block' : 'none';
    });
}

function clearSearch() {
    document.getElementById('product-search').value = '';
    filterProducts('');
}

// Initialize
updateCartDisplay();
</script>
@endpush
@endsection
