<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.hero_title')) - BeldiNuts</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('beldi-nuts-logo.png') }}">
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'warm-brown': '#8B4513',
                        'beige': '#F5F5DC',
                        'earth-green': '#8FBC8F',
                        'light-gold': '#DAA520',
                        'cream': '#FFF8DC'
                    }
                }
            }
        }
    </script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans">
    @include('partials.navbar')
    
    <main class="pt-[10rem]">
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    @stack('scripts')
</body>
</html>

