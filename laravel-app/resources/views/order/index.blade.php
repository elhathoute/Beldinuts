@extends('layouts.app')

@section('title', __('messages.my_orders'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-warm-brown mb-8">
            @auth
                {{ __('messages.my_orders') }}
            @else
                {{ __('messages.track_order') }}
            @endauth
        </h1>
        
        @guest
        <!-- Phone Number Search Form for Guest Users -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 max-w-md mx-auto">
            <h2 class="text-xl font-semibold text-warm-brown mb-4">{{ __('messages.search_by_phone') }}</h2>
            <form method="POST" action="{{ route('orders.search', app()->getLocale()) }}">
                @csrf
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.phone') }}
                    </label>
                    <input type="tel" 
                           name="phone" 
                           id="phone" 
                           required
                           value="{{ old('phone', $phone ?? '') }}"
                           placeholder="+212 615919437"
                           class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-light-gold focus:border-light-gold">
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" 
                        class="w-full bg-light-gold hover:bg-yellow-600 text-white font-semibold py-2 px-6 rounded-full transition-colors">
                    <i class="fas fa-search mr-2"></i>{{ __('messages.search') }}
                </button>
            </form>
            @if(isset($phone) && !empty($phone) && $orders->isEmpty())
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                    <p class="text-yellow-800 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ __('messages.no_orders_found') }}
                    </p>
                </div>
            @endif
        </div>
        @endguest
        
        <div class="space-y-6">
            @forelse($orders as $order)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-warm-brown">{{ __('messages.order') }} #{{ $order->id }}</h3>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-warm-brown">{{ number_format($order->total, 2) }} DH</p>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($order->status == 'delivered') bg-green-100 text-green-800
                            @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ __('messages.status_' . $order->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <h4 class="font-semibold mb-2">{{ __('messages.items') }}:</h4>
                    <ul class="space-y-2">
                        @foreach($order->items as $item)
                        <li class="flex justify-between text-sm">
                            <span>{{ $item->product->name }} ({{ $item->quantity_grams }}g)</span>
                            <span>{{ number_format($item->unit_price * $item->quantity_grams, 2) }} DH</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                @if($order->tracking)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm"><strong>{{ __('messages.tracking') }}:</strong> {{ $order->tracking }}</p>
                </div>
                @endif
                
                <div class="mt-4">
                    <a href="{{ route('order.show', $order) }}" 
                       class="text-light-gold hover:text-yellow-600 text-sm">
                        {{ __('messages.view_details') }} →
                    </a>
                </div>
            </div>
            @empty
            @auth
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">{{ __('messages.no_orders') }}</p>
                <a href="{{ route('order.create', app()->getLocale()) }}" 
                   class="mt-4 inline-block bg-light-gold hover:bg-yellow-600 text-white px-6 py-2 rounded-full">
                    {{ __('messages.start_shopping') }}
                </a>
            </div>
            @else
            @if(!session('phone'))
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-mobile-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">{{ __('messages.enter_phone_to_search') }}</p>
            </div>
            @endif
            @endauth
            @endforelse
        </div>
    </div>
</div>
@endsection

