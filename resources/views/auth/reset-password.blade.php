<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password | {{ $appSettings['business_name'] ?? 'AquaFlow' }}</title>
    <meta name="robots" content="noindex, nofollow">
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
        
        <!-- Left Side: Brand Showcase -->
        <div class="hidden lg:flex lg:col-span-5 bg-gradient-to-br from-sky-600 via-sky-700 to-cyan-800 text-white p-8 sm:p-10 flex-col justify-between relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-56 h-56 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-cyan-400/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Brand Header -->
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-2xl border border-white/20">
                    <img src="/icons/logo.svg" alt="Logo" class="w-9 h-9 rounded-xl bg-white p-1 object-contain shadow-sm">
                    <div>
                        <h2 class="text-base font-extrabold tracking-tight leading-none text-white">{{ $appSettings['business_name'] ?? 'HydroPro Delivery' }}</h2>
                        <span class="text-[10px] text-cyan-200 uppercase tracking-widest font-semibold">New Password</span>
                    </div>
                </div>

                <div class="pt-2">
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-snug">
                        Create Your <br>New Password
                    </h1>
                    <p class="text-xs text-sky-100 mt-2 leading-relaxed">
                        Choose a strong, unique password with at least 6 characters to keep your account safe and secure.
                    </p>
                </div>
            </div>

            <!-- Middle Feature Highlights -->
            <div class="relative z-10 my-8 space-y-3.5">
                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-key text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Minimum 6 Characters</h4>
                        <p class="text-[11px] text-sky-100">Use a blend of letters, numbers & symbols</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-shield-halved text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Instant Activation</h4>
                        <p class="text-[11px] text-sky-100">Sign in immediately after resetting</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Testimonial / Trust Footer -->
            <div class="relative z-10 pt-4 border-t border-white/15 flex items-center justify-between text-[11px] text-sky-200">
                <span class="flex items-center space-x-1.5 font-medium">
                    <i class="fa-solid fa-lock text-cyan-300"></i>
                    <span>Secure Access Portal</span>
                </span>
                <span class="text-white/80 font-bold">&copy; {{ date('Y') }} {{ $appSettings['business_name'] ?? 'HydroPro' }}</span>
            </div>
        </div>

        <!-- Right Side: Reset Form -->
        <div class="col-span-1 lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto space-y-6">

                <!-- Mobile Only Brand Header -->
                <div class="lg:hidden text-center space-y-1 mb-2">
                    <img src="/icons/logo.svg" alt="Logo" class="w-12 h-12 rounded-2xl mx-auto shadow-md p-1 bg-gradient-to-tr from-sky-500 to-cyan-600 object-contain">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ $appSettings['business_name'] ?? 'HydroPro Delivery' }}</h3>
                </div>

                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Set New Password</h2>
                    <p class="text-xs text-slate-500 mt-1">Please enter and confirm your new password below.</p>
                </div>

                @if($errors->any())
                    <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation text-sm shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email', $email) }}" required
                                   class="w-full pl-9 pr-4 py-3 rounded-2xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>
                    </div>

                    <!-- New Password Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input type="password" name="password" id="newPasswordInput" required placeholder="At least 6 characters"
                                   class="w-full pl-9 pr-10 py-3 rounded-2xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                            <button type="button" onclick="toggleVisibility('newPasswordInput', 'newPasswordIcon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                <i class="fa-regular fa-eye text-sm" id="newPasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm New Password Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input type="password" name="password_confirmation" id="confirmPasswordInput" required placeholder="Re-enter your password"
                                   class="w-full pl-9 pr-10 py-3 rounded-2xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                            <button type="button" onclick="toggleVisibility('confirmPasswordInput', 'confirmPasswordIcon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                <i class="fa-regular fa-eye text-sm" id="confirmPasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white font-extrabold text-xs shadow-lg shadow-sky-600/30 active:scale-[0.99] transition flex items-center justify-center space-x-2">
                        <span>Save & Reset Password</span>
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    </button>
                </form>

                <div class="text-center pt-2 text-xs text-slate-600">
                    <a href="{{ route('login') }}" class="font-extrabold text-sky-600 hover:underline inline-flex items-center space-x-1.5">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Cancel and return to Sign In</span>
                    </a>
                </div>

            </div>
        </div>

    </div>

    <script>
        function toggleVisibility(inputId, iconId) {
            const input = $('#' + inputId);
            const icon = $('#' + iconId);
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        }
    </script>

</body>
</html>
