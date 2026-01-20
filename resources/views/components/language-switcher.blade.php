{{-- Premium Language Switcher Component --}}
<div x-data="{ open: false }" @click.away="open = false" class="relative">
    {{-- Switcher Button --}}
    <button @click="open = !open" 
            class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-white border border-gray-200 hover:border-brand-300 hover:bg-brand-50 transition-all duration-200 shadow-sm hover:shadow-md group">
        <svg class="h-5 w-5 text-gray-600 group-hover:text-brand-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
        </svg>
        <span class="text-sm font-medium text-gray-700 group-hover:text-brand-700 hidden sm:block" id="current-lang-text">EN</span>
        <svg class="h-4 w-4 text-gray-500 group-hover:text-brand-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-56 rounded-xl shadow-xl z-50 overflow-hidden"
         style="display: none;">
        
        {{-- Glassmorphism Card --}}
        <div class="bg-white/95 backdrop-blur-lg border border-gray-100 rounded-xl overflow-hidden shadow-2xl">
            {{-- Header --}}
            <div class="px-4 py-3 bg-gradient-to-r from-brand-500 to-brand-600 border-b border-brand-400">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    <p class="text-sm font-semibold text-white">Select Language</p>
                </div>
            </div>

            {{-- Language Options --}}
            <div class="py-2">
                {{-- English --}}
                <button onclick="changeLanguage('en')" 
                        class="w-full flex items-center px-4 py-3 hover:bg-gradient-to-r hover:from-brand-50 hover:to-brand-100 transition-all duration-200 group lang-option" 
                        data-lang="en">
                    <span class="text-2xl mr-3">🇬🇧</span>
                    <div class="flex-1 text-left">
                        <p class="text-sm font-semibold text-gray-800 group-hover:text-brand-700">English</p>
                        <p class="text-xs text-gray-500">Default</p>
                    </div>
                    <svg class="h-5 w-5 text-brand-600 opacity-0 active-lang-indicator" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- French --}}
                <button onclick="changeLanguage('fr')" 
                        class="w-full flex items-center px-4 py-3 hover:bg-gradient-to-r hover:from-brand-50 hover:to-brand-100 transition-all duration-200 group lang-option" 
                        data-lang="fr">
                    <span class="text-2xl mr-3">🇫🇷</span>
                    <div class="flex-1 text-left">
                        <p class="text-sm font-semibold text-gray-800 group-hover:text-brand-700">Français</p>
                        <p class="text-xs text-gray-500">French</p>
                    </div>
                    <svg class="h-5 w-5 text-brand-600 opacity-0 active-lang-indicator" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- Kinyarwanda --}}
                <button onclick="changeLanguage('rw')" 
                        class="w-full flex items-center px-4 py-3 hover:bg-gradient-to-r hover:from-brand-50 hover:to-brand-100 transition-all duration-200 group lang-option" 
                        data-lang="rw">
                    <span class="text-2xl mr-3">🇷🇼</span>
                    <div class="flex-1 text-left">
                        <p class="text-sm font-semibold text-gray-800 group-hover:text-brand-700">Kinyarwanda</p>
                        <p class="text-xs text-gray-500">Rwanda</p>
                    </div>
                    <svg class="h-5 w-5 text-brand-600 opacity-0 active-lang-indicator" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- Swahili --}}
                <button onclick="changeLanguage('sw')" 
                        class="w-full flex items-center px-4 py-3 hover:bg-gradient-to-r hover:from-brand-50 hover:to-brand-100 transition-all duration-200 group lang-option" 
                        data-lang="sw">
                    <span class="text-2xl mr-3">🇹🇿</span>
                    <div class="flex-1 text-left">
                        <p class="text-sm font-semibold text-gray-800 group-hover:text-brand-700">Kiswahili</p>
                        <p class="text-xs text-gray-500">Swahili</p>
                    </div>
                    <svg class="h-5 w-5 text-brand-600 opacity-0 active-lang-indicator" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            {{-- Footer --}}
            <div class="px-4 py-2 bg-gray-50 border-t border-gray-100">
                <p class="text-xs text-gray-500 text-center">Powered by Google Translate</p>
            </div>
        </div>
    </div>
</div>

{{-- Language Switcher JavaScript --}}
<script>
    // Language display names mapping
    const langNames = {
        'en': 'EN',
        'fr': 'FR',
        'rw': 'RW',
        'sw': 'SW'
    };

    // Flag to check if Google Translate is ready
    let googleTranslateReady = false;

    // Check if Google Translate is loaded
    function waitForGoogleTranslate(callback, maxAttempts = 100) {
        let attempts = 0;
        const checkInterval = setInterval(function() {
            attempts++;
            const select = document.querySelector('.goog-te-combo');
            if (select && select.options && select.options.length > 0) {
                clearInterval(checkInterval);
                googleTranslateReady = true;
                console.log('Google Translate select element found with', select.options.length, 'options');
                if (callback) callback();
            } else if (attempts >= maxAttempts) {
                clearInterval(checkInterval);
                console.error('Google Translate failed to load after ' + attempts + ' attempts');
                console.log('Checking if google_translate_element exists:', !!document.querySelector('#google_translate_element'));
                console.log('Checking if any goog-te elements exist:', document.querySelectorAll('[class*="goog-te"]').length);
            }
        }, 200);
    }

    // Change language function
    function changeLanguage(lang) {
        console.log('Changing language to:', lang);
        
        // Save preference to localStorage
        localStorage.setItem('preferredLanguage', lang);
        
        // Update current language display
        const langText = document.getElementById('current-lang-text');
        if (langText) {
            langText.textContent = langNames[lang];
        }
        
        // Update active indicator
        document.querySelectorAll('.lang-option').forEach(option => {
            const indicator = option.querySelector('.active-lang-indicator');
            if (option.dataset.lang === lang) {
                indicator.style.opacity = '1';
            } else {
                indicator.style.opacity = '0';
            }
        });
        
        // Function to actually trigger the translation
        function triggerTranslation() {
            const select = document.querySelector('.goog-te-combo');
            if (!select) {
                console.error('Google Translate select not found');
                return;
            }

            console.log('Triggering translation with select element');
            
            if (lang === 'en') {
                // Reset to original language
                select.value = '';
                select.dispatchEvent(new Event('change'));
            } else {
                // Change to selected language
                select.value = lang;
                select.dispatchEvent(new Event('change'));
            }
        }

        // If Google Translate is ready, trigger immediately
        if (googleTranslateReady) {
            triggerTranslation();
        } else {
            // Wait for it to be ready
            waitForGoogleTranslate(triggerTranslation);
        }
    }

    // Initialize on page load
    window.addEventListener('load', function() {
        console.log('Page loaded, initializing language switcher');
        
        // Wait for Google Translate to be ready
        waitForGoogleTranslate(function() {
            console.log('Google Translate is ready');
            
            const savedLang = localStorage.getItem('preferredLanguage') || 'en';
            console.log('Saved language preference:', savedLang);
            
            // Update UI to show saved language
            const langText = document.getElementById('current-lang-text');
            if (langText) {
                langText.textContent = langNames[savedLang];
            }
            
            // Update active indicator
            document.querySelectorAll('.lang-option').forEach(option => {
                const indicator = option.querySelector('.active-lang-indicator');
                if (option.dataset.lang === savedLang) {
                    indicator.style.opacity = '1';
                }
            });
            
            // Apply saved language
            if (savedLang !== 'en') {
                setTimeout(function() {
                    changeLanguage(savedLang);
                }, 500);
            }
        });
    });
</script>

