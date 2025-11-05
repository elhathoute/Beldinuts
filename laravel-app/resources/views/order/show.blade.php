@extends('layouts.app')

@section('title', __('messages.order') . ' #' . $order->id)

@section('content')
<div class="min-h-screen bg-gray-50 py-8 mt-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-warm-brown mb-8">{{ __('messages.order') }} #{{ $order->id }}</h1>
        
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-warm-brown mb-2">{{ __('messages.order_info') }}</h3>
                    <p class="text-sm text-gray-600"><strong>{{ __('messages.date') }}:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-sm text-gray-600"><strong>{{ __('messages.status') }}:</strong> 
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($order->status == 'delivered') bg-green-100 text-green-800
                            @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ __('messages.status_' . $order->status) }}
                        </span>
                    </p>
                    @if($order->tracking)
                    <p class="text-sm text-gray-600"><strong>{{ __('messages.tracking') }}:</strong> {{ $order->tracking }}</p>
                    @endif
                </div>
                
                <div>
                    <h3 class="font-semibold text-warm-brown mb-2">{{ __('messages.shipping_info') }}</h3>
                    <p class="text-sm text-gray-600"><strong>{{ __('messages.phone') }}:</strong> {{ $order->phone }}</p>
                    <p class="text-sm text-gray-600"><strong>{{ __('messages.address') }}:</strong> {{ $order->address }}</p>
                </div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('whatsapp_url'))
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-warm-brown mb-2">{{ __('messages.send_via_whatsapp') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('messages.send_order_details_whatsapp') }}</p>
                </div>
                <a href="{{ session('whatsapp_url') }}" 
                   target="_blank"
                   class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-full transition-colors inline-flex items-center">
                    <i class="fab fa-whatsapp mr-2 text-xl"></i>{{ __('messages.send_whatsapp') }}
                </a>
            </div>
        </div>
        @endif
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-warm-brown mb-4">{{ __('messages.order_items') }}</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.product') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.quantity') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.price') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.subtotal') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity_grams }}g</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->unit_price, 2) }} DH/g</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ number_format($item->unit_price * $item->quantity_grams, 2) }} DH</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">{{ __('messages.total') }}:</td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-warm-brown">{{ number_format($order->total, 2) }} DH</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

