@extends('layouts.customer')

@section('title', 'Home - AquaFlow Water Delivery')

@section('content')
<div class="space-y-6">

    <!-- Top Grid for Tablets & Desktops -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- Welcome & Profile Summary Card (Spans 2 cols on md/lg) -->
        <div class="md:col-span-2 bg-gradient-to-br from-ocean-700 via-ocean-600 to-sky-500 rounded-3xl p-6 text-white shadow-xl shadow-sky-600/20 relative overflow-hidden flex flex-col justify-between">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs uppercase tracking-wider text-sky-200 font-semibold">Delivering Pure Water</span>
                        <h2 class="text-2xl font-black mt-0.5">Hi, {{ explode(' ', $user->name)[0] }} 👋</h2>
                    </div>
                    <div class="bg-white/15 backdrop-blur-md rounded-2xl px-3.5 py-2 border border-white/20 text-right">
                        <span class="text-[10px] text-sky-200 block uppercase font-bold">Cans Held</span>
                        <span class="text-xl font-extrabold flex items-center justify-end space-x-1">
                            <i class="fa-solid fa-bottle-water text-xs text-sky-300"></i>
                            <span>{{ $profile->total_empty_cans_held }}</span>
                        </span>
                    </div>
                </div>

                <!-- Current Address Indicator -->
                <div class="mt-6 pt-3 border-t border-white/15 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2 truncate max-w-[80%]">
                        <i class="fa-solid fa-location-dot text-sky-300"></i>
                        <span class="truncate text-sky-100 font-medium">
                            {{ $defaultAddress ? $defaultAddress->formatted_address : 'Set delivery location' }}
                        </span>
                    </div>
                    <a href="{{ route('customer.profile') }}" class="text-white underline font-semibold text-xs shrink-0 hover:text-sky-200">Change</a>
                </div>
            </div>

            <!-- Decorative background water circle -->
            <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full bg-white/10 pointer-events-none"></div>
        </div>

        <!-- Outstanding Balance Card or Quick Action (Spans 1 col on md/lg) -->
        <div class="flex flex-col justify-between space-y-3">
            @if($profile->total_due_balance > 0)
            <div class="bg-gradient-to-r from-amber-500 to-rose-500 rounded-3xl p-5 text-white shadow-md flex flex-col justify-between h-full">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <span class="text-[11px] text-amber-100 uppercase font-bold tracking-wider block">Outstanding Balance</span>
                        <span class="text-2xl font-black">₹{{ number_format($profile->total_due_balance, 2) }}</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('customer.payments') }}" class="block text-center bg-white text-rose-600 px-4 py-2.5 rounded-xl text-xs font-bold shadow hover:bg-slate-50 transition active:scale-95">
                        Pay Outstanding Balance &rarr;
                    </a>
                </div>
            </div>
            @else
            <div class="bg-gradient-to-br from-emerald-600 to-teal-500 rounded-3xl p-5 text-white shadow-md flex flex-col justify-between h-full">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <span class="text-[11px] text-emerald-100 uppercase font-bold tracking-wider block">Account Status</span>
                        <span class="text-xl font-black">All Dues Cleared</span>
                    </div>
                </div>
                <p class="text-xs text-emerald-100 mt-2">Zero pending payments on your account. Order your refill anytime.</p>
            </div>
            @endif
        </div>

    </div>

    <!-- Quick Action Reorder & Schedule Buttons (Responsive Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <a href="{{ route('customer.cart') }}" class="card-interactive bg-white p-4 sm:p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center space-x-4 group">
            <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-tr from-sky-500 to-cyan-400 text-white flex items-center justify-center text-xl shadow-md shadow-sky-500/25 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div>
                <span class="text-sm sm:text-base font-extrabold text-slate-900 block group-hover:text-ocean-600 transition-colors">My Cart & Checkout</span>
                <span class="text-xs text-slate-400">View added water cans, choose date & order</span>
            </div>
        </a>

        <a href="{{ route('customer.orders') }}" class="card-interactive bg-white p-4 sm:p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center space-x-4 group">
            <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-500/25 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-rotate-right"></i>
            </div>
            <div>
                <span class="text-sm sm:text-base font-extrabold text-slate-900 block group-hover:text-emerald-600 transition-colors">Quick Reorder</span>
                <span class="text-xs text-slate-400">Repeat your previous water can orders</span>
            </div>
        </a>
    </div>

    <!-- Active / Upcoming Delivery Tracker -->
    @if($upcomingDeliveries->count() > 0)
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 tracking-tight flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Active & Upcoming Scheduled Deliveries</span>
            </h3>
            <a href="{{ route('customer.orders') }}" class="text-xs text-ocean-600 font-semibold hover:underline">View all orders</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($upcomingDeliveries as $order)
            <a href="{{ route('customer.order.show', $order->id) }}" class="block bg-white rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="font-bold text-slate-900">#{{ $order->order_number }}</span>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                        {{ $order->order_status === 'out_for_delivery' ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-ocean-800' }}">
                        {{ str_replace('_', ' ', $order->order_status) }}
                    </span>
                </div>

                <div class="flex items-center space-x-3 my-2 text-xs text-slate-600">
                    <div class="flex items-center space-x-1">
                        <i class="fa-regular fa-calendar text-ocean-600"></i>
                        <span>{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}</span>
                    </div>
                    <span>•</span>
                    <div class="flex items-center space-x-1 truncate">
                        <i class="fa-regular fa-clock text-ocean-600"></i>
                        <span class="truncate">{{ $order->slot?->name ?? 'Standard slot' }}</span>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500">{{ $order->items->sum('quantity') }} Cans</span>
                    <span class="font-extrabold text-slate-900">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Dynamic Products Section (Responsive Grid: 1 col on mobile, 2 on sm, 4 on lg) -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Available Water Cans</h3>
                <p class="text-xs text-slate-400">10-stage RO + UV purified, sanitized refillable containers</p>
            </div>
            <a href="{{ route('customer.book') }}" class="text-xs font-bold text-ocean-600 hover:underline">View All &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
            @foreach($featuredProducts as $product)
            <div class="product-card bg-white rounded-3xl p-4 border border-slate-100 shadow-sm flex flex-col justify-between group hover:shadow-md transition" data-id="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                <div>
                    <!-- Product Image with Zoom on Hover -->
                    <div class="w-full h-40 sm:h-48 rounded-2xl bg-slate-50 overflow-hidden relative mb-3">
                        <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=500' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2.5 left-2.5 bg-slate-900/80 backdrop-blur text-white font-extrabold text-[10px] px-2.5 py-0.5 rounded-lg shadow-sm">
                            {{ $product->volume }} {{ $product->unit }}
                        </span>
                        @if($product->volume == '20')
                            <span class="absolute top-2.5 right-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-[9px] uppercase px-2 py-0.5 rounded-md shadow-sm">
                                Popular
                            </span>
                        @endif
                    </div>

                    <h4 class="text-sm font-extrabold text-slate-900 leading-snug line-clamp-2 group-hover:text-ocean-600 transition-colors">{{ $product->name }}</h4>
                    <p class="text-xs text-slate-400 line-clamp-2 mt-1">{{ $product->description }}</p>
                </div>

                <!-- Product Pricing, Quantity Selector & Action Buttons -->
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-3">
                    <!-- Price and 1:1 Exchange badge -->
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-baseline space-x-1">
                                <span class="text-lg font-black text-slate-900">₹{{ number_format($product->selling_price, 2) }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400 block -mt-0.5">Incl. all taxes</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-lg border border-sky-200 bg-sky-50 text-sky-700 text-[10px] font-bold">
                            1:1 Exchange
                        </span>
                    </div>

                    <!-- Quantity Row -->
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span class="font-bold text-slate-500 text-[11px]">Quantity:</span>
                        <div class="flex items-center border border-slate-200 rounded-full px-2 py-0.5 bg-slate-50/50 space-x-3">
                            <button type="button" class="btn-home-qty-minus text-slate-400 hover:text-slate-800 text-xs px-1 font-extrabold active:scale-95 transition">
                                <i class="fa-solid fa-minus text-[10px]"></i>
                            </button>
                            <span class="home-qty-display font-black text-slate-800 text-xs w-4 text-center">1</span>
                            <button type="button" class="btn-home-qty-plus text-slate-400 hover:text-slate-800 text-xs px-1 font-extrabold active:scale-95 transition">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons: Add Cart & Buy Now -->
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <!-- Add Cart Button -->
                        <button type="button" class="btn-add-cart w-full py-2 px-2 rounded-xl border border-sky-200 bg-sky-50 hover:bg-sky-100 text-ocean-700 font-extrabold text-[11px] flex items-center justify-center space-x-1.5 transition active:scale-95 shadow-sm">
                            <i class="fa-solid fa-bag-shopping text-xs"></i>
                            <span>Add Cart</span>
                        </button>

                        <!-- Buy Now Button -->
                        <button type="button" class="btn-buy-now w-full py-2 px-2 rounded-xl bg-gradient-to-r from-sky-600 to-ocean-700 hover:from-sky-700 hover:to-ocean-800 text-white font-extrabold text-[11px] flex items-center justify-center space-x-1.5 shadow-md shadow-sky-600/20 transition active:scale-95">
                            <span>Buy Now</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Promotional Clean Drinking Banner -->
    <div class="bg-gradient-to-r from-cyan-500 via-sky-600 to-ocean-700 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="max-w-xl z-10">
            <span class="text-[10px] uppercase tracking-wider font-extrabold bg-white/20 px-2.5 py-0.5 rounded-full inline-block mb-1.5">Purity Assured</span>
            <h4 class="text-lg sm:text-xl font-black leading-tight">Need Weekly or Daily Water Can Subscription?</h4>
            <p class="text-xs text-cyan-100 mt-1">Book scheduled refills with recurring delivery options directly to your doorway.</p>
        </div>
        <div class="z-10 shrink-0">
            <a href="{{ route('customer.book') }}" class="inline-block px-5 py-2.5 bg-white text-ocean-700 font-extrabold text-xs rounded-xl shadow hover:bg-slate-50 transition">
                Schedule Delivery &rarr;
            </a>
        </div>
        <div class="absolute -right-4 -bottom-4 text-white/15 text-8xl pointer-events-none">
            <i class="fa-solid fa-faucet-drip"></i>
        </div>
    </div>

    <!-- Customer Support Quick Card -->
    <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-5 border border-slate-100 shadow-sm flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-11 h-11 rounded-2xl bg-sky-50 text-ocean-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div>
                <span class="text-xs sm:text-sm font-bold text-slate-900 block">Need help with delivery or returns?</span>
                <span class="text-xs text-slate-500">Call Support: +91 98765 43210 (7 AM - 9 PM)</span>
            </div>
        </div>
        <a href="tel:+919876543210" class="px-4 py-2 rounded-xl bg-slate-100 flex items-center space-x-2 text-slate-700 hover:bg-ocean-600 hover:text-white transition text-xs font-bold">
            <i class="fa-solid fa-phone text-xs"></i>
            <span class="hidden sm:inline">Call Now</span>
        </a>
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Quantity increment
    $('.btn-home-qty-plus').on('click', function() {
        const span = $(this).siblings('.home-qty-display');
        let val = parseInt(span.text()) || 1;
        span.text(val + 1);
    });

    // Quantity decrement
    $('.btn-home-qty-minus').on('click', function() {
        const span = $(this).siblings('.home-qty-display');
        let val = parseInt(span.text()) || 1;
        if (val > 1) {
            span.text(val - 1);
        }
    });

    // Add to Cart button handler
    $('.btn-add-cart').on('click', function() {
        const card = $(this).closest('.product-card');
        const prodId = card.data('id');
        const qty = parseInt(card.find('.home-qty-display').text()) || 1;

        // Existing cart quantity + new quantity
        const existingQty = window.CartManager.getItemQty(prodId);
        const newTotalQty = existingQty + qty;

        window.CartManager.setItemQty(prodId, newTotalQty);

        // Feedback Toast
        const btn = $(this);
        const originalContent = btn.html();
        btn.addClass('bg-emerald-50 text-emerald-700 border-emerald-300').html('<i class="fa-solid fa-check text-xs"></i> <span>Added!</span>');

        setTimeout(() => {
            btn.removeClass('bg-emerald-50 text-emerald-700 border-emerald-300').html(originalContent);
        }, 1200);

        // Optional small Swal toast
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: `Added ${qty} item(s) to Cart!`,
            showConfirmButton: false,
            timer: 1800,
            timerProgressBar: true
        });
    });

    // Buy Now button handler
    $('.btn-buy-now').on('click', function() {
        const card = $(this).closest('.product-card');
        const prodId = card.data('id');
        const qty = parseInt(card.find('.home-qty-display').text()) || 1;

        // Ensure this product is in the cart and navigate to cart page
        window.CartManager.setItemQty(prodId, qty);
        window.location.href = "{{ route('customer.cart') }}?product_id=" + prodId + "&qty=" + qty;
    });
});
</script>
@endpush
@endsection

