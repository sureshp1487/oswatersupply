<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($appSettings['business_name'] ?? 'HydroPro') . ' - Admin Dashboard')</title>

    <!-- Brand Favicon & Icons -->
    <link rel="icon" type="image/svg+xml" href="/icons/logo.svg">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    <!-- Tailwind CSS with dynamic Theme Tokens based on Customization Settings -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $appSettings["primary_color"] ?? "#0077b6" }}',
                        accent: '{{ $appSettings["accent_color"] ?? "#00b4d8" }}',
                        brand: {
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
                        electric: {
                            400: 'color-mix(in srgb, {{ $appSettings["accent_color"] ?? "#00b4d8" }} 75%, white)',
                            500: '{{ $appSettings["accent_color"] ?? "#00b4d8" }}',
                            600: 'color-mix(in srgb, {{ $appSettings["accent_color"] ?? "#00b4d8" }} 85%, black)',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --primary: {{ $appSettings["primary_color"] ?? "#0077b6" }};
            --accent: {{ $appSettings["accent_color"] ?? "#00b4d8" }};
        }
        /* Dynamic Theme helpers across Admin CRM */
        .bg-indigo-600, .bg-indigo-700 { background-color: var(--primary) !important; }
        .text-indigo-600, .text-indigo-700, .text-indigo-400 { color: var(--primary) !important; }
        .border-indigo-600, .border-indigo-500 { border-color: var(--primary) !important; }
        .hover\:bg-indigo-700:hover, .hover\:bg-indigo-600:hover { background-color: color-mix(in srgb, var(--primary) 85%, black) !important; }
        .hover\:text-indigo-700:hover, .hover\:text-indigo-600:hover { color: color-mix(in srgb, var(--primary) 85%, black) !important; }
        .bg-indigo-50 { background-color: color-mix(in srgb, var(--primary) 12%, white) !important; }
        .border-indigo-100 { border-color: color-mix(in srgb, var(--primary) 20%, white) !important; }
        .text-indigo-300 { color: color-mix(in srgb, var(--primary) 30%, white) !important; }
        .focus\:ring-indigo-500:focus { --tw-ring-color: var(--primary) !important; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(99, 102, 241, 0.2);
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased h-screen overflow-hidden flex">

    <!-- Admin Mobile Drawer Navigation Backdrop -->
    <div id="mobileDrawerBackdrop" class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm hidden md:hidden"></div>

    <!-- Admin Sidebar (Fixed, non-scrolling layout with internal navigation scroll if needed) -->
    <aside id="adminSidebar" class="w-64 bg-slate-950 text-slate-300 flex-shrink-0 h-screen fixed inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 md:static transition-transform duration-200 ease-in-out flex flex-col justify-between border-r border-indigo-950/80">
        <!-- Top Section with Sticky Brand & Scrollable Nav -->
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Header Brand (Fixed at Top) -->
            <div class="h-16 flex-shrink-0 flex items-center justify-between px-6 border-b border-indigo-950/80 bg-slate-950">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <img src="/icons/logo.svg" alt="HydroPro Logo" class="w-9 h-9 rounded-xl shadow-md p-0.5 bg-gradient-to-tr from-blue-500 to-indigo-600 object-contain">
                    <div>
                        <h2 class="text-sm font-black text-white leading-tight tracking-tight">{{ $appSettings['business_name'] ?? 'HydroPro' }}</h2>
                        <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider">Admin Console</span>
                    </div>
                </a>
                <button type="button" id="closeMobileDrawer" class="md:hidden text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links (Smooth internal scroll if screen height is small) -->
            <nav class="flex-1 overflow-y-auto sidebar-scroll p-4 space-y-1 text-xs font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-900 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-sm"></i>
                    <span>Overview</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders*') ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-900 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-sm"></i>
                    <span>Orders & Dispatch</span>
                </a>

                <!-- Dedicated Due Management Module -->
                <a href="{{ route('admin.dues.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dues*') ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-900 hover:text-white' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-sm"></i>
                    <span>Outstanding Dues</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products*') ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-900 hover:text-white' }}">
                    <i class="fa-solid fa-bottle-water w-5 text-sm"></i>
                    <span>Water Can Products</span>
                </a>

                <a href="{{ route('admin.customers.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.customers*') ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-900 hover:text-white' }}">
                    <i class="fa-solid fa-users w-5 text-sm"></i>
                    <span>Customers</span>
                </a>

                <a href="{{ route('admin.deliveries.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.deliveries*') ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-900 hover:text-white' }}">
                    <i class="fa-solid fa-truck-fast w-5 text-sm"></i>
                    <span>Delivery Schedule</span>
                </a>

                <!-- Brand & Dashboard Customization Menu -->
                <a href="{{ route('admin.customization.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.customization*') ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-900 hover:text-white' }}">
                    <i class="fa-solid fa-wand-magic-sparkles w-5 text-sm text-indigo-400"></i>
                    <div class="flex items-center justify-between flex-1">
                        <span>Customization</span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-indigo-500/30 text-indigo-300 uppercase tracking-tighter">New</span>
                    </div>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings*') ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-900 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-5 text-sm"></i>
                    <span>System Settings</span>
                </a>
            </nav>
        </div>

        <!-- User / Logout (Fixed at Bottom) -->
        <div class="flex-shrink-0 p-4 border-t border-indigo-950/80 bg-slate-950">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 truncate">
                    <div class="w-8 h-8 rounded-full bg-indigo-700 text-white flex items-center justify-center font-bold text-xs shadow-sm">A</div>
                    <div class="truncate text-xs">
                        <span class="block font-bold text-white truncate">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-indigo-400">Administrator</span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 rounded-lg hover:bg-slate-900 transition" title="Logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area (Independent Full-Height Scroll) -->
    <div class="flex-1 flex flex-col h-screen min-w-0 bg-slate-100/80 overflow-hidden">
        <!-- Top bar for Mobile / Search (Fixed) -->
        <header class="h-16 flex-shrink-0 bg-white/95 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center space-x-3">
                <button type="button" id="openMobileDrawer" class="md:hidden text-slate-700 hover:text-slate-900 text-lg p-2 rounded-xl bg-slate-50 border border-slate-200">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-sm sm:text-base font-extrabold text-slate-900 truncate">@yield('page_title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('customer.home') }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3.5 py-1.5 rounded-xl border border-indigo-100 flex items-center space-x-1.5 transition">
                    <span>View Customer App</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </header>

        <!-- Flash messages with auto-fadeout and dismiss button -->
        @if(session('success'))
            <div class="flash-alert m-6 mb-0 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-sm shadow-sm flex-shrink-0 transition-all duration-500">
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="dismissFlashAlert(this)" class="text-emerald-500 hover:text-emerald-800 p-1 rounded-lg transition" title="Dismiss">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="flash-alert m-6 mb-0 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between text-sm shadow-sm flex-shrink-0 transition-all duration-500">
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="dismissFlashAlert(this)" class="text-rose-500 hover:text-rose-800 p-1 rounded-lg transition" title="Dismiss">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="flash-alert m-6 mb-0 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between text-sm shadow-sm flex-shrink-0 transition-all duration-500">
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span class="font-semibold">{{ $errors->first() }}</span>
                </div>
                <button type="button" onclick="dismissFlashAlert(this)" class="text-rose-500 hover:text-rose-800 p-1 rounded-lg transition" title="Dismiss">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        @endif

        <!-- Scrollable Main Container -->
        <main class="flex-1 p-6 space-y-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <script>
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

        // Automatically fade out and remove all alert/flash messages after 3.5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.flash-alert');
            alerts.forEach(function(alert) {
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

        $(document).ready(function() {
            $('#openMobileDrawer').on('click', function() {
                $('#adminSidebar').removeClass('-translate-x-full');
                $('#mobileDrawerBackdrop').removeClass('hidden');
            });
            $('#closeMobileDrawer, #mobileDrawerBackdrop').on('click', function() {
                $('#adminSidebar').addClass('-translate-x-full');
                $('#mobileDrawerBackdrop').addClass('hidden');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
