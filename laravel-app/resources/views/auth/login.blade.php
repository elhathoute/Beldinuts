@extends('layouts.app')

@section('title', __('auth.login'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cream py-12 px-4 sm:px-6 lg:px-8 mt-16">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-bold text-warm-brown">
                {{ __('auth.login_title') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('auth.login_subtitle') }}
            </p>
        </div>
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('login', app()->getLocale()) }}">
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

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">{{ __('auth.email') }}</label>
                    <input id="email" name="email" type="email" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-light-gold focus:border-light-gold focus:z-10 sm:text-sm" 
                           placeholder="{{ __('auth.email') }}" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">{{ __('auth.password') }}</label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-light-gold focus:border-light-gold focus:z-10 sm:text-sm" 
                           placeholder="{{ __('auth.password') }}">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                           class="h-4 w-4 text-light-gold focus:ring-light-gold border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        {{ __('auth.remember_me') }}
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-light-gold hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-gold">
                    {{ __('auth.login') }}
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    {{ __('auth.no_account') }}
                    <a href="{{ route('register', app()->getLocale()) }}" class="font-medium text-light-gold hover:text-yellow-600">
                        {{ __('auth.register') }}
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection

