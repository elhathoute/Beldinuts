@extends('layouts.app')

@section('title', __('admin.add_product'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8 mt-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-warm-brown mb-8">{{ __('admin.add_product') }}</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Validation Errors:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.products.store', app()->getLocale()) }}" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('admin.name') }}</label>
                        <input type="text" name="name" id="name" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                               value="{{ old('name') }}">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">{{ __('admin.description') }}</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price_per_gram_retail" class="block text-sm font-medium text-gray-700">{{ __('admin.price_per_gram_retail') }}</label>
                            <input type="number" step="0.01" name="price_per_gram_retail" id="price_per_gram_retail" required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                                   value="{{ old('price_per_gram_retail') }}">
                            @error('price_per_gram_retail')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label for="price_per_gram_wholesale" class="block text-sm font-medium text-gray-700">{{ __('admin.price_per_gram_wholesale') }}</label>
                            <input type="number" step="0.01" name="price_per_gram_wholesale" id="price_per_gram_wholesale" required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                                   value="{{ old('price_per_gram_wholesale') }}">
                            @error('price_per_gram_wholesale')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700">{{ __('admin.stock') }}</label>
                        <input type="number" name="stock" id="stock" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                               value="{{ old('stock') }}">
                        @error('stock')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Product Photos Section -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-warm-brown mb-4">{{ __('admin.product_photos') }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ __('admin.upload_photos_on_create') }}</p>
                        
                        <div id="photos-container" class="space-y-4">
                            <div class="photo-upload-item">
                                <input type="file" name="photos[]" accept="image/*" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-light-gold file:text-white hover:file:bg-yellow-600">
                            </div>
                        </div>
                        
                        <button type="button" id="add-photo-btn" class="mt-4 px-4 py-2 border border-light-gold text-light-gold rounded-md hover:bg-light-gold hover:text-white">
                            {{ __('admin.add_another_photo') }}
                        </button>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.products.index', app()->getLocale()) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-light-gold text-white rounded-md hover:bg-yellow-600">
                        {{ __('admin.create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('add-photo-btn').addEventListener('click', function() {
    const container = document.getElementById('photos-container');
    const newItem = document.createElement('div');
    newItem.className = 'photo-upload-item';
    newItem.innerHTML = `
        <div class="flex items-center space-x-2">
            <input type="file" name="photos[]" accept="image/*" 
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-light-gold file:text-white hover:file:bg-yellow-600">
            <button type="button" class="remove-photo-btn px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    container.appendChild(newItem);
    
    // Add remove functionality
    newItem.querySelector('.remove-photo-btn').addEventListener('click', function() {
        newItem.remove();
    });
});
</script>
@endsection

