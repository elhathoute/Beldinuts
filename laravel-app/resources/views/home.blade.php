@extends('layouts.app')

@section('title', __('messages.hero_title'))

@section('content')
<!-- Hero Section -->
<section id="accueil" class="hero-bg min-h-screen flex items-center justify-center text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="fade-in">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                {{ __('messages.hero_title') }}
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-cream">
                {{ __('messages.hero_subtitle') }}
            </p>
            <p class="text-lg md:text-xl mb-12 max-w-3xl mx-auto">
                {{ __('messages.hero_description') }}
            </p>
            <a href="#produits" class="inline-block bg-light-gold hover:bg-yellow-600 text-white font-semibold py-4 px-8 rounded-full text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                {{ __('messages.hero_button') }}
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="apropos" class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="slide-in-left">
                <h2 class="text-3xl md:text-4xl font-bold text-warm-brown mb-6">
                    {{ __('messages.about_title') }}
                </h2>
                <p class="text-lg text-gray-700 mb-6">
                    {{ __('messages.about_text1') }}
                </p>
                <p class="text-lg text-gray-700 mb-6">
                    {{ __('messages.about_text2') }}
                </p>
                <p class="text-lg text-gray-700 mb-8">
                    {{ __('messages.about_text3') }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center text-warm-brown">
                        <i class="fas fa-leaf text-xl mr-2"></i>
                        <span class="font-semibold">{{ __('messages.about_natural') }}</span>
                    </div>
                    <div class="flex items-center text-warm-brown">
                        <i class="fas fa-map-marker-alt text-xl mr-2"></i>
                        <span class="font-semibold">{{ __('messages.about_origin') }}</span>
                    </div>
                    <div class="flex items-center text-warm-brown">
                        <i class="fas fa-heart text-xl mr-2"></i>
                        <span class="font-semibold">{{ __('messages.about_quality') }}</span>
                    </div>
                </div>
            </div>
            <div class="slide-in-right">
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="bg-warm-brown text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-seedling text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-warm-brown mb-2">{{ __('messages.about_selection') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('messages.about_selection_desc') }}</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-earth-green text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-truck text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-warm-brown mb-2">{{ __('messages.about_delivery') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('messages.about_delivery_desc') }}</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-light-gold text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-award text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-warm-brown mb-2">{{ __('messages.about_certified') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('messages.about_certified_desc') }}</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-warm-brown text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-warm-brown mb-2">{{ __('messages.about_community') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('messages.about_community_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section id="produits" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-warm-brown mb-6">
                {{ __('messages.products_title') }}
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ __('messages.products_subtitle') }}
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($products as $product)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $product->image ?? asset('beldi-nuts-logo.png') }}')">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-warm-brown mb-3">{{ $product->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-warm-brown">{{ number_format($product->price_per_gram, 2) }} DH/g</span>
                        <a href="{{ route('order.create', app()->getLocale()) }}" class="inline-block bg-light-gold hover:bg-yellow-600 text-white px-6 py-2 rounded-full transition-colors">
                            {{ __('messages.order_btn') }}
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <p class="col-span-3 text-center text-gray-500">{{ __('messages.no_products') }}</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-20 bg-beige">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-warm-brown mb-6">
                {{ __('messages.benefits_title') }}
            </h2>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-warm-brown text-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-leaf text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-warm-brown mb-4">{{ __('messages.benefits_natural') }}</h3>
                <p class="text-gray-600">{{ __('messages.benefits_natural_desc') }}</p>
            </div>
            
            <div class="text-center">
                <div class="bg-earth-green text-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-dumbbell text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-warm-brown mb-4">{{ __('messages.benefits_protein') }}</h3>
                <p class="text-gray-600">{{ __('messages.benefits_protein_desc') }}</p>
            </div>
            
            <div class="text-center">
                <div class="bg-light-gold text-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shield-alt text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-warm-brown mb-4">{{ __('messages.benefits_no_additives') }}</h3>
                <p class="text-gray-600">{{ __('messages.benefits_no_additives_desc') }}</p>
            </div>
            
            <div class="text-center">
                <div class="bg-warm-brown text-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-warm-brown mb-4">{{ __('messages.benefits_health') }}</h3>
                <p class="text-gray-600">{{ __('messages.benefits_health_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-20 bg-warm-brown">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
            {{ __('messages.newsletter_title') }}
        </h2>
        <p class="text-xl text-cream mb-8">
            {{ __('messages.newsletter_subtitle') }}
        </p>
        <form class="max-w-md mx-auto" method="POST" action="#">
            @csrf
            <div class="flex flex-col sm:flex-row gap-4">
                <input 
                    type="email" 
                    name="email"
                    placeholder="{{ __('messages.newsletter_placeholder') }}" 
                    class="flex-1 px-6 py-4 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-light-gold"
                    required
                >
                <button 
                    type="submit" 
                    class="bg-light-gold hover:bg-yellow-600 text-white font-semibold px-8 py-4 rounded-full transition-colors"
                >
                    {{ __('messages.newsletter_button') }}
                </button>
            </div>
        </form>
    </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-warm-brown mb-6">
                {{ __('messages.faq_title') }}
            </h2>
            <p class="text-lg text-gray-600">
                {{ __('messages.faq_subtitle') }}
            </p>
        </div>
        
        <div class="space-y-6">
            <!-- FAQ Item 1 -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                <button class="faq-toggle w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-warm-brown">
                            {{ __('messages.faq_q1') }}
                        </h3>
                        <i class="fas fa-chevron-down text-warm-brown transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <p class="text-gray-600">{{ __('messages.faq_a1') }}</p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                <button class="faq-toggle w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-warm-brown">
                            {{ __('messages.faq_q2') }}
                        </h3>
                        <i class="fas fa-chevron-down text-warm-brown transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <p class="text-gray-600">{{ __('messages.faq_a2') }}</p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                <button class="faq-toggle w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-warm-brown">
                            {{ __('messages.faq_q3') }}
                        </h3>
                        <i class="fas fa-chevron-down text-warm-brown transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <p class="text-gray-600">{{ __('messages.faq_a3') }}</p>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                <button class="faq-toggle w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-warm-brown">
                            {{ __('messages.faq_q4') }}
                        </h3>
                        <i class="fas fa-chevron-down text-warm-brown transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <p class="text-gray-600">{{ __('messages.faq_a4') }}</p>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                <button class="faq-toggle w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-warm-brown">
                            {{ __('messages.faq_q5') }}
                        </h3>
                        <i class="fas fa-chevron-down text-warm-brown transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <p class="text-gray-600">{{ __('messages.faq_a5') }}</p>
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                <button class="faq-toggle w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-warm-brown">
                            {{ __('messages.faq_q6') }}
                        </h3>
                        <i class="fas fa-chevron-down text-warm-brown transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <p class="text-gray-600">{{ __('messages.faq_a6') }}</p>
                </div>
            </div>

            <!-- FAQ Item 7 -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                <button class="faq-toggle w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-warm-brown">
                            {{ __('messages.faq_q7') }}
                        </h3>
                        <i class="fas fa-chevron-down text-warm-brown transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-6 pb-6">
                    <p class="text-gray-600">{{ __('messages.faq_a7') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.hero-bg {
    background: linear-gradient(rgba(139, 69, 19, 0.6), rgba(139, 69, 19, 0.4)), 
                url('https://img.freepik.com/premium-photo/background-from-various-nuts-dried-fruits_266870-12179.jpg');
    background-size: cover;
    background-position: center;
}
.fade-in {
    animation: fadeIn 1s ease-in;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
.slide-in-left {
    animation: slideInLeft 1s ease-out;
}
.slide-in-right {
    animation: slideInRight 1s ease-out;
}
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-50px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes slideInRight {
    from { opacity: 0; transform: translateX(50px); }
    to { opacity: 1; transform: translateX(0); }
}
</style>
@endpush

@push('scripts')
<script>
function toggleFAQ(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('.fa-chevron-down');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>
@endpush
