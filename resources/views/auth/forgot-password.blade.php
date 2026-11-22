<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | {{ $appSettings['business_name'] ?? 'AquaFlow' }}</title>
    <meta name="description" content="Reset your password for {{ $appSettings['business_name'] ?? 'AquaFlow' }} account.">
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="{{ url('/forgot-password') }}">
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
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[600px]">
        
        <!-- Left Side: Brand Showcase (Desktop View Only) -->
        <div class="hidden lg:flex lg:col-span-5 bg-gradient-to-br from-sky-600 via-sky-700 to-cyan-800 text-white p-8 sm:p-10 flex-col justify-between relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-56 h-56 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-cyan-400/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Brand Header -->
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-2xl border border-white/20">
                    <img src="/icons/logo.svg" alt="Logo" class="w-9 h-9 rounded-xl bg-white p-1 object-contain shadow-sm">
                    <div>
                        <h2 class="text-base font-extrabold tracking-tight leading-none text-white">{{ $appSettings['business_name'] ?? 'HydroPro Delivery' }}</h2>
                        <span class="text-[10px] text-cyan-200 uppercase tracking-widest font-semibold">Account Recovery</span>
                    </div>
                </div>

                <div class="pt-2">
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-snug">
                        Secure Account <br>Recovery
                    </h1>
                    <p class="text-xs text-sky-100 mt-2 leading-relaxed">
                        Forgot your login credentials? No worries. We'll help you securely reset your password and get back to managing your daily hydration.
                    </p>
                </div>
            </div>

            <!-- Middle Highlights -->
            <div class="relative z-10 my-8 space-y-3.5">
                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-envelope-circle-check text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Instant Email Dispatch</h4>
                        <p class="text-[11px] text-sky-100">Secure 60-minute time-sensitive reset link</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-shield-halved text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Encrypted & Safe</h4>
                        <p class="text-[11px] text-sky-100">Tokenized security protects your personal data</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="relative z-10 pt-4 border-t border-white/15 flex items-center justify-between text-[11px] text-sky-200">
                <span class="flex items-center space-x-1.5 font-medium">
                    <i class="fa-solid fa-lock text-cyan-300"></i>
                    <span>SSL Protected</span>
                </span>
                <span class="text-white/80 font-bold">&copy; {{ date('Y') }} {{ $appSettings['business_name'] ?? 'HydroPro' }}</span>
            </div>
        </div>

        <!-- Right Side: Request Form -->
        <div class="col-span-1 lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto space-y-6">
                
                <!-- Mobile Brand Header -->
                <div class="lg:hidden text-center space-y-1 mb-2">
                    <img src="/icons/logo.svg" alt="Logo" class="w-12 h-12 rounded-2xl mx-auto shadow-md p-1 bg-gradient-to-tr from-sky-500 to-cyan-600 object-contain">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ $appSettings['business_name'] ?? 'HydroPro Delivery' }}</h3>
                </div>

                <div>
                    <a href="{{ route('login') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-sky-600 hover:text-sky-700 transition mb-3">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Back to Sign In</span>
                    </a>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Forgot Password?</h2>
                    <p class="text-xs text-slate-500 mt-1">Enter your registered email address or mobile number. We'll send you a password reset link.</p>
                </div>

                <!-- Status / Success Alert -->
                @if(session('status'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-start space-x-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm shrink-0 mt-0.5"></i>
                        <span class="leading-relaxed">{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Error Alert -->
                @if($errors->any())
                    <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation text-sm shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email ID or Mobile Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </div>
                            <input type="text" name="login" value="{{ old('login') }}" required autofocus
                                   placeholder="name@domain.com or 9876543210"
                                   class="w-full pl-9 pr-4 py-3 rounded-2xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white font-extrabold text-xs shadow-lg shadow-sky-600/30 active:scale-[0.99] transition flex items-center justify-center space-x-2">
                        <span>Send Password Reset Link</span>
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                    </button>
                </form>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                    <span>Remember your password?</span>
                    <a href="{{ route('login') }}" class="font-extrabold text-sky-600 hover:underline">Sign In</a>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
