@extends('layouts.admin')

@section('page_title', 'App & Dashboard Customization')

@section('content')
<div class="max-w-5xl space-y-6">

    <!-- Header Description Card -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-indigo-700/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-indigo-500/30 text-indigo-200 text-xs font-bold mb-2 border border-indigo-400/30">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>White-Label & Theme Studio</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black tracking-tight">Brand & UI Customization</h2>
            <p class="text-xs sm:text-sm text-indigo-200 mt-1">Configure brand identity, color palette, custom logo, favicon, and announcement banners in real-time.</p>
        </div>
        <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/10 self-start md:self-auto">
            <img src="/icons/logo.svg" alt="Current Logo" class="w-10 h-10 rounded-xl shadow-md bg-indigo-600/60 p-1 object-contain">
            <div>
                <span class="text-[10px] text-indigo-300 font-bold uppercase block">Active Theme</span>
                <span class="text-xs font-extrabold text-white capitalize">{{ $settings['dashboard_theme'] ?? 'indigo' }} (Royal Indigo)</span>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.customization.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. Brand Identity -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center space-x-3 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-signature"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Brand Identity & Naming</h3>
                    <p class="text-[11px] text-slate-500">Displayed on the Customer App, Delivery Captains portal, and Admin Dashboard</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Brand / Business Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="business_name" value="{{ old('business_name', $settings['business_name'] ?? 'HydroPro Water Delivery') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Brand Tagline</label>
                    <input type="text" name="business_tagline" value="{{ old('business_tagline', $settings['business_tagline'] ?? 'Pure Hydration & Smart Doorstep Delivery') }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <!-- 2. Logo & Favicon Assets -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center space-x-3 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-image"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Brand Logo & Favicon</h3>
                    <p class="text-[11px] text-slate-500">Upload high-resolution brand icons or SVG logos</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <!-- Logo -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 p-2 flex items-center justify-center shadow-sm flex-shrink-0">
                        <img src="/icons/logo.svg" alt="App Logo" class="w-12 h-12 object-contain">
                    </div>
                    <div class="flex-1 space-y-1">
                        <label class="block font-bold text-slate-800">Upload New App Logo</label>
                        <p class="text-[10px] text-slate-500">SVG, PNG or JPG (Max 2MB)</p>
                        <input type="file" name="logo" accept=".svg,.png,.jpg,.jpeg" class="block w-full text-[11px] text-slate-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>

                <!-- Favicon -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 p-2 flex items-center justify-center shadow-sm flex-shrink-0">
                        <img src="/favicon.ico" alt="Favicon" class="w-8 h-8 object-contain">
                    </div>
                    <div class="flex-1 space-y-1">
                        <label class="block font-bold text-slate-800">Upload New Browser Favicon</label>
                        <p class="text-[10px] text-slate-500">ICO or PNG (Max 1MB)</p>
                        <input type="file" name="favicon" accept=".ico,.png" class="block w-full text-[11px] text-slate-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Dashboard Color Theme & Palette -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center space-x-3 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Theme Color & UI Styling</h3>
                    <p class="text-[11px] text-slate-500">Pick a pre-designed theme or define custom hex accent colors</p>
                </div>
            </div>

            <!-- Theme Presets Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2.5">Theme Presets</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <!-- Royal Indigo (Active Recommended) -->
                    <label class="relative flex flex-col p-3.5 border-2 rounded-2xl cursor-pointer hover:border-indigo-500 transition {{ ($settings['dashboard_theme'] ?? 'indigo') === 'indigo' ? 'border-indigo-600 bg-indigo-50/40 ring-2 ring-indigo-500/20' : 'border-slate-200' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900">Royal Indigo</span>
                            <input type="radio" name="dashboard_theme" value="indigo" {{ ($settings['dashboard_theme'] ?? 'indigo') === 'indigo' ? 'checked' : '' }} onchange="selectPresetTheme('#4f46e5', '#3b82f6')" class="text-indigo-600 focus:ring-indigo-500">
                        </div>
                        <div class="flex space-x-1.5 mt-auto">
                            <span class="w-5 h-5 rounded-full bg-indigo-600 shadow-sm"></span>
                            <span class="w-5 h-5 rounded-full bg-blue-500 shadow-sm"></span>
                            <span class="w-5 h-5 rounded-full bg-slate-900 shadow-sm"></span>
                        </div>
                    </label>

                    <!-- Ocean Blue -->
                    <label class="relative flex flex-col p-3.5 border-2 rounded-2xl cursor-pointer hover:border-sky-500 transition {{ ($settings['dashboard_theme'] ?? '') === 'ocean' ? 'border-sky-600 bg-sky-50/40 ring-2 ring-sky-500/20' : 'border-slate-200' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900">Ocean Blue</span>
                            <input type="radio" name="dashboard_theme" value="ocean" {{ ($settings['dashboard_theme'] ?? '') === 'ocean' ? 'checked' : '' }} onchange="selectPresetTheme('#0284c7', '#0ea5e9')" class="text-sky-600 focus:ring-sky-500">
                        </div>
                        <div class="flex space-x-1.5 mt-auto">
                            <span class="w-5 h-5 rounded-full bg-sky-600 shadow-sm"></span>
                            <span class="w-5 h-5 rounded-full bg-cyan-500 shadow-sm"></span>
                            <span class="w-5 h-5 rounded-full bg-slate-900 shadow-sm"></span>
                        </div>
                    </label>

                    <!-- Emerald Teal -->
                    <label class="relative flex flex-col p-3.5 border-2 rounded-2xl cursor-pointer hover:border-emerald-500 transition {{ ($settings['dashboard_theme'] ?? '') === 'emerald' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900">Emerald Teal</span>
                            <input type="radio" name="dashboard_theme" value="emerald" {{ ($settings['dashboard_theme'] ?? '') === 'emerald' ? 'checked' : '' }} onchange="selectPresetTheme('#059669', '#10b981')" class="text-emerald-600 focus:ring-emerald-500">
                        </div>
                        <div class="flex space-x-1.5 mt-auto">
                            <span class="w-5 h-5 rounded-full bg-emerald-600 shadow-sm"></span>
                            <span class="w-5 h-5 rounded-full bg-teal-400 shadow-sm"></span>
                            <span class="w-5 h-5 rounded-full bg-slate-900 shadow-sm"></span>
                        </div>
                    </label>

                    <!-- Slate Graphite -->
                    <label class="relative flex flex-col p-3.5 border-2 rounded-2xl cursor-pointer hover:border-slate-600 transition {{ ($settings['dashboard_theme'] ?? '') === 'slate' ? 'border-slate-800 bg-slate-50 ring-2 ring-slate-700/20' : 'border-slate-200' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900">Graphite Dark</span>
                            <input type="radio" name="dashboard_theme" value="slate" {{ ($settings['dashboard_theme'] ?? '') === 'slate' ? 'checked' : '' }} onchange="selectPresetTheme('#1e293b', '#64748b')" class="text-slate-800 focus:ring-slate-700">
                        </div>
                        <div class="flex space-x-1.5 mt-auto">
                            <span class="w-5 h-5 rounded-full bg-slate-900 shadow-sm"></span>
                            <span class="w-5 h-5 rounded-full bg-slate-600 shadow-sm"></span>
                            <span class="w-5 h-5 rounded-full bg-slate-400 shadow-sm"></span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Custom Hex Pickers -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Primary Brand Color</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" id="primaryColorPicker" value="{{ $settings['primary_color'] ?? '#4f46e5' }}" onchange="$('#primaryColorInput').val(this.value)" class="w-10 h-10 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                        <input type="text" id="primaryColorInput" name="primary_color" value="{{ old('primary_color', $settings['primary_color'] ?? '#4f46e5') }}" oninput="$('#primaryColorPicker').val(this.value)" class="w-full px-3 py-2 border rounded-xl font-mono text-xs font-bold uppercase">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Secondary / Electric Accent Color</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" id="accentColorPicker" value="{{ $settings['accent_color'] ?? '#3b82f6' }}" onchange="$('#accentColorInput').val(this.value)" class="w-10 h-10 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                        <input type="text" id="accentColorInput" name="accent_color" value="{{ old('accent_color', $settings['accent_color'] ?? '#3b82f6') }}" oninput="$('#accentColorPicker').val(this.value)" class="w-full px-3 py-2 border rounded-xl font-mono text-xs font-bold uppercase">
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Announcement Banner -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Customer App Announcement Banner</h3>
                        <p class="text-[11px] text-slate-500">Display offers, festival notices, or operational updates at the top of the Customer App</p>
                    </div>
                </div>

                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="banner_enabled" value="1" {{ ($settings['banner_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                    <span class="text-xs font-bold text-slate-700">Enable Banner</span>
                </label>
            </div>

            <div class="text-xs">
                <label class="block font-bold text-slate-700 mb-1">Banner Announcement Text</label>
                <textarea name="announcement_banner" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="e.g. Free Delivery on 5+ cans this festive week! Order before 10 AM for 2-hour delivery.">{{ old('announcement_banner', $settings['announcement_banner'] ?? 'Welcome to HydroPro! Get pure drinking water delivered to your doorstep with real-time tracking.') }}</textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center space-x-3">
            <button type="submit" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-black shadow-lg shadow-indigo-600/30 flex items-center space-x-2 transition hover:scale-[1.01] active:scale-[0.99]">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save All Customization Settings</span>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-5 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold transition">
                Cancel
            </a>
        </div>
    </form>

</div>

@push('scripts')
<script>
    function selectPresetTheme(primaryHex, accentHex) {
        $('#primaryColorPicker').val(primaryHex);
        $('#primaryColorInput').val(primaryHex);
        $('#accentColorPicker').val(accentHex);
        $('#accentColorInput').val(accentHex);
    }
</script>
@endpush
@endsection
