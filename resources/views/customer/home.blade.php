@extends('layouts.customer')

@section('title', 'Home - AquaFlow Water Delivery')

@section('content')
<div class="space-y-6">

    <!-- Compact Customer Details Bar (Reduced Space) -->
    <div class="bg-gradient-to-r from-ocean-700 via-ocean-600 to-sky-600 rounded-2xl px-4 py-3 sm:px-5 sm:py-3.5 text-white shadow-md relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div class="flex items-center space-x-3 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur border border-white/20 flex items-center justify-center text-lg text-sky-200 shrink-0 shadow-inner">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center space-x-2">
                    <h2 class="text-base sm:text-lg font-black truncate leading-tight">Hi, {{ explode(' ', $user->name)[0] }} 👋</h2>
                    @if($profile->total_due_balance > 0)
                    <a href="{{ route('customer.payments') }}" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white shadow-sm shrink-0 hover:bg-rose-600 transition">
                        Due: ₹{{ number_format($profile->total_due_balance, 2) }}
                    </a>
                    @endif
                </div>
                <div class="flex items-center space-x-1.5 text-[11px] text-sky-100 font-medium truncate mt-0.5">
                    <i class="fa-solid fa-location-dot text-sky-300 text-[10px] shrink-0"></i>
                    <span class="truncate">{{ $defaultAddress ? $defaultAddress->formatted_address : 'Set delivery location' }}</span>
                    <a href="{{ route('customer.profile') }}" class="text-white underline font-bold hover:text-sky-200 shrink-0 ml-1">Change</a>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2 self-end sm:self-auto shrink-0">
            <div class="bg-white/15 backdrop-blur-md rounded-xl px-3 py-1.5 border border-white/20 flex items-center space-x-2">
                <i class="fa-solid fa-bottle-water text-xs text-sky-300"></i>
                <div class="text-right">
                    <span class="text-[9px] text-sky-200 block uppercase font-bold leading-none">Cans Held</span>
                    <span class="text-sm font-black leading-tight">{{ $profile->total_empty_cans_held }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Banner Carousel (Auto-Scrolling Slider) -->
    @if(isset($banners) && $banners->count() > 0)
    <div class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-slate-100 group" id="heroBannerCarousel">
        <!-- Slides Track -->
        <div class="relative w-full h-44 sm:h-56 md:h-64 lg:h-72 overflow-hidden bg-slate-900">
            @foreach($banners as $index => $banner)
            <div class="hero-slide absolute inset-0 transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}" data-index="{{ $index }}">
                @if($banner->link_url)
                <a href="{{ $banner->link_url }}" class="block w-full h-full relative group/item">
                @else
                <div class="block w-full h-full relative">
                @endif
                    <!-- Image -->
                    <img src="{{ $banner->image_path }}" alt="{{ $banner->title ?? 'Hero Banner' }}" class="w-full h-full object-cover">
                    
                    <!-- Gradient Overlay with Title and Subtitle -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/30 to-transparent flex flex-col justify-end p-5 sm:p-7 md:p-8 text-white">
                        @if($banner->title)
                        <h3 class="text-lg sm:text-2xl md:text-3xl font-black tracking-tight leading-tight drop-shadow-md">
                            {{ $banner->title }}
                        </h3>
                        @endif
                        @if($banner->subtitle)
                        <p class="text-xs sm:text-sm md:text-base text-sky-100 mt-1 max-w-xl font-medium drop-shadow line-clamp-2">
                            {{ $banner->subtitle }}
                        </p>
                        @endif
                        @if($banner->link_url)
                        <div class="mt-3">
                            <span class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-white text-ocean-700 font-extrabold text-xs shadow-md group-hover/item:bg-sky-50 transition">
                                <span>Order Now</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </div>
                        @endif
                    </div>
                @if($banner->link_url)
                </a>
                @else
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Carousel Navigation Controls (< & >) -->
        @if($banners->count() > 1)
        <button type="button" id="heroPrevBtn" class="absolute left-2.5 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-md">
            <i class="fa-solid fa-chevron-left text-xs sm:text-sm"></i>
        </button>
        <button type="button" id="heroNextBtn" class="absolute right-2.5 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-md">
            <i class="fa-solid fa-chevron-right text-xs sm:text-sm"></i>
        </button>

        <!-- Carousel Dots Indicator -->
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-20 flex items-center space-x-1.5 bg-black/30 backdrop-blur-sm px-2.5 py-1 rounded-full">
            @foreach($banners as $index => $banner)
            <button type="button" class="hero-dot h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-5' : 'bg-white/50 hover:bg-white/80 w-2' }}" data-index="{{ $index }}"></button>
            @endforeach
        </div>
        @endif
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
    // Hero Banner Auto-Scrolling Carousel
    const heroSlides = $('.hero-slide');
    const heroDots = $('.hero-dot');
    const totalHeroSlides = heroSlides.length;
    let currentHeroIndex = 0;
    let heroAutoTimer = null;

    function renderHeroSlide(index) {
        if (totalHeroSlides <= 1) return;
        if (index >= totalHeroSlides) index = 0;
        if (index < 0) index = totalHeroSlides - 1;
        currentHeroIndex = index;

        heroSlides.each(function(i) {
            if (i === currentHeroIndex) {
                $(this).removeClass('opacity-0 z-0 pointer-events-none').addClass('opacity-100 z-10 pointer-events-auto');
            } else {
                $(this).removeClass('opacity-100 z-10 pointer-events-auto').addClass('opacity-0 z-0 pointer-events-none');
            }
        });

        heroDots.each(function(i) {
            if (i === currentHeroIndex) {
                $(this).removeClass('bg-white/50 w-2').addClass('bg-white w-5');
            } else {
                $(this).removeClass('bg-white w-5').addClass('bg-white/50 w-2');
            }
        });
    }

    function startHeroTimer() {
        if (totalHeroSlides > 1 && !heroAutoTimer) {
            heroAutoTimer = setInterval(function() {
                renderHeroSlide(currentHeroIndex + 1);
            }, 4000); // Automatically rotates every 4 seconds
        }
    }

    function stopHeroTimer() {
        if (heroAutoTimer) {
            clearInterval(heroAutoTimer);
            heroAutoTimer = null;
        }
    }

    if (totalHeroSlides > 1) {
        startHeroTimer();

        // Pause on hover
        $('#heroBannerCarousel').on('mouseenter', stopHeroTimer).on('mouseleave', startHeroTimer);

        // Previous button
        $('#heroPrevBtn').on('click', function(e) {
            e.preventDefault();
            stopHeroTimer();
            renderHeroSlide(currentHeroIndex - 1);
            startHeroTimer();
        });

        // Next button
        $('#heroNextBtn').on('click', function(e) {
            e.preventDefault();
            stopHeroTimer();
            renderHeroSlide(currentHeroIndex + 1);
            startHeroTimer();
        });

        // Click on dots
        heroDots.on('click', function() {
            const idx = parseInt($(this).data('index'));
            stopHeroTimer();
            renderHeroSlide(idx);
            startHeroTimer();
        });

        // Touch swipe support for mobile devices
        let startX = 0;
        const carouselEl = document.getElementById('heroBannerCarousel');
        if (carouselEl) {
            carouselEl.addEventListener('touchstart', function(e) {
                startX = e.changedTouches[0].screenX;
                stopHeroTimer();
            }, { passive: true });

            carouselEl.addEventListener('touchend', function(e) {
                const endX = e.changedTouches[0].screenX;
                if (startX - endX > 45) {
                    renderHeroSlide(currentHeroIndex + 1);
                } else if (endX - startX > 45) {
                    renderHeroSlide(currentHeroIndex - 1);
                }
                startHeroTimer();
            }, { passive: true });
        }
    }

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

        // Modern Interactive Cart Toast Popup with Action Button (Responsive for Mobile & Desktop with Fade In/Out)
        const prodName = card.find('h4').text().trim() || 'Water Can';
        const prodImg = card.find('img').attr('src');
        const prodPrice = parseFloat(card.data('price')) || 0;

        if (window.CartManager && window.CartManager.showCartPopup) {
            window.CartManager.showCartPopup({
                name: prodName,
                img: prodImg,
                subtitle: `<span class="text-sky-600 font-bold">+${qty} Added</span> &bull; ₹${(prodPrice * qty).toFixed(2)}`,
                escape: escapeHtml,
                timer: 3500
            });
        }
    });

    // Auto load cart popup on page load if items exist in cart (Desktop & Mobile)
    if (window.CartManager && window.CartManager.getTotalCount() > 0) {
        setTimeout(() => {
            const cart = window.CartManager.getCart();
            const firstProdId = Object.keys(cart)[0];
            let popupImg = '/icons/logo.svg';
            let popupName = 'Items in Cart';

            if (firstProdId) {
                const matchedCard = $(`.product-card[data-id="${firstProdId}"]`);
                if (matchedCard.length) {
                    popupImg = matchedCard.find('img').attr('src') || popupImg;
                    popupName = matchedCard.find('h4').text().trim() || popupName;
                }
            }

            const total = window.CartManager.getTotalCount();
            window.CartManager.showCartPopup({
                name: popupName,
                img: popupImg,
                subtitle: `<span class="text-sky-600 font-bold">${total} Can${total > 1 ? 's' : ''} saved</span> &bull; Ready to checkout`,
                escape: escapeHtml,
                timer: 4500
            });
        }, 1200);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

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

