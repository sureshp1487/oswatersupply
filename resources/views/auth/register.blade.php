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
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .step-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
                        <span class="text-[10px] text-cyan-200 uppercase tracking-widest font-semibold">Customer Onboarding</span>
                    </div>
                </div>

                <div class="pt-2">
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-snug">
                        Join in 2 Simple Steps.
                    </h1>
                    <p class="text-xs text-sky-100 mt-2 leading-relaxed">
                        Create your account and save your doorstep address for fast 1-click water can orders and scheduled deliveries.
                    </p>
                </div>
            </div>

            <!-- Middle Feature List -->
            <div class="relative z-10 my-6 space-y-3.5">
                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-location-crosshairs text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Smart GPS Pinpointing</h4>
                        <p class="text-[11px] text-sky-100">Auto-fill street, locality, and pincode with one click</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-repeat text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Effortless Can Reorders</h4>
                        <p class="text-[11px] text-sky-100">Repeat your previous orders instantly anytime</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-wallet text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Flexible Payment Options</h4>
                        <p class="text-[11px] text-sky-100">Pay on delivery or maintain a customer credit balance</p>
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
                            <span>Delivery Address</span>
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div id="stepProgressBar" class="bg-gradient-to-r from-sky-500 to-cyan-500 h-1.5 rounded-full w-1/2 transition-all duration-300"></div>
                    </div>
                </div>

                <!-- Step Inline Error Alert Box (No popups) -->
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
                            <span>Continue to Step 2: Delivery Address</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                    <!-- STEP 2: Delivery Address -->
                    <div id="step2Container" class="step-transition space-y-3.5 hidden">
                        <div class="flex items-center justify-between border-b pb-2">
                            <div>
                                <span class="text-xs font-black uppercase tracking-wider text-sky-600 block">Step 2: Delivery Address</span>
                                <p class="text-[11px] text-slate-400">Where should we deliver your water cans?</p>
                            </div>
                            <button type="button" id="btnBackToStep1" class="text-xs text-slate-400 hover:text-slate-700 font-bold flex items-center space-x-1 py-1 px-2 rounded-lg hover:bg-slate-100 transition">
                                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                                <span>Back</span>
                            </button>
                        </div>

                        <!-- Fetch Current Location GPS Button -->
                        <button type="button" id="btnDetectLocation" class="w-full py-2.5 px-3 rounded-2xl bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-200 text-xs font-bold transition flex items-center justify-center space-x-2 active:scale-95 shadow-sm">
                            <i class="fa-solid fa-location-crosshairs text-sm text-sky-600 animate-pulse"></i>
                            <span id="detectLocationText">Use Current GPS Location (Auto-fill)</span>
                        </button>
                        <div id="gpsStatusMessage" class="text-[11px] text-center hidden"></div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Door / Flat No. <span class="text-rose-500 font-black">*</span></label>
                                <input type="text" name="door_number" id="door_number" value="{{ old('door_number') }}" placeholder="Flat 204"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                                <p id="error-door_number" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Building / House <span class="text-rose-500 font-black">*</span></label>
                                <input type="text" name="house_or_building" id="house_or_building" value="{{ old('house_or_building') }}" placeholder="Green Meadows"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                                <p id="error-house_or_building" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Street / Road Name <span class="text-rose-500 font-black">*</span></label>
                            <input type="text" name="street" id="street" value="{{ old('street') }}" placeholder="4th Cross Avenue"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                            <p id="error-street" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Area / Locality <span class="text-rose-500 font-black">*</span></label>
                                <input type="text" name="area_locality" id="area_locality" value="{{ old('area_locality') }}" placeholder="Anna Nagar"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                                <p id="error-area_locality" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Pincode <span class="text-rose-500 font-black">*</span></label>
                                <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}" placeholder="600040" maxlength="6"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                                <p id="error-pincode" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">City <span class="text-rose-500 font-black">*</span></label>
                            <input type="text" name="city" id="city" value="{{ old('city', 'Chennai') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                            <p id="error-city" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Delivery Instructions <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <input type="text" name="delivery_instructions" id="delivery_instructions" value="{{ old('delivery_instructions') }}" placeholder="e.g. Ring bell, leave outside if not home"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                        </div>

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
        $(document).ready(function() {
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

            // Validate Step 1 and proceed to Step 2
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
                } else if (phone.length < 10 || !/^\d{10}$/.test(phone)) {
                    showFieldError('phone', 'Please enter a valid 10-digit mobile number.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#phone');
                } else {
                    clearFieldError('phone');
                }

                if (!email) {
                    showFieldError('email', 'Please enter your email address.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#email');
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
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

                // Switch to Step 2
                $('#step1Container').addClass('hidden');
                $('#step2Container').removeClass('hidden');

                // Update Stepper Progress
                $('#stepProgressBar').removeClass('w-1/2').addClass('w-full');
                $('#stepBadge2').removeClass('text-slate-400').addClass('text-sky-600 font-bold');
                $('#stepBadge2 span:first').removeClass('bg-slate-200 text-slate-600').addClass('bg-sky-600 text-white');
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

            // Validate Step 2 on Form Submission
            $('#onboardingForm').on('submit', function(e) {
                clearFormAlert();
                let hasError = false;
                let firstErrorElement = null;

                const door = $('#door_number').val().trim();
                const building = $('#house_or_building').val().trim();
                const street = $('#street').val().trim();
                const area = $('#area_locality').val().trim();
                const pincode = $('#pincode').val().trim();
                const city = $('#city').val().trim();

                if (!door) {
                    showFieldError('door_number', 'Door / Flat No. is required.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#door_number');
                }
                if (!building) {
                    showFieldError('house_or_building', 'Building / House name is required.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#house_or_building');
                }
                if (!street) {
                    showFieldError('street', 'Street / Road name is required.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#street');
                }
                if (!area) {
                    showFieldError('area_locality', 'Area / Locality is required.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#area_locality');
                }
                if (!pincode) {
                    showFieldError('pincode', 'Pincode is required.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#pincode');
                } else if (pincode.length < 6 || !/^\d{6}$/.test(pincode)) {
                    showFieldError('pincode', 'Please enter a valid 6-digit pincode.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#pincode');
                }
                if (!city) {
                    showFieldError('city', 'City is required.');
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = $('#city');
                }

                if (hasError) {
                    e.preventDefault();
                    showFormAlert('Please fill in all mandatory address details.');
                    if (firstErrorElement) {
                        firstErrorElement.focus();
                    }
                }
            });

            // Geolocation Auto-Detection
            $('#btnDetectLocation').on('click', function() {
                const btnText = $('#detectLocationText');
                const statusMsg = $('#gpsStatusMessage');

                if (!navigator.geolocation) {
                    statusMsg.removeClass('hidden text-emerald-600').addClass('text-rose-500').text('Geolocation is not supported by your browser.');
                    return;
                }

                btnText.text('Detecting your location...');
                statusMsg.removeClass('hidden text-rose-500 text-emerald-600').addClass('text-sky-600').text('Getting GPS coordinates and fetching address details...');

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;

                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&addressdetails=1`)
                            .then(response => response.json())
                            .then(data => {
                                btnText.text('Use Current GPS Location (Auto-fill)');

                                if (data && data.address) {
                                    const addr = data.address;
                                    const road = addr.road || addr.street || addr.residential || addr.suburb || '';
                                    const locality = addr.suburb || addr.neighbourhood || addr.city_district || addr.locality || '';
                                    const cityVal = addr.city || addr.town || addr.state_district || 'Chennai';
                                    const postcode = addr.postcode ? addr.postcode.replace(/\D/g, '').slice(0, 6) : '';

                                    if (road) {
                                        $('#street').val(road);
                                        clearFieldError('street');
                                    }
                                    if (locality) {
                                        $('#area_locality').val(locality);
                                        clearFieldError('area_locality');
                                    }
                                    if (cityVal) {
                                        $('#city').val(cityVal);
                                        clearFieldError('city');
                                    }
                                    if (postcode) {
                                        $('#pincode').val(postcode);
                                        clearFieldError('pincode');
                                    }

                                    statusMsg.removeClass('text-sky-600 text-rose-500').addClass('text-emerald-600 font-bold').text('Location detected and address fields filled!');
                                } else {
                                    statusMsg.removeClass('text-sky-600 text-emerald-600').addClass('text-amber-600').text('Coordinates retrieved. Please verify street and door number.');
                                }
                            })
                            .catch(err => {
                                btnText.text('Use Current GPS Location (Auto-fill)');
                                statusMsg.removeClass('text-sky-600 text-emerald-600').addClass('text-amber-600').text('GPS detected. Please type remaining address details.');
                            });
                    },
                    function(error) {
                        btnText.text('Use Current GPS Location (Auto-fill)');
                        let msg = 'Unable to retrieve location.';
                        if (error.code === error.PERMISSION_DENIED) {
                            msg = 'Location permission was denied. Please fill in your address manually.';
                        }
                        statusMsg.removeClass('hidden text-emerald-600 text-sky-600').addClass('text-rose-500 font-bold').text(msg);
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            });

            // Toggle Password Visibility for Password & Confirm Password
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
