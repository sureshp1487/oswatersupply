<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration | Book 20 Litre Water Can Online - {{ $appSettings['business_name'] ?? 'AquaFlow' }}</title>
    <meta name="description" content="Register your customer account for doorstep 20 litre drinking water can delivery in Chennai. Easy online booking, flexible time slots, COD and monthly subscriptions.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/register') }}">
    <!-- Brand Favicon & Icons -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/icons/logo.svg">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Leaflet Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .step-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #registrationMap {
            min-height: 230px;
            z-index: 1;
        }
        .leaflet-container {
            border-radius: 1rem;
            font-family: inherit;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-10">

    <!-- Split Screen Card Container -->
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[670px]">

        <!-- Left Side: Brand Showcase & Registration Benefits (Desktop View Only - Hidden on Mobile) -->
        <div class="hidden lg:flex lg:col-span-5 bg-gradient-to-br from-sky-600 via-sky-700 to-cyan-800 text-white p-8 sm:p-10 flex-col justify-between relative overflow-hidden">
            <!-- Decorative Background Glow -->
            <div class="absolute -top-16 -right-16 w-56 h-56 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-cyan-400/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Brand Header -->
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-2xl border border-white/20">
                    <img src="/icons/logo.svg" alt="Logo" class="w-9 h-9 rounded-xl bg-white p-1 object-contain shadow-sm">
                    <div>
                        <h2 class="text-base font-extrabold tracking-tight leading-none text-white">{{ $appSettings['business_name'] ?? 'HydroPro Delivery' }}</h2>
                        <span class="text-[10px] text-cyan-200 uppercase tracking-widest font-semibold">Fast Onboarding</span>
                    </div>
                </div>

                <div class="pt-2">
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-snug">
                        Register in 2 Simple Steps.
                    </h1>
                    <p class="text-xs text-sky-100 mt-2 leading-relaxed">
                        Create your account and pinpoint your delivery location on the map. Full address details can easily be filled or changed later.
                    </p>
                </div>
            </div>

            <!-- Middle Feature List -->
            <div class="relative z-10 my-6 space-y-3.5">
                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-map-location-dot text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Interactive Map Pinpoint</h4>
                        <p class="text-[11px] text-sky-100">Drag pin to correct your exact doorstep location</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-repeat text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Fast 1-Click Ordering</h4>
                        <p class="text-[11px] text-sky-100">Repeat previous water can orders in seconds</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-wallet text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Flexible Payments</h4>
                        <p class="text-[11px] text-sky-100">Cash on delivery, Razorpay UPI, or monthly balance</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer Info -->
            <div class="relative z-10 pt-4 border-t border-white/15 flex items-center justify-between text-[11px] text-sky-200">
                <span class="flex items-center space-x-1.5 font-medium">
                    <i class="fa-solid fa-clock text-cyan-300"></i>
                    <span>Takes less than 1 minute</span>
                </span>
                <span class="text-white/80 font-bold">&copy; {{ date('Y') }} {{ $appSettings['business_name'] ?? 'HydroPro' }}</span>
            </div>
        </div>

        <!-- Right Side: Multi-Step Registration Form -->
        <div class="col-span-1 lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto space-y-5">
                <!-- Mobile Only Brand Header -->
                <div class="lg:hidden text-center space-y-1 mb-2">
                    <img src="/icons/logo.svg" alt="Logo" class="w-12 h-12 rounded-2xl mx-auto shadow-md p-1 bg-gradient-to-tr from-sky-500 to-cyan-600 object-contain">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ $appSettings['business_name'] ?? 'HydroPro Delivery' }}</h3>
                </div>

                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Create an Account</h2>
                    <p class="text-xs text-slate-500 mt-1">Get started with your water delivery service</p>
                </div>

                <!-- Stepper Progress Bar -->
                <div class="space-y-2 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span id="stepBadge1" class="text-sky-600 flex items-center space-x-1.5">
                            <span class="w-5 h-5 rounded-full bg-sky-600 text-white flex items-center justify-center text-[10px]">1</span>
                            <span>Account Details</span>
                        </span>
                        <span class="text-slate-300">&bull;&bull;&bull;</span>
                        <span id="stepBadge2" class="text-slate-400 flex items-center space-x-1.5">
                            <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px]">2</span>
                            <span>Pin Location</span>
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div id="stepProgressBar" class="bg-gradient-to-r from-sky-500 to-cyan-500 h-1.5 rounded-full w-1/2 transition-all duration-300"></div>
                    </div>
                </div>

                <!-- Step Inline Error Alert Box -->
                <div id="formAlertBox" class="hidden p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2.5 transition-all">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm shrink-0"></i>
                    <span id="formAlertText" class="font-medium"></span>
                </div>

                @if($errors->any())
                    <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm shrink-0"></i>
                        <span class="font-medium">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form id="onboardingForm" action="{{ route('register') }}" method="POST" class="space-y-4" novalidate>
                    @csrf

                    <!-- STEP 1: Account Information -->
                    <div id="step1Container" class="step-transition space-y-3.5">
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-sky-600 block">Step 1: Account Information</span>
                            <p class="text-[11px] text-slate-400">Enter your name and mobile number for deliveries</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500 font-black">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Ramesh Varma"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                            <p id="error-name" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Number <span class="text-rose-500 font-black">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="9876543210" maxlength="10"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                                <p id="error-phone" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Email ID <span class="text-rose-500 font-black">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="name@email.com"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                                <p id="error-email" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Password <span class="text-rose-500 font-black">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" placeholder="Min 6 characters"
                                           class="w-full pl-3.5 pr-8 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                                    <button type="button" class="toggle-password-field absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer" data-target="#password" title="Show/Hide Password" aria-label="Toggle password visibility">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </button>
                                </div>
                                <p id="error-password" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Confirm Password <span class="text-rose-500 font-black">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat password"
                                           class="w-full pl-3.5 pr-8 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                                    <button type="button" class="toggle-password-field absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer" data-target="#password_confirmation" title="Show/Hide Confirm Password" aria-label="Toggle confirm password visibility">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </button>
                                </div>
                                <p id="error-password_confirmation" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                            </div>
                        </div>

                        <!-- Step 1 Next Button -->
                        <button type="button" id="btnNextStep" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white font-extrabold text-xs shadow-lg shadow-sky-600/30 transition flex items-center justify-center space-x-2 active:scale-[0.99]">
                            <span>Continue to Step 2: Set Location on Map</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>

                        <!-- Google Sign-Up Option -->
                        <div class="relative flex py-1 items-center">
                            <div class="flex-grow border-t border-slate-200"></div>
                            <span class="flex-shrink mx-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Or register with</span>
                            <div class="flex-grow border-t border-slate-200"></div>
                        </div>

                        <a href="{{ route('auth.google') }}" class="w-full py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center justify-center space-x-2.5 shadow-sm active:scale-[0.99]">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Continue with Google</span>
                        </a>
                    </div>

                    <!-- STEP 2: Location Map Pinpoint (Simple steps: address filled later) -->
                    <div id="step2Container" class="step-transition space-y-3.5 hidden">
                        <div class="flex items-center justify-between border-b pb-2">
                            <div>
                                <span class="text-xs font-black uppercase tracking-wider text-sky-600 block">Step 2: Confirm Location on Map</span>
                                <p class="text-[11px] text-slate-400">Drag the pin or tap the map to correct location</p>
                            </div>
                            <button type="button" id="btnBackToStep1" class="text-xs text-slate-500 hover:text-slate-800 font-bold flex items-center space-x-1 py-1 px-2.5 rounded-lg hover:bg-slate-100 transition">
                                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                                <span>Back</span>
                            </button>
                        </div>

                        <!-- Fetch Current GPS Location Button -->
                        <button type="button" id="btnDetectLocation" class="w-full py-2.5 px-3 rounded-2xl bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-200 text-xs font-bold transition flex items-center justify-center space-x-2 active:scale-95 shadow-sm">
                            <i class="fa-solid fa-location-crosshairs text-sm text-sky-600 animate-pulse"></i>
                            <span id="detectLocationText">Auto-Detect My GPS Location</span>
                        </button>
                        <div id="gpsStatusMessage" class="text-[11px] text-center hidden"></div>

                        <!-- Interactive Leaflet Map Container -->
                        <div class="relative">
                            <div id="registrationMap" class="w-full h-56 rounded-2xl border border-slate-200 shadow-inner bg-slate-100"></div>
                            <div class="absolute bottom-2 left-2 right-2 z-[500] pointer-events-none">
                                <div class="bg-slate-900/80 backdrop-blur-sm text-white px-3 py-1.5 rounded-xl text-[10px] flex items-center justify-between shadow-md">
                                    <span class="flex items-center space-x-1.5">
                                        <i class="fa-solid fa-hand-pointer text-cyan-300"></i>
                                        <span>Tap map or drag pin to correct location</span>
                                    </span>
                                    <span id="coordsBadge" class="font-mono text-cyan-300 font-semibold">13.0827, 80.2707</span>
                                </div>
                            </div>
                        </div>

                        <!-- Detected Address Card Preview -->
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 flex items-center space-x-1.5">
                                    <i class="fa-solid fa-location-dot text-rose-500"></i>
                                    <span>Pinned Delivery Location</span>
                                </span>
                                <span id="reverseGeocodeBadge" class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold">Location Set</span>
                            </div>
                            <p id="detectedAddressPreview" class="text-xs font-bold text-slate-800 leading-snug">
                                Chennai, Tamil Nadu, India
                            </p>
                        </div>

                        <!-- Optional Door/Flat No. & Instructions -->
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Door / Flat No. <span class="text-slate-400 font-normal">(Optional)</span></label>
                                <input type="text" name="door_number" id="door_number" value="{{ old('door_number') }}" placeholder="e.g. Flat 204 / No. 12"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Delivery Instructions <span class="text-slate-400 font-normal">(Optional)</span></label>
                                <input type="text" name="delivery_instructions" id="delivery_instructions" value="{{ old('delivery_instructions') }}" placeholder="e.g. Leave at gate"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                            </div>
                        </div>

                        <!-- Friendly Address Note -->
                        <div class="p-2.5 rounded-xl bg-sky-50 border border-sky-100 text-[11px] text-sky-800 flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-sky-600 text-xs shrink-0"></i>
                            <span>You can review or change your address anytime later in your <strong>Profile</strong>.</span>
                        </div>

                        <!-- Hidden Address & Coordinates Fields -->
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', '13.0827') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', '80.2707') }}">
                        <input type="hidden" name="house_or_building" id="house_or_building" value="{{ old('house_or_building', 'My Pinned Location') }}">
                        <input type="hidden" name="street" id="street" value="{{ old('street', 'Near Pinned Location') }}">
                        <input type="hidden" name="area_locality" id="area_locality" value="{{ old('area_locality', 'Chennai Area') }}">
                        <input type="hidden" name="city" id="city" value="{{ old('city', 'Chennai') }}">
                        <input type="hidden" name="pincode" id="pincode" value="{{ old('pincode', '600001') }}">

                        <!-- Step 2 Final Submit -->
                        <button type="submit" id="btnSubmitForm" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white font-extrabold text-xs shadow-lg shadow-sky-600/30 transition flex items-center justify-center space-x-2 active:scale-[0.99]">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Complete Registration & Go to Home Page</span>
                        </button>
                    </div>

                </form>

                <div class="text-center pt-2 text-xs text-slate-600">
                    Already registered?
                    <a href="{{ route('login') }}" class="font-extrabold text-sky-600 hover:underline">Sign In</a>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Global Map Variables
        let regMap = null;
        let regMarker = null;
        let currentLat = parseFloat($('#latitude').val()) || 13.0827;
        let currentLng = parseFloat($('#longitude').val()) || 80.2707;

        function showFieldError(fieldId, message) {
            $('#' + fieldId).addClass('border-rose-400 ring-2 ring-rose-200');
            $('#error-' + fieldId).text(message).removeClass('hidden');
        }

        function clearFieldError(fieldId) {
            $('#' + fieldId).removeClass('border-rose-400 ring-2 ring-rose-200');
            $('#error-' + fieldId).addClass('hidden').text('');
        }

        function showFormAlert(message) {
            $('#formAlertText').text(message);
            $('#formAlertBox').removeClass('hidden');
        }

        function clearFormAlert() {
            $('#formAlertBox').addClass('hidden');
            $('#formAlertText').text('');
        }

        // Custom Leaflet Pin Icon
        function getCustomMapPin() {
            return L.divIcon({
                className: 'custom-reg-pin',
                html: `<div style="transform: translate(-50%, -100%);">
                         <div style="background: linear-gradient(135deg, #0284c7, #06b6d4); color: white; width: 34px; height: 34px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.35); border: 2.5px solid white;">
                             <i class="fa-solid fa-droplet" style="transform: rotate(45deg); font-size: 14px;"></i>
                         </div>
                       </div>`,
                iconSize: [34, 34],
                iconAnchor: [17, 34]
            });
        }

        // Initialize Map when Step 2 opens
        function initRegistrationMap() {
            if (regMap) {
                setTimeout(function() {
                    regMap.invalidateSize();
                }, 200);
                return;
            }

            regMap = L.map('registrationMap', {
                center: [currentLat, currentLng],
                zoom: 15,
                zoomControl: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(regMap);

            regMarker = L.marker([currentLat, currentLng], {
                draggable: true,
                icon: getCustomMapPin()
            }).addTo(regMap);

            // Dragend event on marker
            regMarker.on('dragend', function() {
                const position = regMarker.getLatLng();
                updateLocationFromCoords(position.lat, position.lng);
            });

            // Map click to reposition marker
            regMap.on('click', function(e) {
                regMarker.setLatLng(e.latlng);
                updateLocationFromCoords(e.latlng.lat, e.latlng.lng);
            });

            setTimeout(function() {
                regMap.invalidateSize();
            }, 250);

            // Trigger initial reverse geocoding
            updateLocationFromCoords(currentLat, currentLng);
        }

        // Reverse Geocode & Update Form Hidden Fields & UI
        function updateLocationFromCoords(lat, lng) {
            currentLat = lat;
            currentLng = lng;
            $('#latitude').val(lat.toFixed(7));
            $('#longitude').val(lng.toFixed(7));
            $('#coordsBadge').text(`${lat.toFixed(4)}, ${lng.toFixed(4)}`);

            $('#reverseGeocodeBadge').text('Resolving address...').removeClass('bg-emerald-100 text-emerald-800').addClass('bg-amber-100 text-amber-800');

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    $('#reverseGeocodeBadge').text('Location Set').removeClass('bg-amber-100 text-amber-800').addClass('bg-emerald-100 text-emerald-800');

                    if (data && data.address) {
                        const addr = data.address;
                        const road = addr.road || addr.street || addr.residential || addr.suburb || 'Near Pinned Location';
                        const locality = addr.suburb || addr.neighbourhood || addr.city_district || addr.locality || 'Chennai Area';
                        const cityVal = addr.city || addr.town || addr.state_district || 'Chennai';
                        const postcode = addr.postcode ? addr.postcode.replace(/\D/g, '').slice(0, 6) : '600001';

                        $('#street').val(road);
                        $('#area_locality').val(locality);
                        $('#city').val(cityVal);
                        $('#pincode').val(postcode);

                        const formatted = data.display_name ? data.display_name.split(',').slice(0, 4).join(', ') : `${road}, ${locality}, ${cityVal} - ${postcode}`;
                        $('#detectedAddressPreview').text(formatted);
                    } else {
                        $('#detectedAddressPreview').text(`Pinned Location (${lat.toFixed(4)}, ${lng.toFixed(4)})`);
                    }
                })
                .catch(() => {
                    $('#reverseGeocodeBadge').text('Location Pinned').removeClass('bg-amber-100 text-amber-800').addClass('bg-emerald-100 text-emerald-800');
                    $('#detectedAddressPreview').text(`Pinned Location (${lat.toFixed(4)}, ${lng.toFixed(4)})`);
                });
        }

        $(document).ready(function() {
            // Real-time input listener to clear errors
            $('input').on('input', function() {
                const id = $(this).attr('id');
                if (id) {
                    clearFieldError(id);
                }
                if ($('.border-rose-400').length === 0) {
                    clearFormAlert();
                }
            });

            // Step 1: Validate & Advance to Step 2
            $('#btnNextStep').on('click', function() {
                clearFormAlert();
                let hasError = false;
                let firstErrorElement = null;

                const name = $('#name').val().trim();
                const phone = $('#phone').val().trim();
                const email = $('#email').val().trim();
                const password = $('#password').val();
                const passwordConfirmation = $('#password_confirmation').val();

                if (!name) {
                    showFieldError('name', 'Please enter your full name.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#name');
                } else {
                    clearFieldError('name');
                }

                if (!phone) {
                    showFieldError('phone', 'Please enter your mobile number.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#phone');
                } else if (!/^[6-9]\d{9}$/.test(phone)) {
                    showFieldError('phone', 'Please enter a valid 10-digit mobile number starting with 6-9.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#phone');
                } else {
                    clearFieldError('phone');
                }

                if (!email) {
                    showFieldError('email', 'Please enter your email address.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#email');
                } else if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
                    showFieldError('email', 'Please enter a valid email address.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#email');
                } else {
                    clearFieldError('email');
                }

                if (!password) {
                    showFieldError('password', 'Password is required.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#password');
                } else if (password.length < 6) {
                    showFieldError('password', 'Password must be at least 6 characters.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#password');
                } else {
                    clearFieldError('password');
                }

                if (!passwordConfirmation) {
                    showFieldError('password_confirmation', 'Please confirm your password.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#password_confirmation');
                } else if (password !== passwordConfirmation) {
                    showFieldError('password_confirmation', 'Password confirmation does not match.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#password_confirmation');
                } else {
                    clearFieldError('password_confirmation');
                }

                if (hasError) {
                    showFormAlert('Please fix the errors highlighted below.');
                    if (firstErrorElement) {
                        firstErrorElement.focus();
                    }
                    return;
                }

                // Check server-side unique availability (Phone and Email) before advancing to Step 2
                const btn = $('#btnNextStep');
                const originalBtnHtml = btn.html();
                btn.attr('disabled', 'disabled').html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Checking Account Details...');

                $.ajax({
                    url: "{{ route('register.check_account') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        phone: phone,
                        email: email
                    },
                    success: function(response) {
                        btn.removeAttr('disabled').html(originalBtnHtml);
                        clearFormAlert();

                        // Switch to Step 2
                        $('#step1Container').addClass('hidden');
                        $('#step2Container').removeClass('hidden');

                        // Update Stepper Progress
                        $('#stepProgressBar').removeClass('w-1/2').addClass('w-full');
                        $('#stepBadge2').removeClass('text-slate-400').addClass('text-sky-600 font-bold');
                        $('#stepBadge2 span:first').removeClass('bg-slate-200 text-slate-600').addClass('bg-sky-600 text-white');

                        // Initialize Map
                        initRegistrationMap();
                    },
                    error: function(xhr) {
                        btn.removeAttr('disabled').html(originalBtnHtml);
                        const errors = xhr.responseJSON?.errors;
                        let firstField = null;

                        if (errors) {
                            if (errors.phone) {
                                showFieldError('phone', errors.phone[0]);
                                if (!firstField) firstField = $('#phone');
                            }
                            if (errors.email) {
                                showFieldError('email', errors.email[0]);
                                if (!firstField) firstField = $('#email');
                            }
                            showFormAlert(errors.phone ? errors.phone[0] : (errors.email ? errors.email[0] : 'Validation failed.'));
                        } else {
                            showFormAlert(xhr.responseJSON?.message || 'Unable to verify account details. Please try again.');
                        }

                        if (firstField) {
                            firstField.focus();
                        }
                    }
                });
            });

            // Back button click from Step 2 -> Step 1
            $('#btnBackToStep1').on('click', function() {
                clearFormAlert();
                $('#step2Container').addClass('hidden');
                $('#step1Container').removeClass('hidden');

                $('#stepProgressBar').removeClass('w-full').addClass('w-1/2');
                $('#stepBadge2').removeClass('text-sky-600 font-bold').addClass('text-slate-400');
                $('#stepBadge2 span:first').removeClass('bg-sky-600 text-white').addClass('bg-slate-200 text-slate-600');
            });

            // Geolocation Auto-Detection
            $('#btnDetectLocation').on('click', function() {
                const btnText = $('#detectLocationText');
                const statusMsg = $('#gpsStatusMessage');

                if (!navigator.geolocation) {
                    statusMsg.removeClass('hidden text-emerald-600 text-sky-600').addClass('text-rose-500 font-bold').text('Geolocation is not supported by your browser.');
                    return;
                }

                btnText.text('Detecting GPS location...');
                statusMsg.removeClass('hidden text-rose-500 text-emerald-600').addClass('text-sky-600 font-bold').text('Fetching your GPS coordinates...');

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        btnText.text('Auto-Detect My GPS Location');
                        statusMsg.removeClass('text-sky-600 text-rose-500').addClass('text-emerald-600 font-bold').text('GPS location detected! Adjust pin on the map if needed.');

                        if (regMap && regMarker) {
                            regMap.setView([lat, lon], 16);
                            regMarker.setLatLng([lat, lon]);
                        }
                        updateLocationFromCoords(lat, lon);
                    },
                    function(error) {
                        btnText.text('Auto-Detect My GPS Location');
                        let msg = 'Unable to detect GPS.';
                        if (error.code === error.PERMISSION_DENIED) {
                            msg = 'Location permission was denied. You can drag the pin on the map manually.';
                        }
                        statusMsg.removeClass('hidden text-emerald-600 text-sky-600').addClass('text-amber-600 font-bold').text(msg);
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            });

            // Form Submit Listener (Step 2 Submission)
            $('#onboardingForm').on('submit', function(e) {
                clearFormAlert();

                // Validate Step 1 fields again in case user tampered
                const name = $('#name').val().trim();
                const phone = $('#phone').val().trim();
                const email = $('#email').val().trim();
                const password = $('#password').val();
                const passwordConfirmation = $('#password_confirmation').val();

                if (!name || !phone || !email || !password || password !== passwordConfirmation) {
                    e.preventDefault();
                    $('#step2Container').addClass('hidden');
                    $('#step1Container').removeClass('hidden');
                    $('#stepProgressBar').removeClass('w-full').addClass('w-1/2');
                    $('#stepBadge2').removeClass('text-sky-600 font-bold').addClass('text-slate-400');
                    $('#stepBadge2 span:first').removeClass('bg-sky-600 text-white').addClass('bg-slate-200 text-slate-600');
                    showFormAlert('Please complete all required fields in Step 1.');
                    return;
                }

                // Ensure lat / lng are present
                if (!$('#latitude').val() || !$('#longitude').val()) {
                    $('#latitude').val('13.0827');
                    $('#longitude').val('80.2707');
                }

                const doorVal = $('#door_number').val().trim();
                if (doorVal) {
                    $('#house_or_building').val(doorVal);
                }

                // Show spinner on submit button
                $('#btnSubmitForm').attr('disabled', 'disabled').html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Creating Account & Loading Home...');
            });

            // Toggle Password Visibility
            $(document).on('click', '.toggle-password-field', function() {
                const targetSelector = $(this).data('target');
                const targetInput = $(targetSelector);
                const icon = $(this).find('i');
                if (targetInput.attr('type') === 'password') {
                    targetInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    targetInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
</body>
</html>
