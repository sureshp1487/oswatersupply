<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#0284c7">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($appSettings['business_name'] ?? 'HydroPro') . ' - Pure Hydration Delivery')</title>

    <!-- PWA Manifest & App Icons -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/icons/logo.svg">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <link rel="shortcut icon" href="/favicon.ico">

    <!-- Tailwind CSS (Play CDN with Dynamic Customization Colors) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $appSettings["primary_color"] ?? "#0077b6" }}',
                        accent: '{{ $appSettings["accent_color"] ?? "#00b4d8" }}',
                        ocean: {
                            50: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 10%, white)',
                            100: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 20%, white)',
                            200: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 35%, white)',
                            300: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 50%, white)',
                            400: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 75%, white)',
                            500: '{{ $appSettings["primary_color"] ?? "#0077b6" }}',
                            600: '{{ $appSettings["primary_color"] ?? "#0077b6" }}',
                            700: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 85%, black)',
                            800: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 70%, black)',
                            900: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 50%, black)',
                        },
                        aqua: {
                            400: 'color-mix(in srgb, {{ $appSettings["accent_color"] ?? "#00b4d8" }} 75%, white)',
                            500: '{{ $appSettings["accent_color"] ?? "#00b4d8" }}',
                            600: 'color-mix(in srgb, {{ $appSettings["accent_color"] ?? "#00b4d8" }} 85%, black)',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts & FontAwesome & Lucide Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Razorpay Checkout -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <!-- SweetAlert2 for Toast & Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .water-wave-bg {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        }
        .pb-safe {
            padding-bottom: calc(7.5rem + env(safe-area-inset-bottom, 0px));
        }

        /* Micro-Animations & Ripple Effects */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-6px) rotate(1deg); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 15px rgba(2, 132, 199, 0.2); }
            50% { box-shadow: 0 0 30px rgba(2, 132, 199, 0.45); }
        }
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }

        .animate-float-slow {
            animation: floatSlow 5s ease-in-out infinite;
        }
        .pulse-glow {
            animation: pulseGlow 3s infinite;
        }
        .card-interactive {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-interactive:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px -8px rgba(2, 132, 199, 0.15);
        }
        .card-interactive:active {
            transform: scale(0.98);
        }
        .badge-ripple {
            position: relative;
        }
        .badge-ripple::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: inherit;
            background: rgba(14, 165, 233, 0.35);
            animation: pulseGlow 2s infinite;
            z-index: -1;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header / Brand -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('customer.home') }}" class="flex items-center space-x-2">
                    <img src="/icons/logo.svg" alt="HydroPro Logo" class="w-9 h-9 rounded-xl shadow-md shadow-indigo-500/20 object-contain p-0.5 bg-gradient-to-br from-blue-500 to-indigo-600">
                    <div>
                        <span class="text-xs font-black uppercase tracking-wider text-ocean-600 block leading-none">{{ $appSettings['business_name'] ?? 'HydroPro' }}</span>
                        <h1 class="text-sm font-extrabold text-slate-900 leading-tight">Drinking Water</h1>
                    </div>
                </a>

                <!-- Desktop / Tablet Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 ml-6 text-xs font-bold text-slate-600">
                    <a href="{{ route('customer.home') }}" class="px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.home') ? 'bg-sky-50 text-ocean-600' : 'hover:bg-slate-50' }}">
                        <i class="fa-solid fa-house mr-1"></i> Home
                    </a>
                    <a href="{{ route('customer.cart') }}" class="px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.cart', 'customer.book') ? 'bg-sky-50 text-ocean-600' : 'hover:bg-slate-50' }}">
                        <i class="fa-solid fa-cart-shopping mr-1"></i> Cart
                    </a>
                    <a href="{{ route('customer.orders') }}" class="px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.orders*') ? 'bg-sky-50 text-ocean-600' : 'hover:bg-slate-50' }}">
                        <i class="fa-solid fa-boxes-stacked mr-1"></i> My Orders
                    </a>
                    <a href="{{ route('customer.payments') }}" class="px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.payments') ? 'bg-sky-50 text-ocean-600' : 'hover:bg-slate-50' }}">
                        <i class="fa-solid fa-wallet mr-1"></i> Payments & Dues
                    </a>
                </nav>
            </div>

            <!-- Header Action Links -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                <!-- PWA Install Button (Dynamic) -->
                <button id="pwaInstallHeaderBtn" onclick="triggerPwaInstall()" class="hidden items-center space-x-1.5 px-3 py-1.5 text-xs font-bold bg-sky-50 hover:bg-sky-100 text-ocean-700 border border-sky-200 rounded-full shadow-sm transition-all animate-pulse">
                    <i class="fa-solid fa-cloud-arrow-down text-ocean-600"></i>
                    <span>Install App</span>
                </button>

                <!-- Header Cart Icon with Live Badge -->
                <a href="{{ route('customer.cart') }}" id="headerCartBtn" class="relative p-2 text-slate-600 hover:text-ocean-600 rounded-full hover:bg-slate-100 transition {{ request()->routeIs('customer.cart', 'customer.book') ? 'text-ocean-600 bg-sky-50' : '' }}" title="My Cart">
                    <i class="fa-solid fa-cart-shopping text-lg"></i>
                    <span id="headerCartBadge" class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-sky-600 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white shadow-sm hidden">
                        0
                    </span>
                </a>

                <a href="{{ route('customer.payments') }}" class="relative p-2 text-slate-600 hover:text-ocean-600 rounded-full hover:bg-slate-100 transition" title="Payments & Dues">
                    <i class="fa-solid fa-wallet text-lg"></i>
                    @if(auth()->user()->customerProfile && auth()->user()->customerProfile->total_due_balance > 0)
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white"></span>
                    @endif
                </a>

                <div class="hidden sm:flex items-center space-x-2 pl-2 border-l border-slate-200">
                    <a href="{{ route('customer.profile') }}" class="text-xs font-bold text-slate-700 hover:text-ocean-600 flex items-center space-x-1.5">
                        <i class="fa-solid fa-circle-user text-base text-ocean-600"></i>
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content Canvas (Fluid with mobile, tablet, and wide desktop support) -->
    <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 pb-safe md:pb-8">
        <!-- Announcement Banner if Enabled -->
        @if(!empty($appSettings['banner_enabled']) && $appSettings['banner_enabled'] == '1' && !empty($appSettings['announcement_banner']))
            <div class="mb-4 p-3.5 bg-gradient-to-r from-indigo-900 to-indigo-800 text-white rounded-2xl flex items-center justify-between text-xs font-semibold shadow-md border border-indigo-700/50">
                <div class="flex items-center space-x-2.5">
                    <span class="w-6 h-6 rounded-lg bg-indigo-500/40 flex items-center justify-center text-indigo-200">
                        <i class="fa-solid fa-bullhorn text-[11px]"></i>
                    </span>
                    <span>{{ $appSettings['announcement_banner'] }}</span>
                </div>
            </div>
        @endif

        <!-- Flash notifications (Single source, with dismiss button and auto-hide) -->
        @if(session('success'))
            <div id="flashSuccessAlert" class="flash-alert mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-sm transition-all duration-500">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="dismissFlashAlert(this)" class="text-emerald-500 hover:text-emerald-700 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div id="flashErrorAlert" class="flash-alert mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-sm transition-all duration-500">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="dismissFlashAlert(this)" class="text-rose-500 hover:text-rose-700 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Customer Mobile Bottom Navigation Bar -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-t border-slate-200/80 shadow-lg pb-safe">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-around">
            <!-- Home -->
            <a href="{{ route('customer.home') }}" class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('customer.home') ? 'text-ocean-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-house text-lg mb-1"></i>
                <span class="text-[11px]">Home</span>
            </a>

            <!-- Orders -->
            <a href="{{ route('customer.orders') }}" class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('customer.orders*') ? 'text-ocean-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-boxes-stacked text-lg mb-1"></i>
                <span class="text-[11px]">My Orders</span>
            </a>

            <!-- Prominent Cart Floating Action Button -->
            <div class="flex-1 flex justify-center -mt-6">
                <a href="{{ route('customer.cart') }}" class="w-14 h-14 rounded-full water-wave-bg text-white shadow-xl shadow-sky-600/35 flex flex-col items-center justify-center border-4 border-slate-50 hover:scale-105 active:scale-95 transition-transform relative">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span class="text-[9px] font-extrabold uppercase mt-0.5 tracking-tighter">Cart</span>
                    <span id="mobileCartBadge" class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[9px] font-black rounded-full flex items-center justify-center border-2 border-white hidden">0</span>
                </a>
            </div>

            <!-- Payments & Dues -->
            <a href="{{ route('customer.payments') }}" class="flex flex-col items-center justify-center flex-1 py-1 relative {{ request()->routeIs('customer.payments') ? 'text-ocean-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <div class="relative">
                    <i class="fa-solid fa-receipt text-lg mb-1"></i>
                    @if(auth()->user()->customerProfile && auth()->user()->customerProfile->total_due_balance > 0)
                        <span class="absolute -top-1 -right-1.5 w-2 h-2 bg-rose-500 rounded-full"></span>
                    @endif
                </div>
                <span class="text-[11px]">Payments</span>
            </a>

            <!-- Profile -->
            <a href="{{ route('customer.profile') }}" class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('customer.profile') ? 'text-ocean-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-circle-user text-lg mb-1"></i>
                <span class="text-[11px]">Profile</span>
            </a>
        </div>
    </nav>

    <!-- Global App Modal Container -->
    <div id="appModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-end sm:items-center justify-center p-0 sm:p-4 transition-opacity">
        <div id="appModalContent" class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto"></div>
    </div>

    <!-- PWA Install Prompt Bottom Sheet Banner (Mobile & Desktop) -->
    <div id="pwaBanner" class="hidden fixed bottom-18 md:bottom-6 right-4 left-4 md:left-auto md:w-96 z-50 bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-2xl border border-sky-100 flex items-center justify-between space-x-3 transition-all animate-bounce">
        <div class="flex items-center space-x-3">
            <img src="/icons/icon-192.png" alt="App Icon" class="w-11 h-11 rounded-xl shadow-md border border-sky-100 object-cover">
            <div>
                <p class="text-xs font-extrabold text-slate-900 leading-tight">Install AquaFlow App</p>
                <p class="text-[11px] text-slate-500">Fast 1-tap ordering on your home screen</p>
            </div>
        </div>
        <div class="flex items-center space-x-1.5 flex-shrink-0">
            <button onclick="dismissPwaBanner()" class="px-2 py-1.5 text-xs text-slate-400 hover:text-slate-600 rounded-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <button onclick="triggerPwaInstall()" class="px-3 py-1.5 bg-ocean-600 hover:bg-ocean-700 text-white text-xs font-bold rounded-xl shadow-md shadow-sky-500/25 transition">
                Install
            </button>
        </div>
    </div>

    <script>
        // Setup CSRF header for jQuery AJAX calls
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize Lucide icons
        if (window.lucide) {
            lucide.createIcons();
        }

        // PWA Service Worker Registration & Install Prompt Management
        let deferredPwaPrompt = null;

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPwaPrompt = e;
            
            // Show custom install buttons / banner
            const installBtn = document.getElementById('pwaInstallHeaderBtn');
            if (installBtn) {
                installBtn.classList.remove('hidden');
                installBtn.classList.add('inline-flex');
            }

            // Only show bottom banner if not dismissed this session
            if (!sessionStorage.getItem('pwa_prompt_dismissed')) {
                const banner = document.getElementById('pwaBanner');
                if (banner) {
                    banner.classList.remove('hidden');
                }
            }
        });

        window.addEventListener('appinstalled', () => {
            console.log('AquaFlow PWA successfully installed');
            deferredPwaPrompt = null;
            const banner = document.getElementById('pwaBanner');
            if (banner) banner.classList.add('hidden');
            const installBtn = document.getElementById('pwaInstallHeaderBtn');
            if (installBtn) installBtn.classList.add('hidden');
        });

        function triggerPwaInstall() {
            if (deferredPwaPrompt) {
                deferredPwaPrompt.prompt();
                deferredPwaPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted PWA prompt');
                    } else {
                        console.log('User dismissed PWA prompt');
                    }
                    deferredPwaPrompt = null;
                    dismissPwaBanner();
                });
            } else {
                alert('To install AquaFlow:\n• On Android/Chrome: Tap Chrome menu (⋮) -> "Add to Home screen" or "Install App".\n• On iPhone/Safari: Tap Share button -> "Add to Home Screen".');
            }
        }

        function dismissPwaBanner() {
            const banner = document.getElementById('pwaBanner');
            if (banner) banner.classList.add('hidden');
            sessionStorage.setItem('pwa_prompt_dismissed', '1');
        }

        function dismissFlashAlert(btn) {
            const alert = btn.closest('.flash-alert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.maxHeight = '0';
                alert.style.margin = '0';
                alert.style.padding = '0';
                alert.style.overflow = 'hidden';
                setTimeout(() => alert.remove(), 400);
            }
        }

        // Automatically hide flash alert after 3.5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const flashAlerts = document.querySelectorAll('.flash-alert');
            flashAlerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    alert.style.maxHeight = '0';
                    alert.style.margin = '0';
                    alert.style.padding = '0';
                    alert.style.overflow = 'hidden';
                    setTimeout(function() {
                        alert.remove();
                    }, 400);
                }, 3500);
            });
        });

        // Global Cart Storage Helper for synchronizing Cart Badge and Selection
        window.CartManager = {
            storageKey: 'hydropro_cart',
            getCart: function() {
                try {
                    const data = localStorage.getItem(this.storageKey);
                    return data ? JSON.parse(data) : {};
                } catch(e) {
                    return {};
                }
            },
            saveCart: function(cart) {
                try {
                    localStorage.setItem(this.storageKey, JSON.stringify(cart));
                } catch(e) {}
                this.updateBadge();
            },
            setItemQty: function(productId, qty) {
                const cart = this.getCart();
                if (qty > 0) {
                    cart[productId] = qty;
                } else {
                    delete cart[productId];
                }
                this.saveCart(cart);
            },
            getItemQty: function(productId) {
                const cart = this.getCart();
                return cart[productId] || 0;
            },
            getTotalCount: function() {
                const cart = this.getCart();
                let count = 0;
                for (let pid in cart) {
                    count += parseInt(cart[pid]) || 0;
                }
                return count;
            },
            updateBadge: function() {
                const total = this.getTotalCount();
                const badge = document.getElementById('headerCartBadge');
                const mobileBadge = document.getElementById('mobileCartBadge');
                const text = total > 99 ? '99+' : total;

                if (badge) {
                    if (total > 0) {
                        badge.textContent = text;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                if (mobileBadge) {
                    if (total > 0) {
                        mobileBadge.textContent = text;
                        mobileBadge.classList.remove('hidden');
                    } else {
                        mobileBadge.classList.add('hidden');
                    }
                }
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.CartManager.updateBadge();
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW Reg failed: ', err));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
