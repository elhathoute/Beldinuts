<footer id="contact" class="bg-gray-900 text-white py-16 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center mb-4">
                    <img src="{{ asset('beldi-nuts-logo.png') }}" alt="BeldiNuts Logo" class="h-[100px] w-[100px] rounded-full me-4">
                    <h3 class="text-2xl font-bold text-light-gold">BeldiNuts</h3>
                </div>
                <p class="text-gray-300 mb-4">
                    {{ __('messages.footer_description') }}
                </p>
                <div class="flex space-x-4">
                    <a href="https://www.instagram.com/beldinutsmorroco/" target="_blank" class="text-gray-300 hover:text-light-gold transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                </div>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">{{ __('messages.footer_products') }}</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('order.create', app()->getLocale()) }}" class="text-gray-300 hover:text-light-gold transition-colors">{{ __('messages.footer_amandes') }}</a></li>
                    <li><a href="{{ route('order.create', app()->getLocale()) }}" class="text-gray-300 hover:text-light-gold transition-colors">{{ __('messages.footer_noix') }}</a></li>
                    <li><a href="{{ route('order.create', app()->getLocale()) }}" class="text-gray-300 hover:text-light-gold transition-colors">{{ __('messages.footer_dattes') }}</a></li>
                    <li><a href="{{ route('order.create', app()->getLocale()) }}" class="text-gray-300 hover:text-light-gold transition-colors">{{ __('messages.footer_fruits_secs') }}</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">{{ __('messages.footer_info') }}</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home', app()->getLocale()) }}#apropos" class="text-gray-300 hover:text-light-gold transition-colors">{{ __('messages.nav_about') }}</a></li>
                    <li><a href="#contact" class="text-gray-300 hover:text-light-gold transition-colors">{{ __('messages.footer_delivery') }}</a></li>
                    <li><a href="#contact" class="text-gray-300 hover:text-light-gold transition-colors">{{ __('messages.footer_returns') }}</a></li>
                    <li><a href="{{ route('home', app()->getLocale()) }}#faq" class="text-gray-300 hover:text-light-gold transition-colors">{{ __('messages.nav_faq') }}</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">{{ __('messages.footer_contact') }}</h4>
                <div class="space-y-2">
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-light-gold me-2 mt-1"></i>
                        <span class="text-gray-300">{{ __('messages.footer_address') }}</span>
                    </div>
                    <div class="flex flex-col space-y-1">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-light-gold me-2"></i>
                            <a href="tel:+212615919437" class="text-gray-300 hover:text-light-gold transition-colors">+212 615919437</a>
                        </div>
                        <div class="flex items-center ms-7">
                            <a href="tel:+212694147354" class="text-gray-300 hover:text-light-gold transition-colors">+212 694-147354</a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-light-gold me-2"></i>
                        <a href="mailto:beldinuts@gmail.com" class="text-gray-300 hover:text-light-gold transition-colors">beldinuts@gmail.com</a>
                    </div>
                </div>
                <div class="mt-4">
                    <h5 class="text-sm font-semibold mb-2">{{ __('messages.location') }}</h5>
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d53221.97428900667!2d-7.851877212524421!3d33.517676320249386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda62b072488925b%3A0xf46f2275c5d13d5d!2sBeldiNuts!5e0!3m2!1sen!2sma!4v1762351233177!5m2!1sen!2sma" 
                            width="100%" 
                            height="150" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-12 pt-8 text-center">
            <p class="text-gray-300">
                <span>{{ __('messages.footer_copyright') }}</span> | 
                <a href="#" class="hover:text-light-gold transition-colors">{{ __('messages.footer_legal') }}</a> | 
                <a href="#" class="hover:text-light-gold transition-colors">{{ __('messages.footer_privacy') }}</a>
            </p>
        </div>
    </div>
</footer>

