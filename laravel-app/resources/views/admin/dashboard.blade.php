@extends('layouts.app')

@section('title', __('admin.dashboard'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-warm-brown mb-8">{{ __('admin.dashboard') }}</h1>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-light-gold rounded-md p-3">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">{{ __('admin.total_products') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_products'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-warm-brown rounded-md p-3">
                        <i class="fas fa-shopping-cart text-white text-xl"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">{{ __('admin.total_orders') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-earth-green rounded-md p-3">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">{{ __('admin.total_users') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">{{ __('admin.total_revenue') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_revenue'], 2) }} DH</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.products.index', app()->getLocale()) }}" 
               class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <i class="fas fa-box text-light-gold text-2xl mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-warm-brown">{{ __('admin.manage_products') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('admin.manage_products_desc') }}</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('admin.orders', app()->getLocale()) }}" 
               class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <i class="fas fa-shopping-cart text-warm-brown text-2xl mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-warm-brown">{{ __('admin.manage_orders') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('admin.manage_orders_desc') }}</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('admin.reviews', app()->getLocale()) }}" 
               class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <i class="fas fa-star text-light-gold text-2xl mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-warm-brown">{{ __('admin.manage_reviews') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('admin.manage_reviews_desc') }}</p>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-warm-brown">{{ __('admin.recent_orders') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.order_id') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.customer') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.total') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($stats['recent_orders'] as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($order->total, 2) }} DH</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($order->status == 'delivered') bg-green-100 text-green-800
                                    @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ __('admin.status_' . $order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">{{ __('admin.no_orders') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

