@extends('layouts.app')

@section('title', __('auth.register'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cream py-12 px-4 sm:px-6 lg:px-8 mt-16">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-bold text-warm-brown">
                {{ __('auth.register_title') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('auth.register_subtitle') }}
            </p>
        </div>
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('register', app()->getLocale()) }}">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('auth.name') }}</label>
                    <input id="name" name="name" type="text" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-light-gold focus:border-light-gold sm:text-sm" 
                           placeholder="{{ __('auth.name') }}" value="{{ old('name') }}">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('auth.email') }}</label>
                    <input id="email" name="email" type="email" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-light-gold focus:border-light-gold sm:text-sm" 
                           placeholder="{{ __('auth.email') }}" value="{{ old('email') }}">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('auth.phone') }}</label>
                    <input id="phone" name="phone" type="tel" 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-light-gold focus:border-light-gold sm:text-sm" 
                           placeholder="{{ __('auth.phone') }}" value="{{ old('phone') }}">
                </div>
                
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">{{ __('auth.address') }}</label>
                    <textarea id="address" name="address" rows="3" 
                              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-light-gold focus:border-light-gold sm:text-sm" 
                              placeholder="{{ __('auth.address') }}">{{ old('address') }}</textarea>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('auth.password') }}</label>
                    <input id="password" name="password" type="password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-light-gold focus:border-light-gold sm:text-sm" 
                           placeholder="{{ __('auth.password') }}">
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('auth.confirm_password') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-light-gold focus:border-light-gold sm:text-sm" 
                           placeholder="{{ __('auth.confirm_password') }}">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-light-gold hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-gold">
                    {{ __('auth.register') }}
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    {{ __('auth.have_account') }}
                    <a href="{{ route('login', app()->getLocale()) }}" class="font-medium text-light-gold hover:text-yellow-600">
                        {{ __('auth.login') }}
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection

