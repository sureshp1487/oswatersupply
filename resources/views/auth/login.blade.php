<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer & Staff Sign In | 20 Litre Water Can Delivery - {{ $appSettings['business_name'] ?? 'AquaFlow' }}</title>
    <meta name="description" content="Sign in to your {{ $appSettings['business_name'] ?? 'AquaFlow' }} account to order 20 litre drinking water cans, schedule delivery, manage empty cans and view invoices in Chennai.">
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="{{ url('/login') }}">
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
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-10">

    <!-- Split Screen Card Container -->
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
        
        <!-- Left Side: Brand Showcase & Content (Desktop View Only - Hidden on Mobile) -->
        <div class="hidden lg:flex lg:col-span-5 bg-gradient-to-br from-sky-600 via-sky-700 to-cyan-800 text-white p-8 sm:p-10 flex-col justify-between relative overflow-hidden">
            <!-- Decorative Background Water Rings / Blur -->
            <div class="absolute -top-16 -right-16 w-56 h-56 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-cyan-400/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Brand Header -->
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-2xl border border-white/20">
                    <img src="/icons/logo.svg" alt="Logo" class="w-9 h-9 rounded-xl bg-white p-1 object-contain shadow-sm">
                    <div>
                        <h2 class="text-base font-extrabold tracking-tight leading-none text-white">{{ $appSettings['business_name'] ?? 'HydroPro Delivery' }}</h2>
                        <span class="text-[10px] text-cyan-200 uppercase tracking-widest font-semibold">Doorstep Service</span>
                    </div>
                </div>

                <div class="pt-2">
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-snug">
                        Pure Hydration, <br>Delivered Daily.
                    </h1>
                    <p class="text-xs text-sky-100 mt-2 leading-relaxed">
                        {{ $appSettings['business_tagline'] ?? 'Pure Hydration & Smart Doorstep Delivery' }}. Sign in to order water cans, manage refills, and track deliveries in real time.
                    </p>
                </div>
            </div>

            <!-- Middle Feature Highlights -->
            <div class="relative z-10 my-8 space-y-3.5">
                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-truck-fast text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Express Doorstep Delivery</h4>
                        <p class="text-[11px] text-sky-100">Quick turnaround straight to your home or office</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-bottle-water text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">100% Quality Inspected</h4>
                        <p class="text-[11px] text-sky-100">Multi-stage purification & sealed sanitized cans</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-file-invoice text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Instant Invoicing & Ledger</h4>
                        <p class="text-[11px] text-sky-100">Live order history, dues balance, and digital bills</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Testimonial / Trust Footer -->
            <div class="relative z-10 pt-4 border-t border-white/15 flex items-center justify-between text-[11px] text-sky-200">
                <span class="flex items-center space-x-1.5 font-medium">
                    <i class="fa-solid fa-shield-halved text-cyan-300"></i>
                    <span>Secure Access Portal</span>
                </span>
                <span class="text-white/80 font-bold">&copy; {{ date('Y') }} {{ $appSettings['business_name'] ?? 'HydroPro' }}</span>
            </div>
        </div>

        <!-- Right Side: Sign In Form -->
        <div class="col-span-1 lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto space-y-6">
                <!-- Mobile Only Brand Header -->
                <div class="lg:hidden text-center space-y-1 mb-2">
                    <img src="/icons/logo.svg" alt="Logo" class="w-12 h-12 rounded-2xl mx-auto shadow-md p-1 bg-gradient-to-tr from-sky-500 to-cyan-600 object-contain">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ $appSettings['business_name'] ?? 'HydroPro Delivery' }}</h3>
                </div>

                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Welcome Back</h2>
                    <p class="text-xs text-slate-500 mt-1">Please enter your credentials to access your portal</p>
                </div>

                <!-- Client-Side Form Inline Alert Box -->
                <div id="loginFormAlert" class="hidden p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
                    <i class="fa-solid fa-circle-exclamation text-sm shrink-0"></i>
                    <span id="loginFormAlertText"></span>
                </div>

                @if($errors->any())
                    <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation text-sm shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="p-3.5 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-xs flex items-center space-x-2">
                        <i class="fa-solid fa-circle-info text-sm shrink-0"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                <form id="signInForm" action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mobile Number or Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user text-xs"></i>
                            </div>
                            <input type="text" name="login" id="loginInput" value="{{ old('login', '9876543212') }}" required placeholder="9876543212 or email@domain.com"
                                   class="w-full pl-9 pr-4 py-3 rounded-2xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>
                        <p id="error-loginInput" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input type="password" name="password" id="passwordInput" value="customer123" required placeholder="Enter password"
                                   class="w-full pl-9 pr-4 py-3 rounded-2xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>
                        <p id="error-passwordInput" class="text-[11px] text-rose-600 font-semibold mt-1 hidden"></p>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center space-x-2 text-slate-600 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-sky-600 focus:ring-sky-500 border-slate-300">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="text-sky-600 font-bold hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white font-extrabold text-xs shadow-lg shadow-sky-600/30 active:scale-[0.99] transition flex items-center justify-center space-x-2">
                        <span>Sign In</span>
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    </button>
                </form>

                <!-- Quick Demo Credentials Box -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-[11px] text-slate-600 space-y-2">
                    <span class="font-bold text-slate-800 block uppercase tracking-wider text-[10px] flex items-center justify-between">
                        <span>Quick Demo Logins</span>
                        <span class="text-sky-600 font-semibold text-[10px]">Click to auto-fill</span>
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pt-1">
                        <button type="button" onclick="fillCredentials('9876543212', 'customer123')" class="p-2 rounded-xl bg-white border border-slate-200/80 hover:border-sky-400 text-left transition">
                            <span class="block font-bold text-sky-600 text-[10px] uppercase">Customer</span>
                            <div class="font-mono text-[10px] text-slate-700">9876543212</div>
                            <div class="text-[10px] text-slate-400">customer123</div>
                        </button>
                        <button type="button" onclick="fillCredentials('admin@aquaflow.com', 'admin123')" class="p-2 rounded-xl bg-white border border-slate-200/80 hover:border-indigo-400 text-left transition">
                            <span class="block font-bold text-indigo-600 text-[10px] uppercase">Admin</span>
                            <div class="font-mono text-[10px] text-slate-700 truncate" title="admin@aquaflow.com">admin@aquaflow.com</div>
                            <div class="text-[10px] text-slate-400">admin123</div>
                        </button>
                        <button type="button" onclick="fillCredentials('9876543211', 'driver123')" class="p-2 rounded-xl bg-white border border-slate-200/80 hover:border-cyan-400 text-left transition">
                            <span class="block font-bold text-cyan-600 text-[10px] uppercase">Driver</span>
                            <div class="font-mono text-[10px] text-slate-700">9876543211</div>
                            <div class="text-[10px] text-slate-400">driver123</div>
                        </button>
                    </div>
                </div>

                <div class="text-center pt-2 text-xs text-slate-600">
                    New customer?
                    <a href="{{ route('register') }}" class="font-extrabold text-sky-600 hover:underline">Create an account</a>
                </div>
            </div>
        </div>

    </div>

    <script>
        function fillCredentials(login, pass) {
            $('#loginInput').val(login);
            $('#passwordInput').val(pass);
            $('#loginFormAlert').addClass('hidden');
            $('#loginInput, #passwordInput').removeClass('border-rose-400 ring-2 ring-rose-200');
            $('#error-loginInput, #error-passwordInput').addClass('hidden');
        }

        $(document).ready(function() {
            $('#signInForm').on('submit', function(e) {
                const loginVal = $('#loginInput').val().trim();
                const passVal = $('#passwordInput').val().trim();
                let hasError = false;

                $('#loginFormAlert').addClass('hidden');
                $('#loginInput, #passwordInput').removeClass('border-rose-400 ring-2 ring-rose-200');
                $('#error-loginInput, #error-passwordInput').addClass('hidden');

                if (!loginVal) {
                    $('#loginInput').addClass('border-rose-400 ring-2 ring-rose-200');
                    $('#error-loginInput').text('Please enter your mobile number or email.').removeClass('hidden');
                    hasError = true;
                }

                if (!passVal) {
                    $('#passwordInput').addClass('border-rose-400 ring-2 ring-rose-200');
                    $('#error-passwordInput').text('Please enter your password.').removeClass('hidden');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    $('#loginFormAlertText').text('Please fill in all required credentials.');
                    $('#loginFormAlert').removeClass('hidden');
                }
            });

            $('#loginInput, #passwordInput').on('input', function() {
                $(this).removeClass('border-rose-400 ring-2 ring-rose-200');
                const fieldId = $(this).attr('id');
                $('#error-' + fieldId).addClass('hidden');
                if (!$('#loginInput').hasClass('border-rose-400') && !$('#passwordInput').hasClass('border-rose-400')) {
                    $('#loginFormAlert').addClass('hidden');
                }
            });
        });
    </script>

</body>
</html>
