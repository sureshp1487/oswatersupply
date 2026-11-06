<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best 20 Litre Water Can Delivery Near Me in Chennai | {{ $appSettings['business_name'] ?? 'AquaFlow' }}</title>
    
    <!-- Comprehensive SEO Meta Tags -->
    <meta name="description" content="Looking for reliable 20 litre water can delivery near you? {{ $appSettings['business_name'] ?? 'AquaFlow' }} delivers fresh, pure 20L drinking water cans to homes, apartments, and offices in Chennai (Velachery, Madipakkam, Anna Nagar & more). Order online with same-day doorstep delivery, flexible scheduling, and Cash on Delivery.">
    <meta name="keywords" content="20 litre water can delivery near me, 20 litre water can home delivery, water can suppliers in Chennai, order water can online, 20 litre water can price, drinking water home delivery, book water can online, monthly water can subscription, office water can supply, same day water delivery, apartment water delivery, bulk water can supply, doorstep water delivery, water can COD delivery, water can delivery in Chennai, water supplier in Madipakkam, water can delivery in Velachery">
    <meta name="author" content="{{ $appSettings['business_name'] ?? 'AquaFlow' }} Water Logistics">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Open Graph (Facebook / WhatsApp / LinkedIn) -->
    <meta property="og:locale" content="en_IN">
    <meta property="og:type" content="website">
    <meta property="og:title" content="20 Litre Water Can Supply – Fresh Drinking Water Delivered to Your Doorstep">
    <meta property="og:description" content="Order 20 litre drinking water cans online in Chennai. Reliable doorstep delivery for homes, apartments, and offices with cash on delivery and monthly subscriptions.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:site_name" content="{{ $appSettings['business_name'] ?? 'AquaFlow' }} Water Logistics">
    <meta property="og:image" content="{{ asset('asset/image/watercan-312.png') }}">
    <meta property="og:image:width" content="600">
    <meta property="og:image:height" content="600">
    <meta property="og:image:alt" content="20 Litre Drinking Water Can Home Delivery">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Best 20 Litre Water Can Delivery Near Me in Chennai">
    <meta name="twitter:description" content="Fresh, pure 20L water cans delivered to your doorstep. Easy online booking, scheduled time slots, and reliable service across Chennai.">
    <meta name="twitter:image" content="{{ asset('asset/image/watercan-312.png') }}">

    <!-- Schema.org JSON-LD Structured Data for Local Business & FAQ -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "{{ $appSettings['business_name'] ?? 'AquaFlow' }} Water Logistics",
      "image": "{{ asset('asset/image/watercan-312.png') }}",
      "@id": "{{ url('/') }}",
      "url": "{{ url('/') }}",
      "telephone": "{{ $appSettings['business_phone'] ?? '+919876543210' }}",
      "priceRange": "₹₹",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Chennai",
        "addressRegion": "Tamil Nadu",
        "postalCode": "600040",
        "addressCountry": "IN"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 13.0827,
        "longitude": 80.2707
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday",
          "Sunday"
        ],
        "opens": "06:00",
        "closes": "21:30"
      },
      "areaServed": [
        "Chennai", "Anna Nagar", "Velachery", "Madipakkam", "T Nagar", "Adyar", "Mylapore", "Guindy", "OMR", "Porur"
      ],
      "sameAs": []
    }
    </script>
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "How does the 1:1 empty water can exchange system work?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "When you place an order, our driver delivers the filled, sanitized 20-litre water cans and collects the corresponding empty containers. Your customer digital ledger tracks empties in hand automatically."
          }
        },
        {
          "@type": "Question",
          "name": "Can I schedule automated recurring deliveries?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. Customers can select preferred morning (8-10 AM, 10 AM-12 PM) or afternoon (2-4 PM, 4-6 PM) time slots with daily, alternate day, or weekly auto-replenishment subscriptions."
          }
        },
        {
          "@type": "Question",
          "name": "What payment modes are supported for water can delivery?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "We support Razorpay Online (UPI, Google Pay, PhonePe, Cards, NetBanking), Cash on Delivery (COD) at your doorstep, and Pay Later for approved corporate monthly credit accounts."
          }
        },
        {
          "@type": "Question",
          "name": "How quickly can I get 20 litre water can delivery in Chennai?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "We offer express same-day water can delivery with an average delivery speed of under 90 minutes from booking across active Chennai metropolitan service zones."
          }
        }
      ]
    }
    </script>
    
    <!-- Meta & Icons -->
    <link rel="icon" type="image/svg+xml" href="/icons/logo.svg">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    
    <!-- Fonts & Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $appSettings["primary_color"] ?? "#0077b6" }}',
                        accent: '{{ $appSettings["accent_color"] ?? "#00b4d8" }}',
                        aqua: {
                            50: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 8%, white)',
                            100: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 15%, white)',
                            200: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 30%, white)',
                            300: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 50%, white)',
                            400: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 75%, white)',
                            500: '{{ $appSettings["primary_color"] ?? "#0077b6" }}',
                            600: '{{ $appSettings["primary_color"] ?? "#0077b6" }}',
                            700: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 85%, black)',
                            800: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 70%, black)',
                            900: 'color-mix(in srgb, {{ $appSettings["primary_color"] ?? "#0077b6" }} 50%, black)',
                        },
                        navy: {
                            900: '#0f172a',
                            950: '#0b1329',
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
            --brand-primary: {{ $appSettings["primary_color"] ?? "#0077b6" }};
            --brand-accent: {{ $appSettings["accent_color"] ?? "#00b4d8" }};
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Dynamic Customization Classes */
        [class*="text-[#0077b6]"] { color: var(--brand-primary) !important; }
        [class*="bg-[#0077b6]"] { background-color: var(--brand-primary) !important; }
        [class*="border-[#0077b6]"] { border-color: var(--brand-primary) !important; }
        [class*="text-[#00b4d8]"] { color: var(--brand-accent) !important; }
        [class*="bg-[#00b4d8]"] { background-color: var(--brand-accent) !important; }
        [class*="border-[#00b4d8]"] { border-color: var(--brand-accent) !important; }
        [class*="from-[#0077b6]"] { --tw-gradient-from: var(--brand-primary) !important; }
        [class*="to-[#00b4d8]"] { --tw-gradient-to: var(--brand-accent) !important; }
        [class*="to-[#0096c7]"] { --tw-gradient-to: var(--brand-accent) !important; }
        [class*="via-[#008cc9]"] { --tw-gradient-stops: var(--brand-primary), color-mix(in srgb, var(--brand-primary) 70%, var(--brand-accent)), var(--brand-accent) !important; }
        [class*="hover:text-[#0077b6]"]:hover { color: var(--brand-primary) !important; }
        [class*="hover:bg-[#006499]"]:hover, [class*="hover:bg-[#005f94]"]:hover { background-color: color-mix(in srgb, var(--brand-primary) 85%, black) !important; }
        [class*="shadow-[#0077b6]"] { --tw-shadow-color: color-mix(in srgb, var(--brand-primary) 30%, transparent) !important; }

        /* Ensure icon containers have contrast and visible icons */
        .service-icon-box {
            background-color: color-mix(in srgb, var(--brand-primary) 12%, #f0f9ff);
            color: var(--brand-primary);
        }
        .service-card:hover .service-icon-box {
            background-color: var(--brand-primary);
            color: #ffffff;
        }

        /* Smooth card hover lift & shadow animation */
        .step-card {
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .step-card:hover {
            transform: translateY(-8px);
            border-color: var(--brand-primary) !important;
            box-shadow: 0 20px 30px -10px color-mix(in srgb, var(--brand-primary) 25%, transparent);
        }
        .step-card:hover .step-badge {
            background-color: var(--brand-primary) !important;
            color: #ffffff !important;
            box-shadow: 0 10px 18px -4px color-mix(in srgb, var(--brand-primary) 40%, transparent);
            transform: scale(1.08);
        }
        .step-card .step-badge {
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased selection:bg-[var(--brand-primary)] selection:text-white">

    <!-- Clean White Navigation Header matching user image -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            
            <!-- Brand Logo matching user screenshot -->
            <a href="/" class="flex items-center space-x-3 group min-w-0">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#0077b6] to-[#00b4d8] p-0.5 shadow-md shadow-[#0077b6]/20 flex items-center justify-center shrink-0">
                    <img src="/icons/logo.svg" alt="AquaFlow Logo" class="w-full h-full object-contain p-1">
                </div>
                <div class="min-w-0">
                    <span class="text-lg font-black text-slate-900 tracking-tight block leading-none">
                        AquaFlow
                    </span>
                    <span class="text-[9px] font-extrabold text-[#0077b6] tracking-wider uppercase block mt-1">
                        WATER LOGISTICS
                    </span>
                </div>
            </a>

            <!-- Desktop Nav Links matching screenshot: Home, Products, How It Works, Service Areas, FAQ, Contact -->
            <nav class="hidden md:flex items-center space-x-7 text-xs font-bold text-slate-600">
                <a href="/" class="text-[#0077b6] hover:text-[#005f94] transition">Home</a>
                <a href="#services" class="hover:text-[#0077b6] transition">Our Services</a>
                <a href="#products" class="hover:text-[#0077b6] transition">Products & Pricing</a>
                <a href="#how-it-works" class="hover:text-[#0077b6] transition">How It Works</a>
                <a href="#check-pincode" class="hover:text-[#0077b6] transition">Service Areas</a>
                <a href="#faq" class="hover:text-[#0077b6] transition">FAQ</a>
                <a href="#corporate" class="hover:text-[#0077b6] transition">Contact</a>
            </nav>

            <!-- Actions: Sign In only -->
            <div class="flex items-center space-x-3 sm:space-x-5 shrink-0">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs transition">
                            Admin Console &rarr;
                        </a>
                    @elseif(auth()->user()->isDriver())
                        <a href="{{ route('driver.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs transition">
                            Driver Portal &rarr;
                        </a>
                    @else
                        <a href="{{ route('customer.home') }}" class="px-4 py-2.5 rounded-xl bg-[#0077b6] hover:bg-[#005f94] text-white font-bold text-xs transition shadow-sm">
                            Dashboard &rarr;
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 hover:text-[#0077b6] hover:bg-slate-50 transition">
                        Sign In
                    </a>
                @endauth

                <!-- Mobile Hamburger Button -->
                <button type="button" id="mobileMenuBtn" onclick="toggleMobileNav()" class="md:hidden w-10 h-10 rounded-xl bg-slate-100 text-slate-700 hover:text-slate-900 flex items-center justify-center transition">
                    <i id="mobileMenuIcon" class="fa-solid fa-bars text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Slide-down Navigation -->
        <div id="mobileNavMenu" class="hidden md:hidden bg-white border-b border-slate-100 px-4 py-4 space-y-2 shadow-lg">
            <a href="/" onclick="toggleMobileNav()" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-sky-50 hover:text-[#0077b6] transition">Home</a>
            <a href="#products" onclick="toggleMobileNav()" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-sky-50 hover:text-[#0077b6] transition">Products</a>
            <a href="#how-it-works" onclick="toggleMobileNav()" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-sky-50 hover:text-[#0077b6] transition">How It Works</a>
            <a href="#check-pincode" onclick="toggleMobileNav()" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-sky-50 hover:text-[#0077b6] transition">Service Areas</a>
            <a href="#faq" onclick="toggleMobileNav()" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-sky-50 hover:text-[#0077b6] transition">FAQ</a>
            <a href="#corporate" onclick="toggleMobileNav()" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-sky-50 hover:text-[#0077b6] transition">Contact</a>
        </div>
    </header>

    <!-- Hero Section matching user screenshot -->
    <section class="relative bg-white pt-10 sm:pt-16 pb-16 sm:pb-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Hero Left: Typography & Buttons matching screenshot -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <!-- Pill badge: 100% Hygienic Packaged Drinking Water -->
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-sky-50 border border-sky-100 text-xs font-bold text-[#0077b6]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#00b4d8]"></span>
                        <span>100% Hygienic Packaged Drinking Water in Chennai</span>
                    </div>

                    <!-- Heading matching user reference & SEO -->
                    <h1 class="text-4xl sm:text-6xl font-black text-slate-900 tracking-tight leading-[1.08]">
                        20 Litre Water Can Supply – <br>
                        <span class="text-[#0077b6]">Fresh Drinking Water</span> <br>
                        Delivered to Your Doorstep
                    </h1>

                    <!-- SEO Subtitle matching user request -->
                    <p class="text-sm sm:text-base text-slate-600 max-w-xl font-normal leading-relaxed">
                        Looking for reliable <strong>20 litre water can delivery near you</strong>? We make drinking water ordering simple and convenient with doorstep delivery for homes, apartments, offices, shops and businesses.
                    </p>

                    <p class="text-xs sm:text-sm text-slate-500 max-w-xl font-normal leading-relaxed">
                        Order your 20-litre drinking water cans online and choose your preferred delivery date and time. Whether you need a single water can or regular monthly water supply, our service makes it easy to manage your drinking water requirements.
                    </p>

                    <!-- CTAs matching user screenshot: Order Water Now -> & Explore How It Works -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center gap-3.5">
                        <a href="{{ route('customer.book') }}" class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-[#0077b6] hover:bg-[#006499] active:scale-95 text-white font-extrabold text-sm transition shadow-lg shadow-[#0077b6]/30 text-center flex items-center justify-center space-x-2">
                            <span>Order Water Can Online &rarr;</span>
                        </a>
                        <a href="#services" class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm transition border border-slate-200 text-center">
                            Explore Services & Pricing
                        </a>
                    </div>
                </div>

                <!-- Hero Right: Water Can Display Graphic Card using real watercan-312 image -->
                <div class="lg:col-span-5 relative">
                    <div class="bg-gradient-to-br from-[#0077b6] via-[#008cc9] to-[#0096c7] rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden flex flex-col items-center justify-center min-h-[360px] sm:min-h-[440px]">
                        
                        <!-- Top Floating Pill: Doorstep Delivery -->
                        <div class="absolute top-5 left-5 z-20 bg-white/95 backdrop-blur px-3 py-1.5 rounded-full shadow-sm flex items-center space-x-2 text-xs font-bold text-slate-800">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Express Doorstep Delivery</span>
                        </div>

                        <!-- 20L Water Can Image from public/asset/image/watercan-312.png -->
                        <div class="relative z-10 flex flex-col items-center justify-center pt-6 w-full">
                            <img 
                                src="{{ asset('asset/image/watercan-312.png') }}" 
                                alt="20 Litre Drinking Water Can Home Delivery Near Me" 
                                class="max-h-72 sm:max-h-80 w-auto object-contain drop-shadow-2xl transition-transform duration-500 hover:scale-105"
                            >
                            <span class="text-xs font-bold text-white/90 mt-3 tracking-wide">Standard 20-Litre Sanitized Container</span>
                        </div>

                        <!-- Subtle Bottom Wave reflection circles -->
                        <div class="absolute -bottom-10 -right-10 w-48 h-48 rounded-full bg-white/10 pointer-events-none"></div>
                        <div class="absolute -top-10 -left-10 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SEO Highlights Banner: Why Choose Our Water Can Supply Service? -->
    <section class="py-10 bg-sky-50/60 border-y border-sky-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-center">
                <div class="bg-white p-3.5 rounded-2xl border border-sky-100 shadow-xs">
                    <i class="fa-solid fa-mobile-screen-button text-[#0077b6] text-lg mb-1.5 block"></i>
                    <span class="text-xs font-extrabold text-slate-800 block">Convenient Online Ordering</span>
                    <span class="text-[10px] text-slate-500">Order in just a few taps</span>
                </div>
                <div class="bg-white p-3.5 rounded-2xl border border-sky-100 shadow-xs">
                    <i class="fa-solid fa-clock text-[#0077b6] text-lg mb-1.5 block"></i>
                    <span class="text-xs font-extrabold text-slate-800 block">Flexible Scheduling</span>
                    <span class="text-[10px] text-slate-500">Choose morning/evening slot</span>
                </div>
                <div class="bg-white p-3.5 rounded-2xl border border-sky-100 shadow-xs">
                    <i class="fa-solid fa-truck-ramp-box text-[#0077b6] text-lg mb-1.5 block"></i>
                    <span class="text-xs font-extrabold text-slate-800 block">Reliable Doorstep Delivery</span>
                    <span class="text-[10px] text-slate-500">Delivered right to your door</span>
                </div>
                <div class="bg-white p-3.5 rounded-2xl border border-sky-100 shadow-xs">
                    <i class="fa-solid fa-wallet text-[#0077b6] text-lg mb-1.5 block"></i>
                    <span class="text-xs font-extrabold text-slate-800 block">Cash on Delivery & UPI</span>
                    <span class="text-[10px] text-slate-500">Multiple payment options</span>
                </div>
                <div class="bg-white p-3.5 rounded-2xl border border-sky-100 shadow-xs">
                    <i class="fa-solid fa-arrows-rotate text-[#0077b6] text-lg mb-1.5 block"></i>
                    <span class="text-xs font-extrabold text-slate-800 block">1:1 Can Exchange</span>
                    <span class="text-[10px] text-slate-500">Transparent empty container ledger</span>
                </div>
                <div class="bg-white p-3.5 rounded-2xl border border-sky-100 shadow-xs">
                    <i class="fa-solid fa-calendar-check text-[#0077b6] text-lg mb-1.5 block"></i>
                    <span class="text-xs font-extrabold text-slate-800 block">Monthly Subscriptions</span>
                    <span class="text-[10px] text-slate-500">For homes, offices & apartments</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted By / Marquee Logotypes -->
    <section class="py-7 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-[11px] font-extrabold uppercase tracking-widest text-slate-400 mb-5">
                Supplying Clean Drinking Water To Homes, Apartments, Offices & Corporates
            </p>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-6 items-center justify-center opacity-70">
                <div class="flex items-center justify-center space-x-2 text-xs font-black text-slate-600">
                    <i class="fa-solid fa-building text-[#0077b6] text-sm"></i>
                    <span>TECHPARK HQ</span>
                </div>
                <div class="flex items-center justify-center space-x-2 text-xs font-black text-slate-600">
                    <i class="fa-solid fa-hospital text-[#0077b6] text-sm"></i>
                    <span>HEALTHCARE+</span>
                </div>
                <div class="flex items-center justify-center space-x-2 text-xs font-black text-slate-600">
                    <i class="fa-solid fa-industry text-[#0077b6] text-sm"></i>
                    <span>IND-ZONE MFG</span>
                </div>
                <div class="flex items-center justify-center space-x-2 text-xs font-black text-slate-600">
                    <i class="fa-solid fa-graduation-cap text-[#0077b6] text-sm"></i>
                    <span>APEX CAMPUS</span>
                </div>
                <div class="flex items-center justify-center space-x-2 text-xs font-black text-slate-600">
                    <i class="fa-solid fa-city text-[#0077b6] text-sm"></i>
                    <span>URBAN LIVING</span>
                </div>
                <div class="flex items-center justify-center space-x-2 text-xs font-black text-slate-600">
                    <i class="fa-solid fa-hotel text-[#0077b6] text-sm"></i>
                    <span>ROYAL SUITES</span>
                </div>
            </div>
        </div>
    </section>

    <!-- How AquaFlow Works in 4 Steps Section (Matching user reference) -->
    <section id="how-it-works" class="py-16 sm:py-24 bg-white text-slate-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16 space-y-3">
                <span class="inline-block px-4 py-1 rounded-full bg-sky-50 border border-sky-100 text-[#0077b6] text-[11px] font-extrabold uppercase tracking-wider">
                    Simple & Seamless
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    How AquaFlow Works in 4 Steps
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
                    Pure drinking water at your doorstep without complicated contracts or unexpected delays.
                </p>
            </div>

            <!-- 4 Step Cards Grid with Clean Hover Animation on all cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                
                <!-- Step 1 -->
                <div class="step-card bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm flex flex-col justify-between group cursor-pointer">
                    <div class="space-y-4 sm:space-y-5">
                        <div class="step-badge w-12 h-12 rounded-2xl bg-sky-50 text-[#0077b6] flex items-center justify-center text-sm font-black">
                            1
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-[#0077b6] transition-colors">Select your water can</h3>
                            <p class="text-xs text-slate-500 leading-relaxed mt-2 font-normal">
                                Choose from standard 20 Litre, compact 10 Litre, or portable 5 Litre water cans based on your requirement.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="step-card bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm flex flex-col justify-between group cursor-pointer">
                    <div class="space-y-4 sm:space-y-5">
                        <div class="step-badge w-12 h-12 rounded-2xl bg-sky-50 text-[#0077b6] flex items-center justify-center text-sm font-black">
                            2
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-[#0077b6] transition-colors">Choose delivery date & time</h3>
                            <p class="text-xs text-slate-500 leading-relaxed mt-2 font-normal">
                                Pick a convenient delivery date and available time slot (8 AM–10 AM, 10 AM–12 PM, 2 PM–4 PM, or 4 PM–6 PM).
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 3 (Equal Clean State with Hover Animation) -->
                <div class="step-card bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm flex flex-col justify-between group cursor-pointer">
                    <div class="space-y-4 sm:space-y-5">
                        <div class="step-badge w-12 h-12 rounded-2xl bg-sky-50 text-[#0077b6] flex items-center justify-center text-sm font-black">
                            3
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-[#0077b6] transition-colors">Confirm your order</h3>
                            <p class="text-xs text-slate-500 leading-relaxed mt-2 font-normal">
                                Choose to pay online via Razorpay, Cash on Delivery (COD), or Pay Later with approved customer credit terms.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="step-card bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm flex flex-col justify-between group cursor-pointer">
                    <div class="space-y-4 sm:space-y-5">
                        <div class="step-badge w-12 h-12 rounded-2xl bg-sky-50 text-[#0077b6] flex items-center justify-center text-sm font-black">
                            4
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-[#0077b6] transition-colors">Receive water at doorstep</h3>
                            <p class="text-xs text-slate-500 leading-relaxed mt-2 font-normal">
                                Verify your delivery OTP, receive sanitized cans, hand over your empty containers, and enjoy pure drinking water.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Check Service In Your Area Widget (Matching Reference) -->
            <div id="check-pincode" class="mt-12 sm:mt-16 max-w-xl mx-auto">
                <div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200 shadow-sm transition hover:shadow-md">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-800 block mb-4">
                        CHECK SERVICE IN YOUR AREA
                    </span>
                    <form id="pincodeCheckForm" onsubmit="event.preventDefault(); checkServicePincode();" class="flex flex-col sm:flex-row items-stretch gap-2.5 sm:gap-3">
                        <input 
                            type="text" 
                            id="pincodeInput" 
                            name="pincode" 
                            value="" 
                            maxlength="6" 
                            placeholder="Enter 6-digit Pincode (e.g. 600040)"
                            class="flex-1 px-4 py-3 rounded-2xl border border-slate-200 text-slate-900 text-sm font-semibold tracking-wide placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0077b6]/20 focus:border-[#0077b6] transition"
                        >
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-[#0077b6] hover:bg-[#006499] active:scale-95 text-white font-extrabold text-xs transition shrink-0 shadow-sm text-center"
                        >
                            Check Pincode
                        </button>
                    </form>

                    <!-- Feedback Alert Box (Hidden until customer types and clicks Check) -->
                    <div id="pincodeResult" class="hidden mt-4 p-3.5 rounded-2xl text-xs font-medium border">
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Our Water Can Delivery Services (Dedicated SEO Section for 3 Customer Groups) -->
    <section id="services" class="py-20 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
                <span class="inline-block px-4 py-1 rounded-full bg-sky-50 border border-sky-100 text-[#0077b6] text-[11px] font-extrabold uppercase tracking-wider">
                    Our Water Can Delivery Services
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Pure Drinking Water Solutions Tailored to Your Needs
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                    From daily household hydration to scheduled apartment societies and bulk corporate supply contracts across Chennai.
                </p>
            </div>

            <!-- 3 Core Customer Group Pillars matching SEO recommendations -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Pillar 1: Home Customers -->
                <div class="service-card bg-slate-50/80 rounded-3xl p-7 sm:p-8 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-[#0077b6]/40 transition flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div class="service-icon-box w-14 h-14 rounded-2xl flex items-center justify-center text-2xl transition shadow-sm">
                            <i class="fa-solid fa-house-chimney-water"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">20 Litre Water Can Home Delivery</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Daily and weekly clean drinking water requirements for families. Enjoy reliable same-day delivery, 1:1 empty can exchange, and Cash on Delivery (COD) or UPI at your doorstep.
                        </p>
                        <ul class="text-xs text-slate-700 space-y-2 pt-2 border-t border-slate-200/60">
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-[#0077b6] text-[11px]"></i>
                                <span>Morning & evening time slot selection</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-[#0077b6] text-[11px]"></i>
                                <span>Sanitized food-grade 20L refill cans</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-[#0077b6] text-[11px]"></i>
                                <span>No contracts or minimum lock-ins</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-6">
                        <a href="{{ route('customer.book') }}" class="block w-full py-3 rounded-xl bg-white border border-slate-200 text-slate-800 font-extrabold text-xs text-center hover:bg-[#0077b6] hover:text-white hover:border-[#0077b6] transition">
                            Order for Home &rarr;
                        </a>
                    </div>
                </div>

                <!-- Pillar 2: Offices & Companies -->
                <div class="service-card bg-slate-50/80 rounded-3xl p-7 sm:p-8 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-[#0077b6]/40 transition flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div class="service-icon-box w-14 h-14 rounded-2xl flex items-center justify-center text-2xl transition shadow-sm">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">Office & Commercial Water Supply</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Continuous bulk water supply for workplaces, IT parks, retail showrooms, clinics, and commercial pantries with dedicated fleet dispatch and monthly GST invoicing.
                        </p>
                        <ul class="text-xs text-slate-700 space-y-2 pt-2 border-t border-slate-200/60">
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-[#0077b6] text-[11px]"></i>
                                <span>Fixed recurring deliveries (daily / alternate)</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-[#0077b6] text-[11px]"></i>
                                <span>Bulk volume discounted pricing tiers</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-[#0077b6] text-[11px]"></i>
                                <span>Consolidated 30-day monthly statement</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-6">
                        <a href="#corporate" class="block w-full py-3 rounded-xl bg-white border border-slate-200 text-slate-800 font-extrabold text-xs text-center hover:bg-[#0077b6] hover:text-white hover:border-[#0077b6] transition">
                            Request Office Quote &rarr;
                        </a>
                    </div>
                </div>

                <!-- Pillar 3: Apartments & Subscriptions -->
                <div class="service-card bg-slate-50/80 rounded-3xl p-7 sm:p-8 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-[#0077b6]/40 transition flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div class="service-icon-box w-14 h-14 rounded-2xl flex items-center justify-center text-2xl transition shadow-sm">
                            <i class="fa-solid fa-building-user"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">Apartment & Society Subscriptions</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Centralized monthly water can delivery for gated communities, residential blocks, and hostels. Never run out of clean drinking water with automated delivery schedules.
                        </p>
                        <ul class="text-xs text-slate-700 space-y-2 pt-2 border-t border-slate-200/60">
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-[#0077b6] text-[11px]"></i>
                                <span>Scheduled multi-flat community delivery</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-[#0077b6] text-[11px]"></i>
                                <span>Pause anytime during travel or vacation</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-[#0077b6] text-[11px]"></i>
                                <span>Direct delivery right to your floor</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-6">
                        <a href="{{ route('register') }}" class="block w-full py-3 rounded-xl bg-white border border-slate-200 text-slate-800 font-extrabold text-xs text-center hover:bg-[#0077b6] hover:text-white hover:border-[#0077b6] transition">
                            Start Monthly Subscription &rarr;
                        </a>
                    </div>
                </div>

            </div>

            <!-- SEO List of Services matching user query -->
            <div class="mt-12 bg-sky-50/50 rounded-3xl p-6 sm:p-8 border border-sky-100">
                <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4">Complete Water Can Delivery Features & Services</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs text-slate-700">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check text-[#0077b6]"></i>
                        <span>20 Litre Water Can Home Delivery</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check text-[#0077b6]"></i>
                        <span>Office & Commercial Water Supply</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check text-[#0077b6]"></i>
                        <span>Apartment Water Can Delivery</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check text-[#0077b6]"></i>
                        <span>Monthly Water Can Subscription</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check text-[#0077b6]"></i>
                        <span>Scheduled Water Can Delivery</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check text-[#0077b6]"></i>
                        <span>Online Booking & Order Tracking</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check text-[#0077b6]"></i>
                        <span>Cash on Delivery (COD) & Online UPI</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check text-[#0077b6]"></i>
                        <span>Emergency Same-Day Water Can Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products & Pricing Catalog (Clean White/Soft-Grey background) -->
    <section id="products" class="py-20 bg-slate-50/70 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-4">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-[#0077b6]">Available Water Cans</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-1">
                        10-Stage RO + UV Purified, Sanitized Refillable Containers
                    </h2>
                </div>
                <a href="{{ route('customer.book') }}" class="text-xs font-bold text-[#0077b6] hover:underline flex items-center space-x-1.5">
                    <span>View All Sizes</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 hover:border-[#0077b6]/40 hover:shadow-lg transition flex flex-col justify-between group">
                    <div>
                        <!-- Product Visual -->
                        <div class="w-full h-48 rounded-2xl bg-slate-100 overflow-hidden relative mb-4">
                            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=500' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-slate-800 font-extrabold text-[10px] px-2.5 py-1 rounded-lg border border-slate-200">
                                {{ $product->volume }} {{ $product->unit }}
                            </span>
                            <span class="absolute top-3 right-3 bg-[#0077b6] text-white font-black text-[9px] uppercase px-2 py-0.5 rounded shadow">
                                1:1 Exchange
                            </span>
                        </div>

                        <h4 class="text-sm font-extrabold text-slate-900 leading-snug group-hover:text-[#0077b6] transition-colors">{{ $product->name }}</h4>
                        <p class="text-xs text-slate-500 line-clamp-2 mt-1.5">{{ $product->description }}</p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-lg font-black text-slate-900">₹{{ number_format($product->selling_price, 2) }}</span>
                            <span class="text-[10px] text-slate-400 block">All taxes incl.</span>
                        </div>
                        <a href="{{ route('customer.book') }}" class="px-4 py-2 rounded-xl bg-[#0077b6] hover:bg-[#006499] text-white font-black text-xs transition shadow-sm active:scale-95">
                            Order Now
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-12 text-slate-400">
                    No active products listed in catalog.
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Infrastructure & Purity Protocol -->
    <section id="features" class="py-20 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Left: Purification Process Visuals -->
                <div class="space-y-6">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-[#0077b6] bg-sky-50 px-3 py-1 rounded-full border border-sky-100">
                        Purity Protocol
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                        Certified 10-Stage Micro-Filtration Standard
                    </h2>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Every batch of water processed under the AquaFlow brand passes rigorous continuous sensor checks for dissolved solids, mineral balance, and microbiological safety.
                    </p>

                    <div class="space-y-4 pt-2">
                        <div class="flex items-start space-x-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-sky-100 text-[#0077b6] flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-filter"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Reverse Osmosis + Multi-Grade Sand Filtration</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">Removes particulate contaminants, heavy metals, and regulates optimum total dissolved solids (TDS 100-150).</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-sky-100 text-[#0077b6] flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-sun"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">High-Intensity UV & Ozonation Barrier</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">Dual-wavelength UV irradiation followed by medical-grade ozonation for complete microbial eradication.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-sky-100 text-[#0077b6] flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-spray-can-sparkles"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Automated 5-Step Internal Container Wash</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">High-pressure rotary chemical rinse, sanitized thermal flush, and contact-free hermetic sealing.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Route Dispatch & SLA Card -->
                <div class="bg-gradient-to-br from-sky-50 to-blue-50 p-8 rounded-3xl border border-sky-100 relative space-y-6">
                    <div class="flex items-center justify-between border-b border-sky-200/60 pb-4">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider">Fleet Telemetry</span>
                        <span class="text-xs text-[#0077b6] font-bold flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#0077b6] animate-ping"></span>
                            <span>Live Route Optimization</span>
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 rounded-2xl bg-white border border-sky-100 flex items-center justify-between shadow-sm">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider">Daily City Dispatch</span>
                                <h5 class="text-sm font-bold text-slate-900">Chennai Metropolitan Hubs</h5>
                            </div>
                            <span class="text-xs font-mono font-bold text-[#0077b6] bg-sky-100/70 px-2.5 py-1 rounded-lg">Active Zone</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-white border border-sky-100 flex items-center justify-between shadow-sm">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider">Average Delivery Speed</span>
                                <h5 class="text-sm font-bold text-slate-900">Under 90 Minutes from Booking</h5>
                            </div>
                            <span class="text-xs font-mono font-bold text-[#0077b6] bg-sky-100/70 px-2.5 py-1 rounded-lg">Express SLA</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-white border border-sky-100 flex items-center justify-between shadow-sm">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider">Empty Can Balance Guarantee</span>
                                <h5 class="text-sm font-bold text-slate-900">1:1 Can Barcode Exchange</h5>
                            </div>
                            <span class="text-xs font-mono font-bold text-emerald-700 bg-emerald-100/70 px-2.5 py-1 rounded-lg">Instant Ledger</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('customer.book') }}" class="w-full py-4 rounded-2xl bg-[#0077b6] hover:bg-[#006499] text-white font-black text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-md shadow-[#0077b6]/25 active:scale-95 transition">
                            <span>Book First Water Delivery</span>
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Corporate Bulk Volume Inquiries Section (Blue Accent Banner) -->
    <section id="corporate" class="py-16 sm:py-20 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-[#0077b6] to-[#0096c7] rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                    <div class="lg:col-span-8 space-y-3">
                        <span class="text-[11px] font-extrabold uppercase tracking-widest bg-white/20 text-white px-3 py-1 rounded-full border border-white/20 inline-block">
                            Bulk Volume Inquiries
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                            Supplying 50+ Cans Weekly To Your Facility?
                        </h2>
                        <p class="text-xs sm:text-sm text-sky-100 max-w-2xl leading-relaxed">
                            Lock in customized corporate price breaks, dedicated monthly billing cycles, and a designated driver fleet for your business offices, commercial kitchen, or institutional campus.
                        </p>
                    </div>
                    <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3">
                        <a href="{{ route('register') }}" class="px-7 py-3.5 rounded-xl bg-white text-[#0077b6] font-black text-xs uppercase tracking-wider hover:bg-slate-50 transition shadow-md text-center">
                            Open Corporate Account &rarr;
                        </a>
                        <a href="tel:+919876543210" class="px-7 py-3.5 rounded-xl bg-sky-900/60 hover:bg-sky-900/80 text-white font-extrabold text-xs transition border border-white/20 text-center flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-phone text-xs"></i>
                            <span>Call Enterprise Desk</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 20 SEO Knowledge Guides & FAQ (Complete Coverage of 20 Topics) -->
    <section id="faq" class="py-20 bg-white border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#0077b6] bg-sky-50 px-3.5 py-1 rounded-full border border-sky-100">
                    Drinking Water Guide & FAQ
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Frequently Asked Questions & Water Delivery Guide
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto">
                    Everything you need to know about 20-litre water can supply, delivery timings, pricing, subscriptions, and hygiene standards.
                </p>
            </div>

            <!-- SEO FAQ Accordions covering key user topics -->
            <div class="space-y-3.5">
                <!-- Topic 1 & 2: Near me & Home delivery -->
                <details class="group bg-slate-50 p-5 rounded-2xl border border-slate-200/80 transition [&_summary::-webkit-details-marker]:hidden" open>
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-sm text-slate-900">
                        <span>Best 20 Litre Water Can Delivery Near Me – How fast is delivery?</span>
                        <span class="text-[#0077b6] transition group-open:rotate-180"><i class="fa-solid fa-chevron-down text-xs"></i></span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                        We deliver 20-litre drinking water cans across major Chennai localities including Anna Nagar, Velachery, Madipakkam, T Nagar, Adyar, and Porur. Most orders are fulfilled within 90 minutes of booking, or you can select preferred morning (8 AM–12 PM) and afternoon (2 PM–6 PM) delivery windows.
                    </p>
                </details>

                <!-- Topic 3 & 5: Pricing & Chennai suppliers -->
                <details class="group bg-slate-50 p-5 rounded-2xl border border-slate-200/80 transition [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-sm text-slate-900">
                        <span>What is the affordable 20 litre water can price in Chennai?</span>
                        <span class="text-[#0077b6] transition group-open:rotate-180"><i class="fa-solid fa-chevron-down text-xs"></i></span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                        Our standard 20-litre sanitized pure mineral water can refill starts at ₹50.00 inclusive of all taxes. There is 0 delivery fee for standard deliveries. If you have an empty can from another certified brand, our 1:1 can exchange applies with zero container deposit fee.
                    </p>
                </details>

                <!-- Topic 8 & 13: Subscriptions for Apartments & Homes -->
                <details class="group bg-slate-50 p-5 rounded-2xl border border-slate-200/80 transition [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-sm text-slate-900">
                        <span>How does the monthly water can subscription for homes and apartments work?</span>
                        <span class="text-[#0077b6] transition group-open:rotate-180"><i class="fa-solid fa-chevron-down text-xs"></i></span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                        You can set your weekly frequency (e.g. 2 cans on Monday & Thursday) through your customer dashboard. The system automatically schedules deliveries without requiring re-orders. You can pause or adjust delivery dates anytime during holidays with one click.
                    </p>
                </details>

                <!-- Topic 9 & 14: Office water can supply -->
                <details class="group bg-slate-50 p-5 rounded-2xl border border-slate-200/80 transition [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-sm text-slate-900">
                        <span>Do you provide water can delivery for offices, companies, and commercial pantries?</span>
                        <span class="text-[#0077b6] transition group-open:rotate-180"><i class="fa-solid fa-chevron-down text-xs"></i></span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                        Yes! We supply dedicated bulk water cans for commercial offices, co-working spaces, and IT facilities. Corporate accounts receive priority morning dispatch, bulk tier pricing, and consolidated 30-day GST tax invoices.
                    </p>
                </details>

                <!-- Topic 17 & 18: Cash on delivery & online booking -->
                <details class="group bg-slate-50 p-5 rounded-2xl border border-slate-200/80 transition [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-sm text-slate-900">
                        <span>Can I pay with Cash on Delivery (COD) or UPI at doorstep?</span>
                        <span class="text-[#0077b6] transition group-open:rotate-180"><i class="fa-solid fa-chevron-down text-xs"></i></span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                        Yes. We support Cash on Delivery (COD), UPI (GPay, PhonePe, Paytm), and instant Razorpay online payments. You can also pay later if your account has an approved monthly credit balance.
                    </p>
                </details>

                <!-- Topic 11 & 12: Benefits and water purity inspection -->
                <details class="group bg-slate-50 p-5 rounded-2xl border border-slate-200/80 transition [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-sm text-slate-900">
                        <span>What are the purity and quality standards of your 20-litre water cans?</span>
                        <span class="text-[#0077b6] transition group-open:rotate-180"><i class="fa-solid fa-chevron-down text-xs"></i></span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                        All water undergoes 10-stage purification including reverse osmosis, high-intensity UV irradiation, and ozonation. Every container is sanitized through multi-stage high-pressure internal washes and hermetically sealed to preserve fresh taste and safety.
                    </p>
                </details>
            </div>

            <!-- SEO 20 Topic Quick Directory Links (Indexable keyword cards for Indian search queries) -->
            <div class="mt-14 pt-10 border-t border-slate-100">
                <h4 class="text-xs font-black uppercase tracking-wider text-slate-800 mb-4 text-center">
                    Popular Water Can Search Queries in Chennai & Tamil Nadu
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5 text-[11px]">
                    <a href="#check-pincode" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        1. 20 Litre Water Can Delivery Near Me
                    </a>
                    <a href="#services" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        2. Drinking Water Home Delivery
                    </a>
                    <a href="#check-pincode" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        3. Water Can Suppliers in Chennai
                    </a>
                    <a href="{{ route('customer.book') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        4. Order Water Can Online
                    </a>
                    <a href="#products" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        5. 20 Litre Water Can Price
                    </a>
                    <a href="#features" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        6. Pure Drinking Water Service
                    </a>
                    <a href="#how-it-works" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        7. Book Water Can Online
                    </a>
                    <a href="#services" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        8. Monthly Water Can Subscription
                    </a>
                    <a href="#corporate" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        9. Office Water Can Supply
                    </a>
                    <a href="#features" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        10. Same-Day Water Delivery
                    </a>
                    <a href="#features" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        11. 20 Litre Drinking Water Benefits
                    </a>
                    <a href="#how-it-works" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        12. Best Water Can Supplier
                    </a>
                    <a href="#services" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        13. Apartment Water Delivery
                    </a>
                    <a href="#corporate" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        14. Bulk Water Can Supply
                    </a>
                    <a href="#services" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        15. Doorstep Water Delivery
                    </a>
                    <a href="#check-pincode" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        16. Emergency Water Can Delivery
                    </a>
                    <a href="#products" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        17. Water Can COD Delivery
                    </a>
                    <a href="{{ route('customer.book') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        18. Water Can Booking App
                    </a>
                    <a href="#how-it-works" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        19. Scheduled Water Can Delivery
                    </a>
                    <a href="#check-pincode" class="p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-[#0077b6] font-semibold transition border border-slate-100">
                        20. Water Delivery Service Near Me
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Clean Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 pt-16 pb-12 text-slate-400 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand col -->
                <div class="space-y-4 md:col-span-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0077b6] to-[#00b4d8] p-0.5 flex items-center justify-center">
                            <img src="/icons/logo.svg" alt="Logo" class="w-full h-full object-contain p-1">
                        </div>
                        <span class="text-base font-black text-white tracking-tight">AquaFlow Water Logistics</span>
                    </div>
                    <p class="text-xs text-slate-400 max-w-sm leading-relaxed">
                        {{ $appSettings['business_tagline'] ?? 'Pure Hydration & Smart Doorstep Delivery' }}. High-purity potable drinking water logistics for homes, apartments, and corporate facilities.
                    </p>
                    <div class="text-[11px] text-slate-500">
                        <span>Operating Hours: 06:00 AM - 09:30 PM Everyday</span>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="space-y-3">
                    <h5 class="text-xs font-black uppercase tracking-wider text-white">Platform</h5>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Customer Sign In</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Open Account</a></li>
                        <li><a href="#products" class="hover:text-white transition">Products & Pricing</a></li>
                        <li><a href="#how-it-works" class="hover:text-white transition">How It Works</a></li>
                    </ul>
                </div>

                <!-- Security & Support -->
                <div class="space-y-3">
                    <h5 class="text-xs font-black uppercase tracking-wider text-white">Quality Standards</h5>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-shield-check text-[#00b4d8] text-[10px]"></i>
                            <span>BIS / FSSAI Certified</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-vial-circle-check text-[#00b4d8] text-[10px]"></i>
                            <span>Daily 10-Stage Lab Tested</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-lock text-[#00b4d8] text-[10px]"></i>
                            <span>256-bit Encrypted Payments</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
                <p>&copy; {{ date('Y') }} AquaFlow Water Logistics. All rights reserved.</p>
                <div class="flex items-center space-x-6">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                    <a href="{{ route('login') }}" class="hover:text-white transition">Staff Login</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive Pincode Checker Script -->
    <script>
        function toggleMobileNav() {
            const menu = document.getElementById('mobileNavMenu');
            const icon = document.getElementById('mobileMenuIcon');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
            } else {
                menu.classList.add('hidden');
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        }

        const serviceablePincodes = ['600040', '600001', '600002', '600004', '600018', '600028', '600096'];

        function checkServicePincode() {
            const input = document.getElementById('pincodeInput');
            const resultBox = document.getElementById('pincodeResult');
            const pincode = (input.value || '').trim();

            if (!pincode || pincode.length !== 6 || isNaN(pincode)) {
                resultBox.className = "mt-4 p-3.5 rounded-2xl text-xs font-medium border bg-amber-50 border-amber-200 text-amber-800 block";
                resultBox.innerHTML = "Please enter a valid 6-digit postal pincode.";
                return;
            }

            if (serviceablePincodes.includes(pincode)) {
                resultBox.className = "mt-4 p-3.5 rounded-2xl text-xs font-medium border bg-emerald-50 border-emerald-200 text-emerald-800 block";
                resultBox.innerHTML = `Great news! Express doorstep delivery is active in <strong>${pincode}</strong>. <a href="{{ route('customer.book') }}" class="underline font-bold text-emerald-950 ml-1">Order now &rarr;</a>`;
            } else {
                resultBox.className = "mt-4 p-3.5 rounded-2xl text-xs font-medium border bg-rose-50/90 border-rose-200/80 text-rose-700 block";
                resultBox.innerHTML = `We do not deliver to <span class="font-bold">${pincode}</span> yet. We are expanding to new Chennai sectors soon!`;
            }
        }
    </script>
</body>
</html>
