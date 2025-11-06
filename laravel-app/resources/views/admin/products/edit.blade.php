@extends('layouts.app')

@section('title', __('admin.edit_product'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8 mt-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-warm-brown mb-8">{{ __('admin.edit_product') }}</h1>
        
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
            <form method="POST" action="{{ route('admin.products.update', $product) }}">
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
                            <label for="price_per_gram_retail" class="block text-sm font-medium text-gray-700">{{ __('admin.price_per_gram_retail') }}</label>
                            <input type="number" step="0.01" name="price_per_gram_retail" id="price_per_gram_retail" required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                                   value="{{ old('price_per_gram_retail', $product->price_per_gram_retail) }}">
                            @error('price_per_gram_retail')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label for="price_per_gram_wholesale" class="block text-sm font-medium text-gray-700">{{ __('admin.price_per_gram_wholesale') }}</label>
                            <input type="number" step="0.01" name="price_per_gram_wholesale" id="price_per_gram_wholesale" required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                                   value="{{ old('price_per_gram_wholesale', $product->price_per_gram_wholesale) }}">
                            @error('price_per_gram_wholesale')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700">{{ __('admin.stock') }}</label>
                        <input type="number" name="stock" id="stock" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-light-gold focus:border-light-gold" 
                               value="{{ old('stock', $product->stock) }}">
                        @error('stock')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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

            <!-- Product Photos Section (Outside main form to avoid nesting) -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-semibold text-warm-brown mb-4">{{ __('admin.product_photos') }}</h3>
                
                <!-- Upload New Photo -->
                <form method="POST" action="{{ route('admin.products.photos.store', $product) }}" enctype="multipart/form-data" class="mb-6" id="photo-upload-form">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <input type="file" name="photo" id="photo" accept="image/*" required 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-light-gold file:text-white hover:file:bg-yellow-600">
                        <button type="submit" class="px-4 py-2 bg-light-gold text-white rounded-md hover:bg-yellow-600">
                            {{ __('admin.upload_photo') }}
                        </button>
                    </div>
                    <div id="photo-upload-status" class="mt-2 text-sm text-gray-600"></div>
                </form>
                
                <script>
                document.getElementById('photo-upload-form').addEventListener('submit', function(e) {
                    const fileInput = document.getElementById('photo');
                    const statusDiv = document.getElementById('photo-upload-status');
                    
                    if (!fileInput.files || fileInput.files.length === 0) {
                        e.preventDefault();
                        statusDiv.innerHTML = '<span class="text-red-600">Please select a file first</span>';
                        return false;
                    }
                    
                    statusDiv.innerHTML = '<span class="text-blue-600">Uploading...</span>';
                });
                </script>

                <!-- Existing Photos -->
                @if($product->photos->count() > 0)
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($product->photos as $photo)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover rounded-lg">
                                <form method="POST" action="{{ route('admin.products.photos.destroy', [$product, $photo]) }}" 
                                      class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700"
                                            onclick="return confirm('{{ __('admin.confirm_delete_photo') }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">{{ __('admin.no_photos') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

