@extends('layouts.app')

@section('title', __('admin.edit_product'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8 mt-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-warm-brown mb-8">{{ __('admin.edit_product') }}</h1>
        
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.products.update', [app()->getLocale(), $product]) }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('admin.name') }}</label>
                        <input type="text" name="name" id="name" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                               value="{{ old('name', $product->name) }}">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">{{ __('admin.description') }}</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold">{{ old('description', $product->description) }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price_per_gram" class="block text-sm font-medium text-gray-700">{{ __('admin.price_per_gram') }}</label>
                            <input type="number" step="0.01" name="price_per_gram" id="price_per_gram" required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                                   value="{{ old('price_per_gram', $product->price_per_gram) }}">
                            @error('price_per_gram')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700">{{ __('admin.stock') }}</label>
                            <input type="number" name="stock" id="stock" required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                                   value="{{ old('stock', $product->stock) }}">
                            @error('stock')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Section Prix et Configuration -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-warm-brown mb-4">{{ __('admin.pricing_configuration') }}</h3>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="retail_price" class="block text-sm font-medium text-gray-700">{{ __('admin.retail_price') }} ({{ __('admin.per_piece') }})</label>
                                <input type="number" step="0.01" name="retail_price" id="retail_price" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                                       value="{{ old('retail_price', $product->retail_price) }}" placeholder="Ex: 2.5">
                                <p class="mt-1 text-xs text-gray-500">{{ __('admin.retail_price_desc') }}</p>
                                @error('retail_price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            
                            <div>
                                <label for="wholesale_price" class="block text-sm font-medium text-gray-700">{{ __('admin.wholesale_price') }} ({{ __('admin.per_piece') }})</label>
                                <input type="number" step="0.01" name="wholesale_price" id="wholesale_price" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                                       value="{{ old('wholesale_price', $product->wholesale_price) }}" placeholder="Ex: 2.0">
                                <p class="mt-1 text-xs text-gray-500">{{ __('admin.wholesale_price_desc') }}</p>
                                @error('wholesale_price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="weight_per_piece" class="block text-sm font-medium text-gray-700">{{ __('admin.weight_per_piece') }} (g)</label>
                                <input type="number" name="weight_per_piece" id="weight_per_piece" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                                       value="{{ old('weight_per_piece', $product->weight_per_piece ?? 40) }}">
                                <p class="mt-1 text-xs text-gray-500">{{ __('admin.weight_per_piece_desc') }}</p>
                                @error('weight_per_piece')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700">{{ __('admin.unit') }}</label>
                                <select name="unit" id="unit" required 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold">
                                    <option value="piece" {{ old('unit', $product->unit ?? 'piece') == 'piece' ? 'selected' : '' }}>{{ __('admin.unit_piece') }}</option>
                                    <option value="gram" {{ old('unit', $product->unit ?? 'piece') == 'gram' ? 'selected' : '' }}>{{ __('admin.unit_gram') }}</option>
                                </select>
                                @error('unit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">{{ __('admin.image_url') }}</label>
                        <input type="url" name="image" id="image" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                               value="{{ old('image', $product->image) }}">
                        @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.products.index', app()->getLocale()) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-light-gold text-white rounded-md hover:bg-yellow-600">
                        {{ __('admin.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

