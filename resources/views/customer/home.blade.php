@extends('layouts.customer')

@section('title', 'Home - AquaFlow Water Delivery')

@section('content')
<div class="space-y-6">

    <!-- Hero Banner Carousel (Full width, light shadow) -->
    @if(isset($banners) && $banners->count() > 0)
    <div class="relative -mx-4 sm:-mx-6 lg:mx-0 w-[calc(100%+2rem)] sm:w-[calc(100%+3rem)] lg:w-full rounded-none sm:rounded-3xl overflow-hidden shadow-sm border-y sm:border border-slate-100 group select-none" id="heroBannerCarousel">
        <!-- Viewport -->
        <div class="relative w-full h-56 sm:h-72 md:h-80 lg:h-96 overflow-hidden bg-slate-50">
            <!-- Sliding Track (Moves Right to Left) -->
            <div id="heroTrack" class="flex w-full h-full will-change-transform">
                @foreach($banners as $index => $banner)
                <div class="hero-slide w-full h-full shrink-0 flex-none relative" data-index="{{ $index }}">
                @if($banner->link_url)
                <a href="{{ $banner->link_url }}" class="block w-full h-full relative group/item">
                @else
                <div class="block w-full h-full relative">
                @endif
                    <!-- Image -->
                    <img src="{{ $banner->image_path }}" alt="{{ $banner->title ?? 'Hero Banner' }}" class="w-full h-full object-cover">
                    
                    @if($banner->title || $banner->subtitle)
                    <!-- Gradient Overlay (Light shadow so image remains bright) -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent flex flex-col justify-end p-5 sm:p-7 md:p-8 text-white">
                        @if($banner->title)
                        <h3 class="text-lg sm:text-2xl md:text-3xl font-black tracking-tight leading-tight drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
                            {{ $banner->title }}
                        </h3>
                        @endif
                        @if($banner->subtitle)
                        <p class="text-xs sm:text-sm md:text-base text-sky-100 mt-1 max-w-xl font-medium drop-shadow-[0_1px_3px_rgba(0,0,0,0.6)] line-clamp-2">
                            {{ $banner->subtitle }}
                        </p>
                        @endif
                        @if($banner->link_url)
                        <div class="mt-3">
                            <span class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-white text-sky-700 font-extrabold text-xs shadow-md group-hover/item:bg-sky-50 transition">
                                <span>Order Now</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </div>
                        @endif
                    </div>
                    @elseif($banner->link_url)
                    <div class="absolute bottom-4 right-4 z-10">
                        <span class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-white/90 backdrop-blur text-sky-700 font-extrabold text-xs shadow-md group-hover:bg-white transition">
                            <span>Order Now</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                    @endif
                @if($banner->link_url)
                </a>
                @else
                </div>
                @endif
            </div>
            @endforeach
            </div>
        </div>

        <!-- Carousel Navigation Controls (< & >) -->
        @if($banners->count() > 1)
        <button type="button" id="heroPrevBtn" class="absolute left-2.5 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white/80 hover:bg-white text-slate-800 backdrop-blur flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-md">
            <i class="fa-solid fa-chevron-left text-xs sm:text-sm"></i>
        </button>
        <button type="button" id="heroNextBtn" class="absolute right-2.5 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white/80 hover:bg-white text-slate-800 backdrop-blur flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-md">
            <i class="fa-solid fa-chevron-right text-xs sm:text-sm"></i>
        </button>

        <!-- Carousel Dots Indicator -->
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-20 flex items-center space-x-1.5 bg-black/20 backdrop-blur-sm px-2.5 py-1 rounded-full">
            @foreach($banners as $index => $banner)
            <button type="button" class="hero-dot h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-5 shadow-xs' : 'bg-white/60 hover:bg-white w-2' }}" data-index="{{ $index }}"></button>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    <!-- Available Services Section (Services in a Row with Coming Soon option) -->
    @if(isset($services) && $services->count() > 0)
    <div class="space-y-3.5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-500 inline-block shadow-sm"></span>
                    <span>Available Services</span>
                </h3>
                <p class="text-[11px] sm:text-xs text-slate-500 font-medium">Doorstep solutions for drinking water, vehicle care, and property rental</p>
            </div>
            <a href="{{ route('customer.services') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center space-x-1 shrink-0 group">
                <span>View All</span>
                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
            </a>
        </div>

        <!-- Available Services Grid: 1 by 1 on Mobile, Multi-column on Desktop -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($services as $srv)
            @php
                $isComingSoon = strtolower(trim($srv->badge_text ?? '')) === 'coming soon' || strtolower(trim($srv->action_text ?? '')) === 'coming soon' || $srv->action_url === '#comingSoon';
            @endphp
            <div class="w-full bg-white hover:bg-sky-50/40 rounded-2xl sm:rounded-3xl p-4 sm:p-5 border border-slate-200/80 hover:border-sky-400 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                <div class="space-y-3">
                    <!-- Image Card with Badge -->
                    <div class="relative w-full h-44 sm:h-40 rounded-xl sm:rounded-2xl overflow-hidden bg-slate-100">
                        <img src="{{ $srv->image_path }}" alt="{{ $srv->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent flex flex-col justify-end p-3 text-white">
                            <span class="text-xs text-sky-100 font-bold block drop-shadow">{{ $srv->tagline }}</span>
                        </div>
                        @if($isComingSoon)
                        <div class="absolute top-2.5 left-2.5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500 text-white shadow-sm flex items-center space-x-1">
                                <i class="fa-regular fa-clock text-[9px]"></i>
                                <span>Coming Soon</span>
                            </span>
                        </div>
                        @elseif($srv->badge_text)
                        <div class="absolute top-2.5 left-2.5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/95 text-slate-900 backdrop-blur shadow-sm">
                                {{ $srv->badge_text }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Title & Description -->
                    <div>
                        <h4 class="text-sm sm:text-base font-black text-slate-900 group-hover:text-sky-600 transition-colors leading-tight line-clamp-1">
                            {{ $srv->name }}
                        </h4>
                        @if($srv->description)
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">
                            {{ $srv->description }}
                        </p>
                        @endif
                    </div>

                    <!-- Highlights / Feature list -->
                    @if(!empty($srv->features) && count($srv->features) > 0)
                    <ul class="text-xs text-slate-600 space-y-1 pt-2 border-t border-slate-100">
                        @foreach(array_slice($srv->features, 0, 2) as $feat)
                        <li class="flex items-center space-x-1.5 text-[11px] truncate">
                            <i class="fa-solid fa-circle-check {{ $isComingSoon ? 'text-amber-500' : 'text-sky-500' }} text-[10px] shrink-0"></i>
                            <span class="truncate">{{ $feat }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <!-- CTA Action Button (Active or Coming Soon) -->
                <div class="pt-3 sm:pt-4">
                    @if($isComingSoon)
                    <button type="button" onclick="openHomeServiceInquiry('{{ addslashes($srv->name) }}', 'Coming soon service pre-registration')" class="w-full py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/80 font-extrabold text-xs text-center transition flex items-center justify-center space-x-1.5 shadow-xs">
                        <i class="fa-regular fa-bell text-xs text-amber-600"></i>
                        <span>Coming Soon &bull; Notify Me</span>
                    </button>
                    @elseif($srv->action_url && !str_starts_with($srv->action_url, '#'))
                    <a href="{{ $srv->action_url }}" class="w-full py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-extrabold text-xs text-center shadow-md shadow-sky-500/20 active:scale-95 transition-all flex items-center justify-center space-x-1.5">
                        <span>{{ $srv->action_text ?: 'Order Now' }}</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                    @else
                    <button type="button" onclick="openHomeServiceInquiry('{{ addslashes($srv->name) }}', '{{ addslashes($srv->tagline ?? $srv->description ?? 'Service request') }}')" class="w-full py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-extrabold text-xs text-center shadow-md shadow-sky-500/20 active:scale-95 transition-all flex items-center justify-center space-x-1.5">
                        <span>{{ $srv->action_text ?: 'Book Service' }}</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Dynamic Products Section (2 Columns Grid on Mobile & Tablet, 4 on Desktop) -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Available Water Cans</h3>
                <p class="text-xs text-slate-400">10-stage RO + UV purified, sanitized refillable containers</p>
            </div>
            <a href="{{ route('customer.book') }}" class="text-xs font-bold text-ocean-600 hover:underline">View All &rarr;</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5 sm:gap-4">
            @foreach($featuredProducts as $product)
            <div class="product-card bg-white rounded-2xl sm:rounded-3xl p-3 sm:p-4 border border-slate-100 shadow-sm flex flex-col justify-between group hover:shadow-md transition" data-id="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                <div>
                    <!-- Product Image with Zoom on Hover -->
                    <div class="w-full h-32 sm:h-44 md:h-48 rounded-xl sm:rounded-2xl bg-slate-50 overflow-hidden relative mb-2.5 sm:mb-3">
                        <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=500' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2 left-2 bg-slate-900/80 backdrop-blur text-white font-extrabold text-[9px] sm:text-[10px] px-2 py-0.5 rounded-md sm:rounded-lg shadow-sm">
                            {{ $product->volume }} {{ $product->unit }}
                        </span>
                        @if($product->volume == '20')
                            <span class="absolute top-2 right-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-[8px] sm:text-[9px] uppercase px-1.5 sm:px-2 py-0.5 rounded-md shadow-sm">
                                Popular
                            </span>
                        @endif
                    </div>

                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug line-clamp-2 group-hover:text-ocean-600 transition-colors">{{ $product->name }}</h4>
                    <p class="text-[11px] sm:text-xs text-slate-400 line-clamp-1 sm:line-clamp-2 mt-0.5 sm:mt-1">{{ $product->description }}</p>
                </div>

                <!-- Product Pricing, Quantity Selector & Action Buttons -->
                <div class="mt-3 sm:mt-4 pt-2.5 sm:pt-3 border-t border-slate-100 space-y-2 sm:space-y-3">
                    <!-- Price and 1:1 Exchange badge -->
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-baseline space-x-1">
                                <span class="text-base sm:text-lg font-black text-slate-900">₹{{ number_format($product->selling_price, 2) }}</span>
                            </div>
                            <span class="text-[9px] sm:text-[10px] text-slate-400 block -mt-0.5">Incl. taxes</span>
                        </div>
                        <span class="px-1.5 sm:px-2 py-0.5 rounded-md sm:rounded-lg border border-sky-200 bg-sky-50 text-sky-700 text-[9px] sm:text-[10px] font-bold">
                            1:1 Exch.
                        </span>
                    </div>

                    <!-- Quantity Row -->
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span class="font-bold text-slate-500 text-[10px] sm:text-[11px]">Qty:</span>
                        <div class="flex items-center border border-slate-200 rounded-full px-1.5 sm:px-2 py-0.5 bg-slate-50/50 space-x-2 sm:space-x-3">
                            <button type="button" class="btn-home-qty-minus text-slate-400 hover:text-slate-800 text-xs px-1 font-extrabold active:scale-95 transition">
                                <i class="fa-solid fa-minus text-[9px] sm:text-[10px]"></i>
                            </button>
                            <span class="home-qty-display font-black text-slate-800 text-[11px] sm:text-xs w-3 sm:w-4 text-center">1</span>
                            <button type="button" class="btn-home-qty-plus text-slate-400 hover:text-slate-800 text-xs px-1 font-extrabold active:scale-95 transition">
                                <i class="fa-solid fa-plus text-[9px] sm:text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons: Add Cart & Buy Now -->
                    <div class="grid grid-cols-2 gap-1.5 sm:gap-2 pt-1">
                        <!-- Add Cart Button -->
                        <button type="button" class="btn-add-cart w-full py-1.5 sm:py-2 px-1 rounded-xl border border-sky-200 bg-sky-50 hover:bg-sky-100 text-ocean-700 font-extrabold text-[10px] sm:text-[11px] flex items-center justify-center space-x-1 transition active:scale-95 shadow-sm">
                            <i class="fa-solid fa-bag-shopping text-[10px] sm:text-xs"></i>
                            <span>Add Cart</span>
                        </button>

                        <!-- Buy Now Button -->
                        <button type="button" class="btn-buy-now w-full py-1.5 sm:py-2 px-1 rounded-xl bg-gradient-to-r from-sky-600 to-ocean-700 hover:from-sky-700 hover:to-ocean-800 text-white font-extrabold text-[10px] sm:text-[11px] flex items-center justify-center space-x-1 shadow-md shadow-sky-600/20 transition active:scale-95">
                            <span>Buy Now</span>
                            <i class="fa-solid fa-arrow-right text-[8px] sm:text-[10px]"></i>
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
            <span class="text-[10px] uppercase tracking-wider font-extrabold bg-white/20 px-2.5 py-0.5 rounded-full inline-block mb-1.5">Save up to 15%</span>
            <h4 class="text-lg sm:text-xl font-black leading-tight">Need Weekly or Daily Water Can Subscription?</h4>
            <p class="text-xs text-cyan-100 mt-1">Book recurring delivery plans (Monthly, Alternate Days, Weekly Once) with automated scheduling and doorstep refills.</p>
        </div>
        <div class="z-10 shrink-0">
            <a href="{{ route('customer.subscriptions.index') }}" class="inline-block px-5 py-2.5 bg-white text-ocean-700 font-extrabold text-xs rounded-xl shadow hover:bg-slate-50 transition active:scale-95">
                Explore Subscriptions &rarr;
            </a>
        </div>
        <div class="absolute -right-4 -bottom-4 text-white/15 text-8xl pointer-events-none">
            <i class="fa-solid fa-repeat"></i>
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

<!-- Service Inquiry Modal for Home Page -->
<div id="homeServiceInquiryModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <div>
                <h3 id="homeInquiryServiceTitle" class="text-base font-black text-slate-900">Service Request</h3>
                <p id="homeInquiryServiceSubtitle" class="text-xs text-slate-400">Doorstep assistance request</p>
            </div>
            <button type="button" onclick="closeHomeServiceInquiry()" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <form onsubmit="handleHomeInquirySubmit(event)" class="space-y-3.5 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Your Name</label>
                <input type="text" id="homeInqName" value="{{ auth()->user()->name }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-semibold focus:outline-none focus:ring-2 focus:ring-ocean-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Contact Mobile Number</label>
                <input type="text" id="homeInqPhone" value="{{ auth()->user()->phone }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-semibold focus:outline-none focus:ring-2 focus:ring-ocean-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Preferred Location / Notes</label>
                <textarea id="homeInqNotes" rows="2" placeholder="e.g. Madipakkam / Sedan car wash / 2 BHK flat rental" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-ocean-500"></textarea>
            </div>
            <button type="submit" class="w-full py-3.5 rounded-2xl bg-ocean-600 hover:bg-ocean-700 text-white font-extrabold text-xs shadow-md shadow-sky-600/30 transition flex items-center justify-center space-x-2">
                <span>Submit Request</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openHomeServiceInquiry(title, subtitle) {
    document.getElementById('homeInquiryServiceTitle').textContent = title;
    document.getElementById('homeInquiryServiceSubtitle').textContent = subtitle;
    const modal = document.getElementById('homeServiceInquiryModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeHomeServiceInquiry() {
    const modal = document.getElementById('homeServiceInquiryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
function handleHomeInquirySubmit(e) {
    e.preventDefault();
    closeHomeServiceInquiry();
    Swal.fire({
        icon: 'success',
        title: 'Request Received!',
        text: 'Our team will contact you shortly regarding your service requirements.',
        confirmButtonColor: '#0284c7'
    });
}
</script>
<script>
$(document).ready(function() {
    // Hero Banner Auto-Scrolling Carousel (Smooth Right-to-Left Sliding)
    const track = document.getElementById('heroTrack');
    const heroSlides = $('.hero-slide');
    const heroDots = $('.hero-dot');
    const totalRealSlides = heroSlides.length;
    let trackIndex = 0;
    let heroAutoTimer = null;
    let isAnimating = false;

    if (track && totalRealSlides > 1) {
        // Clone the first slide and append to track for infinite smooth loop
        const firstClone = heroSlides.first().clone(true);
        $(track).append(firstClone);

        function updateDots(activeIdx) {
            heroDots.each(function(i) {
                if (i === activeIdx) {
                    $(this).removeClass('bg-white/50 w-2').addClass('bg-white w-5');
                } else {
                    $(this).removeClass('bg-white w-5').addClass('bg-white/50 w-2');
                }
            });
        }

        function moveToSlide(targetIndex, withAnimation = true) {
            if (isAnimating && withAnimation) return;

            if (withAnimation) {
                isAnimating = true;
                track.style.transition = 'transform 650ms cubic-bezier(0.25, 1, 0.5, 1)';
            } else {
                track.style.transition = 'none';
            }

            trackIndex = targetIndex;
            track.style.transform = `translateX(-${trackIndex * 100}%)`;

            // Active dot
            const activeDot = trackIndex >= totalRealSlides ? 0 : trackIndex;
            updateDots(activeDot);

            if (withAnimation) {
                setTimeout(() => {
                    isAnimating = false;
                    // When reaching the cloned first slide, jump silently to real slide 0
                    if (trackIndex >= totalRealSlides) {
                        track.style.transition = 'none';
                        trackIndex = 0;
                        track.style.transform = 'translateX(0%)';
                    }
                }, 650);
            }
        }

        function nextSlide() {
            moveToSlide(trackIndex + 1, true);
        }

        function prevSlide() {
            if (isAnimating) return;
            if (trackIndex === 0) {
                // Instantly snap to the clone at the end, then slide back to the last real slide
                track.style.transition = 'none';
                trackIndex = totalRealSlides;
                track.style.transform = `translateX(-${trackIndex * 100}%)`;
                void track.offsetHeight; // Force reflow
                moveToSlide(totalRealSlides - 1, true);
            } else {
                moveToSlide(trackIndex - 1, true);
            }
        }

        function startHeroTimer() {
            if (!heroAutoTimer) {
                heroAutoTimer = setInterval(nextSlide, 3500); // Smoothly moves right to left every 3.5s
            }
        }

        function stopHeroTimer() {
            if (heroAutoTimer) {
                clearInterval(heroAutoTimer);
                heroAutoTimer = null;
            }
        }

        startHeroTimer();

        // Pause on hover
        $('#heroBannerCarousel').on('mouseenter', stopHeroTimer).on('mouseleave', startHeroTimer);

        // Controls
        $('#heroPrevBtn').on('click', function(e) {
            e.preventDefault();
            stopHeroTimer();
            prevSlide();
            startHeroTimer();
        });

        $('#heroNextBtn').on('click', function(e) {
            e.preventDefault();
            stopHeroTimer();
            nextSlide();
            startHeroTimer();
        });

        // Click on dots
        heroDots.on('click', function() {
            const idx = parseInt($(this).data('index'));
            stopHeroTimer();
            moveToSlide(idx, true);
            startHeroTimer();
        });

        // Touch swipe support for mobile
        let startX = 0;
        let startY = 0;
        const carouselEl = document.getElementById('heroBannerCarousel');
        if (carouselEl) {
            carouselEl.addEventListener('touchstart', function(e) {
                startX = e.changedTouches[0].screenX;
                startY = e.changedTouches[0].screenY;
                stopHeroTimer();
            }, { passive: true });

            carouselEl.addEventListener('touchend', function(e) {
                const endX = e.changedTouches[0].screenX;
                const endY = e.changedTouches[0].screenY;
                const diffX = startX - endX;
                const diffY = startY - endY;

                // Ensure it's predominantly a horizontal swipe
                if (Math.abs(diffX) > Math.abs(diffY)) {
                    if (diffX > 40) {
                        nextSlide(); // Swipe left -> advance slide right-to-left
                    } else if (diffX < -40) {
                        prevSlide(); // Swipe right -> previous slide
                    }
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
                timer: 1800
            });
        }
    });

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

