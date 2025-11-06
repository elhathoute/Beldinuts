@extends('layouts.app')

@section('title', __('admin.products'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-warm-brown">{{ __('admin.products') }}</h1>
            <a href="{{ route('admin.products.create', app()->getLocale()) }}" 
               class="bg-light-gold hover:bg-yellow-600 text-white px-6 py-2 rounded-full transition-colors">
                <i class="fas fa-plus mr-2"></i>{{ __('admin.add_product') }}
            </a>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('admin.products.index', app()->getLocale()) }}" class="flex items-center gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="{{ __('admin.search_placeholder') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-light-gold focus:border-light-gold">
                </div>
                <button type="submit" class="bg-light-gold hover:bg-yellow-600 text-white px-6 py-2 rounded-md transition-colors">
                    <i class="fas fa-search mr-2"></i>{{ __('admin.search') }}
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.products.index', app()->getLocale()) }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-md transition-colors">
                        {{ __('admin.clear') }}
                    </a>
                @endif
            </form>
        </div>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.price') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.stock') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                    <!-- Retail Price Row -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap" rowspan="2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $product->photos->first() ? asset('storage/' . $product->photos->first()->photo_path) : asset('beldi-nuts-logo.png') }}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($product->price_per_gram_retail, 2) }} DH/g ({{ __('admin.price_per_gram_retail') }})
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" rowspan="2">{{ $product->stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" rowspan="2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-light-gold hover:text-yellow-600 mr-4">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <!-- Wholesale Price Row -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($product->price_per_gram_wholesale, 2) }} DH/g ({{ __('admin.price_per_gram_wholesale') }})
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">{{ __('admin.no_products') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

