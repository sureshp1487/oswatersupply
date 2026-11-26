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
    <!-- Canvas Confetti Celebration Animation -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

    <!-- Leaflet.js OpenStreetMap (Free & Open Source) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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
            padding-bottom: calc(5rem + env(safe-area-inset-bottom, 0px));
        }
        .pb-safe-nav {
            padding-bottom: env(safe-area-inset-bottom, 0px);
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

        /* Cart Popup Fade In / Fade Out Animations */
        @keyframes cartFadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -18px, 0) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0) scale(1);
            }
        }
        @keyframes cartFadeOutUp {
            from {
                opacity: 1;
                transform: translate3d(0, 0, 0) scale(1);
            }
            to {
                opacity: 0;
                transform: translate3d(0, -16px, 0) scale(0.96);
            }
        }
        .cart-popup-fadein {
            animation: cartFadeInDown 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        }
        .cart-popup-fadeout {
            animation: cartFadeOutUp 0.3s cubic-bezier(0.7, 0, 0.84, 0) forwards !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header / Brand -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-2.5 sm:py-3 flex items-center justify-between gap-2">
            <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
                <!-- Desktop: Brand Logo & Name -->
                <a href="{{ route('customer.home') }}" class="hidden sm:flex items-center space-x-2 min-w-0">
                    <img src="/icons/logo.svg" alt="HydroPro Logo" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl shadow-md shadow-indigo-500/20 object-contain p-0.5 bg-gradient-to-br from-blue-500 to-indigo-600 shrink-0">
                    <div class="min-w-0">
                        <span class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-ocean-600 block leading-tight truncate">{{ $appSettings['business_name'] ?? 'HydroPro' }}</span>
                        <h1 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-tight truncate">Drinking Water</h1>
                    </div>
                </a>

                <!-- Mobile: Show Customer Name (Brand Name Removed on Mobile) -->
                <a href="{{ route('customer.profile') }}" class="flex sm:hidden items-center space-x-2.5 min-w-0 py-0.5">
                    <div class="w-8 h-8 rounded-full bg-sky-400 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[10px] text-slate-400 font-bold block leading-none">Hi, Welcome 👋</span>
                        <h2 class="text-xs sm:text-sm font-black text-slate-900 block leading-tight truncate">{{ auth()->user()->name }}</h2>
                    </div>
                </a>

                <!-- Desktop / Tablet Navigation Links (Redesigned: Home, Products, Services, Subscriptions) -->
                <nav class="hidden md:flex items-center space-x-1.5 ml-6 text-xs font-bold bg-slate-100/70 p-1 rounded-2xl border border-slate-200/50">
                    <a href="{{ route('customer.home') }}" class="px-3.5 py-1.5 rounded-xl transition flex items-center space-x-1.5 {{ request()->routeIs('customer.home') ? 'bg-white text-sky-700 shadow-xs font-black' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-bold' }}">
                        <i class="fa-solid fa-house text-xs {{ request()->routeIs('customer.home') ? 'text-sky-500' : 'text-slate-400' }}"></i>
                        <span>Home</span>
                    </a>
                    <a href="{{ route('customer.products') }}" class="px-3.5 py-1.5 rounded-xl transition flex items-center space-x-1.5 {{ request()->routeIs('customer.products*') ? 'bg-white text-sky-700 shadow-xs font-black' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-bold' }}">
                        <i class="fa-solid fa-bottle-water text-xs {{ request()->routeIs('customer.products*') ? 'text-sky-500' : 'text-slate-400' }}"></i>
                        <span>Products</span>
                    </a>
                    <a href="{{ route('customer.services') }}" class="px-3.5 py-1.5 rounded-xl transition flex items-center space-x-1.5 {{ request()->routeIs('customer.services*') ? 'bg-white text-sky-700 shadow-xs font-black' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-bold' }}">
                        <i class="fa-solid fa-bell-concierge text-xs {{ request()->routeIs('customer.services*') ? 'text-sky-500' : 'text-slate-400' }}"></i>
                        <span>Services</span>
                    </a>
                    <a href="{{ route('customer.subscriptions.index') }}" class="px-3.5 py-1.5 rounded-xl transition flex items-center space-x-1.5 {{ request()->routeIs('customer.subscriptions*') ? 'bg-white text-sky-700 shadow-xs font-black' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-bold' }}">
                        <i class="fa-solid fa-repeat text-xs {{ request()->routeIs('customer.subscriptions*') ? 'text-sky-500' : 'text-slate-400' }}"></i>
                        <span>Subscriptions</span>
                    </a>
                </nav>
            </div>

            <!-- Header Action Links (Desktop & Mobile) -->
            <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                <!-- PWA Install Button (Desktop & Tablet only) -->
                <button id="pwaInstallHeaderBtn" onclick="triggerPwaInstall()" class="hidden md:inline-flex items-center space-x-1.5 px-3 py-1.5 text-xs font-bold bg-sky-50 hover:bg-sky-100 text-ocean-700 border border-sky-200 rounded-full shadow-sm transition-all animate-pulse">
                    <i class="fa-solid fa-cloud-arrow-down text-ocean-600"></i>
                    <span>Install App</span>
                </button>

                <!-- Search Button (Desktop: Light Blue Circle with White Magnifying Glass) -->
                <button type="button" onclick="openSearchModal()" class="hidden sm:flex w-9 h-9 rounded-full bg-sky-400 hover:bg-sky-500 text-white items-center justify-center transition shadow-sm" title="Search products & services">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </button>

                <!-- Desktop User Profile Dropdown -->
                <div class="relative hidden sm:block" id="desktopUserDropdownContainer">
                    <button type="button" id="desktopUserMenuBtn" onclick="toggleDesktopUserMenu(event)" class="flex items-center space-x-2 py-1 px-1.5 rounded-full hover:bg-slate-100/80 transition focus:outline-none select-none">
                        <!-- Light Blue Circle with User Outline Icon -->
                        <div class="w-9 h-9 rounded-full bg-sky-400 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fa-regular fa-user text-sm"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-800 leading-tight max-w-[130px] truncate">{{ auth()->user()->name }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-600 transition-transform duration-200" id="desktopUserChevron"></i>
                    </button>

                    <!-- Dropdown Menu Box -->
                    <div id="desktopUserMenuDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2.5 z-50 transform origin-top-right transition-all duration-200 hidden">
                        <a href="javascript:void(0)" onclick="closeDesktopUserMenu(); openModal('profileDetailsModal');" class="block px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition">
                            Account Settings
                        </a>
                        <a href="javascript:void(0)" onclick="closeDesktopUserMenu(); openModal('addressBookModal');" class="block px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition">
                            Manage Addresses
                        </a>
                        <a href="{{ route('customer.orders') }}" class="block px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition">
                            My Orders
                        </a>
                        <a href="{{ route('customer.subscriptions.index') }}" class="block px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition">
                            My Subscriptions
                        </a>
                        <a href="javascript:void(0)" onclick="closeDesktopUserMenu(); openModal('walletModal');" class="block px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition">
                            {{ explode(' ', $appSettings['business_name'] ?? 'AquaFlow')[0] }} Wallet
                        </a>
                        <a href="javascript:void(0)" onclick="closeDesktopUserMenu(); openModal('returnJarModal');" class="block px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition">
                            Return Jar
                        </a>
                        <a href="javascript:void(0)" onclick="closeDesktopUserMenu(); confirmLogout();" class="block px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition">
                            Logout
                        </a>
                    </div>
                </div>

                <!-- Header Cart Icon with Live Badge (Desktop: Light Blue Circle) -->
                <div class="relative">
                    <a href="{{ route('customer.cart') }}" id="headerCartBtn" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-sky-400 hover:bg-sky-500 text-white flex items-center justify-center transition shadow-sm {{ request()->routeIs('customer.cart', 'customer.book') ? 'ring-2 ring-sky-400/50' : '' }}" title="My Cart">
                        <i class="fa-solid fa-cart-shopping text-sm"></i>
                    </a>
                    <span id="headerCartBadge" class="absolute -top-1.5 -right-1.5 min-w-[20px] h-5 px-1 bg-[#d4ba54] text-slate-900 text-[11px] font-black rounded-full flex items-center justify-center border-2 border-white shadow-sm hidden">
                        0
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Canvas (Fluid with mobile, tablet, and wide desktop support) -->
    <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 pb-20 md:pb-8">
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

    <!-- Customer Mobile Bottom Navigation Bar (Redesigned: Home, Products, Services, Profile) -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur-xl border-t border-slate-200/80 shadow-[0_-8px_30px_rgba(0,0,0,0.08)] pb-safe-nav">
        <div class="max-w-md mx-auto px-2 h-16 flex items-center justify-around">
            <!-- 1. Home -->
            <a href="{{ route('customer.home') }}" class="flex flex-col items-center justify-center flex-1 py-1 px-1 relative transition-all group active:scale-95 select-none">
                @if(request()->routeIs('customer.home'))
                    <span class="absolute top-0 w-8 h-1 rounded-full bg-gradient-to-r from-sky-400 to-ocean-600 shadow-xs shadow-sky-500/40"></span>
                    <div class="w-10 h-7 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 text-base mb-0.5">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <span class="text-[11px] font-black text-sky-700 tracking-tight leading-none">Home</span>
                @else
                    <div class="w-10 h-7 flex items-center justify-center text-slate-400 group-hover:text-slate-700 text-base mb-0.5 transition-colors">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-500 group-hover:text-slate-800 tracking-tight leading-none">Home</span>
                @endif
            </a>

            <!-- 2. Products -->
            <a href="{{ route('customer.products') }}" class="flex flex-col items-center justify-center flex-1 py-1 px-1 relative transition-all group active:scale-95 select-none">
                @if(request()->routeIs('customer.products*'))
                    <span class="absolute top-0 w-8 h-1 rounded-full bg-gradient-to-r from-sky-400 to-ocean-600 shadow-xs shadow-sky-500/40"></span>
                    <div class="w-10 h-7 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 text-base mb-0.5">
                        <i class="fa-solid fa-bottle-water"></i>
                    </div>
                    <span class="text-[11px] font-black text-sky-700 tracking-tight leading-none">Products</span>
                @else
                    <div class="w-10 h-7 flex items-center justify-center text-slate-400 group-hover:text-slate-700 text-base mb-0.5 transition-colors">
                        <i class="fa-solid fa-bottle-water"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-500 group-hover:text-slate-800 tracking-tight leading-none">Products</span>
                @endif
            </a>

            <!-- 3. Services -->
            <a href="{{ route('customer.services') }}" class="flex flex-col items-center justify-center flex-1 py-1 px-1 relative transition-all group active:scale-95 select-none">
                @if(request()->routeIs('customer.services*'))
                    <span class="absolute top-0 w-8 h-1 rounded-full bg-gradient-to-r from-sky-400 to-ocean-600 shadow-xs shadow-sky-500/40"></span>
                    <div class="w-10 h-7 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 text-base mb-0.5">
                        <i class="fa-solid fa-bell-concierge"></i>
                    </div>
                    <span class="text-[11px] font-black text-sky-700 tracking-tight leading-none">Services</span>
                @else
                    <div class="w-10 h-7 flex items-center justify-center text-slate-400 group-hover:text-slate-700 text-base mb-0.5 transition-colors">
                        <i class="fa-solid fa-bell-concierge"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-500 group-hover:text-slate-800 tracking-tight leading-none">Services</span>
                @endif
            </a>

            <!-- 4. Profile -->
            <a href="{{ route('customer.profile') }}" class="flex flex-col items-center justify-center flex-1 py-1 px-1 relative transition-all group active:scale-95 select-none">
                @if(request()->routeIs('customer.profile*'))
                    <span class="absolute top-0 w-8 h-1 rounded-full bg-gradient-to-r from-sky-400 to-ocean-600 shadow-xs shadow-sky-500/40"></span>
                    <div class="w-10 h-7 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 text-base mb-0.5">
                        <i class="fa-solid fa-circle-user"></i>
                    </div>
                    <span class="text-[11px] font-black text-sky-700 tracking-tight leading-none">Profile</span>
                @else
                    <div class="w-10 h-7 flex items-center justify-center text-slate-400 group-hover:text-slate-700 text-base mb-0.5 transition-colors">
                        <i class="fa-solid fa-circle-user"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-500 group-hover:text-slate-800 tracking-tight leading-none">Profile</span>
                @endif
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
            
            // Show custom install buttons / banner (Desktop header button only)
            const installBtn = document.getElementById('pwaInstallHeaderBtn');
            if (installBtn && window.innerWidth >= 768) {
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

        // Global Cart Storage Helper scoped per authenticated user
        window.CartManager = {
            storageKey: 'hydropro_cart_user_{{ auth()->id() ?? "guest" }}',
            legacyKey: 'hydropro_cart',
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
            },
            showCartPopup: function(options = {}) {
                if (typeof Swal === 'undefined') return;

                const isMobile = window.innerWidth < 640;
                const totalCount = this.getTotalCount();
                const name = options.name || (totalCount > 0 ? (totalCount + ' Water Can' + (totalCount > 1 ? 's' : '') + ' in Cart') : 'Your Cart');
                const subtitle = options.subtitle || (totalCount > 0 ? 'Ready for fast doorstep delivery' : 'Your cart is currently empty');
                const cartUrl = "{{ route('customer.cart') }}";
                const img = options.img || '/icons/logo.svg';

                Swal.fire({
                    toast: true,
                    position: isMobile ? 'top' : 'top-end',
                    width: isMobile ? '94%' : '380px',
                    showConfirmButton: false,
                    timer: options.timer !== undefined ? options.timer : 3500,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'cart-popup-fadein'
                    },
                    hideClass: {
                        popup: 'cart-popup-fadeout'
                    },
                    customClass: {
                        container: 'p-2 sm:p-4 !z-[9999]',
                        popup: 'rounded-2xl shadow-2xl border border-sky-100 p-2.5 sm:p-3 bg-white/95 backdrop-blur-md',
                        timerProgressBar: 'bg-sky-500'
                    },
                    html: `
                        <div class="flex items-center space-x-2.5 sm:space-x-3 text-left w-full">
                            <div class="relative shrink-0">
                                <img src="${img}" alt="" onerror="this.onerror=null;this.src='/icons/logo.svg';" class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl object-cover border border-slate-100 shadow-xs">
                                <span class="absolute -top-1 -right-1 bg-emerald-500 text-white w-4 h-4 rounded-full flex items-center justify-center text-[8px] font-black shadow-xs">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                            </div>
                            <div class="flex-1 min-w-0 pr-1">
                                <p class="text-[11px] sm:text-xs font-black text-slate-900 leading-tight truncate">${options.escape ? options.escape(name) : name}</p>
                                <p class="text-[10px] sm:text-[11px] text-slate-500 font-semibold mt-0.5 truncate">
                                    ${subtitle}
                                </p>
                            </div>
                            <a href="${cartUrl}" class="shrink-0 px-2 sm:px-2.5 py-1.5 rounded-xl bg-gradient-to-r from-sky-600 to-ocean-700 text-white font-extrabold text-[10px] sm:text-[11px] hover:shadow-md hover:scale-105 active:scale-95 transition flex items-center space-x-1">
                                <span>Cart (${totalCount})</span>
                                <i class="fa-solid fa-arrow-right text-[8px]"></i>
                            </a>
                        </div>
                    `
                });
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Clean up any legacy shared cart key
            try {
                localStorage.removeItem(window.CartManager.legacyKey);
            } catch(e) {}
            window.CartManager.updateBadge();
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW Reg failed: ', err));
            });
        }
    </script>
    @include('customer.partials.profile_modals')
    @stack('scripts')
</body>
</html>
