@extends('layouts.customer')

@section('title', 'Profile - ' . ($appSettings['business_name'] ?? 'AquaFlow'))

@section('content')

<!-- Mobile-Only Profile Section (Requirement 1: Profile Section for Mobile View Only) -->
<div class="md:hidden max-w-2xl mx-auto space-y-4 pb-8">

    <!-- Screen Title -->
    <div class="pt-1 pb-1">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Profile</h1>
    </div>

    <!-- User Header Card -->
    <div class="flex items-center space-x-4 py-2">
        <div class="w-16 h-16 rounded-full bg-sky-400 flex items-center justify-center text-white text-3xl shrink-0 shadow-sm">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="min-w-0">
            <h2 class="text-lg font-bold text-slate-900 truncate">{{ $user->name }}</h2>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">{{ $user->phone ?? 'No phone added' }}</p>
        </div>
    </div>

    <!-- Standalone Action Cards -->
    <div class="space-y-2.5">
        <!-- View Profile Details -->
        <div onclick="openModal('profileDetailsModal')" class="w-full bg-[#f8f9fa] hover:bg-slate-100 transition rounded-2xl p-4 flex items-center justify-between cursor-pointer border border-slate-100 shadow-2xs">
            <div class="flex items-center space-x-4">
                <i class="fa-regular fa-user text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800">View Profile Details</span>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
        </div>

        <!-- Wallet Card -->
        <div onclick="openModal('walletModal')" class="w-full bg-[#f8f9fa] hover:bg-slate-100 transition rounded-2xl p-4 flex items-center justify-between cursor-pointer border border-slate-100 shadow-2xs">
            <div class="flex items-center space-x-4">
                <i class="fa-solid fa-wallet text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800">
                    {{ explode(' ', $appSettings['business_name'] ?? 'AquaFlow')[0] }} Wallet
                </span>
            </div>
            <span class="text-sm sm:text-base font-bold text-slate-900">₹0</span>
        </div>

        <!-- Bank Details Card -->
        <div onclick="openModal('bankDetailsModal')" class="w-full bg-[#f8f9fa] hover:bg-slate-100 transition rounded-2xl p-4 flex items-center justify-between cursor-pointer border border-slate-100 shadow-2xs">
            <div class="flex items-center space-x-4">
                <i class="fa-solid fa-building-columns text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800">Bank Details</span>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
        </div>
    </div>

    <!-- Section: Orders -->
    <div class="pt-2">
        <h3 class="text-sm font-bold text-slate-900 px-1 mb-2">Orders</h3>
        <div class="bg-[#f8f9fa] rounded-2xl p-1.5 border border-slate-100 shadow-2xs divide-y divide-slate-100/80">
            <!-- All Orders -->
            <a href="{{ route('customer.orders') }}" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition">
                <i class="fa-solid fa-box text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">All Orders</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </a>

            <!-- Order Calendar -->
            <div onclick="openModal('orderCalendarModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-regular fa-calendar-days text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Order Calendar</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>

            <!-- Favorite Orders -->
            <div onclick="openModal('favoriteOrdersModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-solid fa-heart text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Favorite Orders</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>

            <!-- Address Book -->
            <div onclick="openModal('addressBookModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-regular fa-address-book text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Address Book</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>

            <!-- Transaction History -->
            <a href="{{ route('customer.payments') }}" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition">
                <i class="fa-regular fa-clock text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Transaction History</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </a>

            <!-- Return Empty Jar -->
            <div onclick="openModal('returnJarModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-solid fa-bottle-water text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Return Empty Jar</span>
                <span class="text-xs bg-teal-100 text-teal-800 font-bold px-2 py-0.5 rounded-full mr-1">
                    {{ $user->customerProfile?->total_empty_cans_held ?? 0 }} held
                </span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>
        </div>
    </div>

    <!-- Section: Customer Support -->
    <div class="pt-2">
        <h3 class="text-sm font-bold text-slate-900 px-1 mb-2">Customer Support</h3>
        <div class="bg-[#f8f9fa] rounded-2xl p-1.5 border border-slate-100 shadow-2xs divide-y divide-slate-100/80">
            <!-- Contact Information -->
            <div onclick="openModal('contactInfoModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-regular fa-id-card text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Contact Information</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>

            <!-- Chat With Us -->
            <div onclick="openModal('chatSupportModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-regular fa-comment-dots text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Chat With Us</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>

            <!-- FAQ -->
            <div onclick="openModal('faqModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-regular fa-circle-question text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">FAQ</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>

            <!-- Locate Us -->
            <div onclick="openModal('locateUsModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-regular fa-compass text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Locate Us</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>
        </div>
    </div>

    <!-- Section: Rewards -->
    <div class="pt-2">
        <h3 class="text-sm font-bold text-slate-900 px-1 mb-2">Rewards</h3>
        <div class="bg-[#f8f9fa] rounded-2xl p-1.5 border border-slate-100 shadow-2xs">
            <!-- Collected Coupons -->
            <div onclick="openModal('couponsModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-solid fa-ticket text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Collected Coupons</span>
                <span class="text-xs bg-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded-full mr-1">3 Active</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>
        </div>
    </div>

    <!-- Section: Legal -->
    <div class="pt-2">
        <h3 class="text-sm font-bold text-slate-900 px-1 mb-2">Legal</h3>
        <div class="bg-[#f8f9fa] rounded-2xl p-1.5 border border-slate-100 shadow-2xs divide-y divide-slate-100/80">
            <!-- About us -->
            <div onclick="openModal('aboutUsModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-regular fa-circle-info text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">About us</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>

            <!-- Terms & Conditions -->
            <div onclick="openModal('termsModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-regular fa-file-lines text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Terms & Conditions</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>

            <!-- Cancellation & Refunds Policy -->
            <div onclick="openModal('refundPolicyModal')" class="flex items-center space-x-4 px-3.5 py-3.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fa-solid fa-box-archive text-lg text-slate-700 w-6 text-center"></i>
                <span class="text-sm sm:text-base font-medium text-slate-800 flex-1">Cancellation & Refunds Policy</span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-300"></i>
            </div>
        </div>
    </div>

    <!-- Section: Logout -->
    <div class="pt-2">
        <div onclick="confirmLogout()" class="w-full bg-[#f8f9fa] hover:bg-rose-50 transition rounded-2xl p-4 flex items-center space-x-4 cursor-pointer border border-slate-100 shadow-2xs group">
            <i class="fa-solid fa-arrow-right-from-bracket text-lg text-slate-700 group-hover:text-rose-600 w-6 text-center transition"></i>
            <span class="text-sm sm:text-base font-medium text-slate-800 group-hover:text-rose-600 transition">Logout</span>
        </div>
    </div>

</div>

<!-- Desktop View Screen (Shown if Desktop User Directly Navigates to /profile) -->
<div class="hidden md:flex flex-col items-center justify-center py-16 text-center max-w-md mx-auto">
    <div class="w-16 h-16 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-3xl mb-4 shadow-sm">
        <i class="fa-solid fa-circle-user"></i>
    </div>
    <h2 class="text-xl font-black text-slate-900">Desktop View Profile Menu</h2>
    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1.5 mb-6">
        On desktop, all profile actions (Account Settings, Manage Addresses, Orders, Subscriptions, Wallet, and Return Jar) are available in the top-right profile dropdown menu.
    </p>
    <a href="{{ route('customer.home') }}" class="px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-extrabold rounded-xl shadow-md transition">
        Return to Home Page
    </a>
</div>

<script>
    // Requirement 1: Profile section is only for mobile view.
    // If accessed on desktop, redirect to Home where desktop dropdown is ready.
    if (window.innerWidth >= 768) {
        window.location.replace("{{ route('customer.home') }}");
    }
</script>

@endsection
