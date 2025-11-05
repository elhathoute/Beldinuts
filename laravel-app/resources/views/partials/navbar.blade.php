<nav class="fixed top-0 w-full bg-white/90 backdrop-blur-sm z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="{{ route('home', app()->getLocale()) }}" class="flex items-center">
                    <img src="{{ asset('beldi-nuts-logo.png') }}" alt="BeldiNuts Logo" class="h-[100px] w-[100px] rounded-full me-4">
                    <h1 class="text-2xl font-bold text-warm-brown">BeldiNuts</h1>
                </a>
            </div>
            <div class="hidden md:flex space-x-12 items-center">
                <a href="{{ route('home', app()->getLocale()) }}#accueil" class="text-warm-brown hover:text-light-gold transition-colors">{{ __('messages.nav_home') }}</a>
                <a href="{{ route('home', app()->getLocale()) }}#apropos" class="text-warm-brown hover:text-light-gold transition-colors">{{ __('messages.nav_about') }}</a>
                <a href="{{ route('home', app()->getLocale()) }}#produits" class="text-warm-brown hover:text-light-gold transition-colors">{{ __('messages.nav_products') }}</a>
                <a href="{{ route('order.create', app()->getLocale()) }}" class="text-warm-brown hover:text-light-gold transition-colors bg-light-gold text-white px-4 py-2 rounded-full">{{ __('messages.nav_order') }}</a>
                
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard', app()->getLocale()) }}" class="text-warm-brown hover:text-light-gold transition-colors">
                            <i class="fas fa-tachometer-alt mr-1"></i>{{ __('admin.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('orders.index', app()->getLocale()) }}" class="text-warm-brown hover:text-light-gold transition-colors">
                            <i class="fas fa-shopping-bag mr-1"></i>{{ __('messages.my_orders') }}
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout', app()->getLocale()) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-warm-brown hover:text-light-gold transition-colors">
                            <i class="fas fa-sign-out-alt mr-1"></i>{{ __('auth.logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login', app()->getLocale()) }}" class="text-warm-brown hover:text-light-gold transition-colors">{{ __('auth.login') }}</a>
                    <a href="{{ route('register', app()->getLocale()) }}" class="text-warm-brown hover:text-light-gold transition-colors">{{ __('auth.register') }}</a>
                @endauth
                
                <!-- Language Selector -->
                <div class="relative">
                    <select id="language-select" onchange="changeLanguage(this.value)" 
                            class="bg-transparent border border-warm-brown text-warm-brown px-3 py-1 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-light-gold">
                        @foreach(['fr' => '🇫🇷 FR', 'ar' => '🇲🇦 AR', 'en' => '🇺🇸 EN'] as $locale => $label)
                            <option value="{{ $locale }}" {{ app()->getLocale() === $locale ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function changeLanguage(locale) {
    const currentUrl = window.location.href;
    const currentLocale = '{{ app()->getLocale() }}';
    const newUrl = currentUrl.replace(`/${currentLocale}/`, `/${locale}/`).replace(`/${currentLocale}`, `/${locale}`);
    window.location.href = newUrl;
}
</script>

