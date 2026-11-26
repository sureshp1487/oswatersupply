@extends('layouts.customer')

@section('title', 'Water Products & Cans - ' . ($appSettings['business_name'] ?? 'HydroPro'))

@section('content')
<div class="space-y-5 sm:space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-br from-sky-600 via-ocean-600 to-indigo-700 rounded-3xl p-5 sm:p-7 text-white shadow-xl shadow-sky-600/20 relative overflow-hidden">
        <div class="relative z-10 space-y-1.5 max-w-xl">
            <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-white/20 backdrop-blur">
                <i class="fa-solid fa-droplet text-sky-200"></i>
                <span>100% Certified RO + UV</span>
            </span>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight leading-tight">
                Our Drinking Water Products
            </h1>
            <p class="text-xs sm:text-sm text-sky-100 leading-relaxed font-medium">
                Choose from premium sanitized 20L exchange cans, tabletop dispensers, and accessories delivered straight to your door.
            </p>
        </div>
        <!-- Decorative water rings -->
        <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute right-20 -top-10 w-28 h-28 rounded-full bg-white/5 pointer-events-none"></div>
    </div>

    <!-- Search & Category Filters -->
    <div class="space-y-3">
        <!-- Live Search Input -->
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
            <input type="text" id="productSearchInput" onkeyup="filterProducts()" placeholder="Search 20L cans, dispensers, tabletop jars..." class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-white border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500 shadow-xs transition">
        </div>

        <!-- Category Pills -->
        @if(isset($categories) && $categories->count() > 0)
        <div class="flex items-center space-x-2 overflow-x-auto pb-1 scrollbar-none select-none" id="categoryFilterBar">
            <button type="button" onclick="selectCategory('all', this)" class="category-pill active shrink-0 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-sky-500 text-white shadow-sm transition active:scale-95">
                All Products ({{ $products->count() }})
            </button>
            @foreach($categories as $cat)
            <button type="button" onclick="selectCategory('{{ $cat->id }}', this)" class="category-pill shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition active:scale-95">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Products Grid (2 Cols on Mobile, 3 on Tablet, 4 on Desktop) -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5 sm:gap-4" id="productsGrid">
        @forelse($products as $product)
        <div class="product-card bg-white rounded-2xl sm:rounded-3xl p-3 sm:p-4 border border-slate-100 shadow-sm flex flex-col justify-between group hover:shadow-md transition" 
             data-id="{{ $product->id }}" 
             data-name="{{ strtolower($product->name) }}"
             data-desc="{{ strtolower($product->description ?? '') }}"
             data-category="{{ $product->category_id }}"
             data-price="{{ $product->selling_price }}">
            <div>
                <!-- Product Image -->
                <div class="w-full h-32 sm:h-44 md:h-48 rounded-xl sm:rounded-2xl bg-slate-50 overflow-hidden relative mb-2.5 sm:mb-3">
                    <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=500' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    
                    @if($product->volume)
                    <span class="absolute top-2 left-2 bg-slate-900/80 backdrop-blur text-white font-extrabold text-[9px] sm:text-[10px] px-2 py-0.5 rounded-md sm:rounded-lg shadow-sm">
                        {{ $product->volume }} {{ $product->unit }}
                    </span>
                    @endif

                    @if($product->volume == '20')
                    <span class="absolute top-2 right-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-[8px] sm:text-[9px] uppercase px-1.5 sm:px-2 py-0.5 rounded-md shadow-sm">
                        Popular
                    </span>
                    @endif
                </div>

                <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug line-clamp-2 group-hover:text-ocean-600 transition-colors">
                    {{ $product->name }}
                </h4>
                @if($product->description)
                <p class="text-[11px] sm:text-xs text-slate-400 line-clamp-1 sm:line-clamp-2 mt-0.5 sm:mt-1">
                    {{ $product->description }}
                </p>
                @endif
            </div>

            <!-- Pricing, Quantity Selector & Action Buttons -->
            <div class="mt-3 sm:mt-4 pt-2.5 sm:pt-3 border-t border-slate-100 space-y-2 sm:space-y-3">
                <!-- Price and 1:1 Exchange badge -->
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-baseline space-x-1">
                            <span class="text-base sm:text-lg font-black text-slate-900">₹{{ number_format($product->selling_price, 2) }}</span>
                        </div>
                        <span class="text-[9px] sm:text-[10px] text-slate-400 block -mt-0.5">Incl. all taxes</span>
                    </div>
                    <span class="px-1.5 sm:px-2 py-0.5 rounded-md sm:rounded-lg border border-sky-200 bg-sky-50 text-sky-700 text-[9px] sm:text-[10px] font-bold">
                        1:1 Exch.
                    </span>
                </div>

                <!-- Quantity Row -->
                <div class="flex items-center justify-between text-xs text-slate-600">
                    <span class="font-bold text-slate-500 text-[10px] sm:text-[11px]">Qty:</span>
                    <div class="flex items-center border border-slate-200 rounded-full px-1.5 sm:px-2 py-0.5 bg-slate-50/50 space-x-2 sm:space-x-3">
                        <button type="button" class="btn-qty-minus text-slate-400 hover:text-slate-800 text-xs px-1 font-extrabold active:scale-95 transition">
                            <i class="fa-solid fa-minus text-[9px] sm:text-[10px]"></i>
                        </button>
                        <span class="qty-display font-black text-slate-800 text-[11px] sm:text-xs w-3 sm:w-4 text-center">1</span>
                        <button type="button" class="btn-qty-plus text-slate-400 hover:text-slate-800 text-xs px-1 font-extrabold active:scale-95 transition">
                            <i class="fa-solid fa-plus text-[9px] sm:text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons: Add Cart & Buy Now -->
                <div class="grid grid-cols-2 gap-1.5 sm:gap-2 pt-1">
                    <!-- Add Cart Button -->
                    <button type="button" class="btn-add-cart w-full py-1.5 sm:py-2 px-1 rounded-xl border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-700 font-extrabold text-[10px] sm:text-[11px] flex items-center justify-center space-x-1 transition active:scale-95 shadow-sm">
                        <i class="fa-solid fa-bag-shopping text-[10px] sm:text-xs"></i>
                        <span>Add Cart</span>
                    </button>

                    <!-- Buy Now Button -->
                    <button type="button" class="btn-buy-now w-full py-1.5 sm:py-2 px-1 rounded-xl bg-gradient-to-r from-sky-500 to-ocean-600 hover:from-sky-600 hover:to-ocean-700 text-white font-extrabold text-[10px] sm:text-[11px] flex items-center justify-center space-x-1 shadow-md shadow-sky-600/20 transition active:scale-95">
                        <span>Buy Now</span>
                        <i class="fa-solid fa-arrow-right text-[8px] sm:text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400">
            <i class="fa-solid fa-bottle-water text-3xl text-slate-300 mb-2 block"></i>
            <p class="text-sm font-semibold">No products available at the moment.</p>
        </div>
        @endforelse
    </div>

    <!-- No Search Results Found Message -->
    <div id="noProductsFound" class="hidden py-12 text-center text-slate-400">
        <i class="fa-solid fa-magnifying-glass text-3xl text-slate-300 mb-2 block"></i>
        <p class="text-sm font-bold text-slate-700">No matching water cans or products found</p>
        <p class="text-xs text-slate-400 mt-1">Try searching with a different keyword</p>
        <button type="button" onclick="resetFilters()" class="mt-3 px-4 py-1.5 rounded-xl bg-sky-50 text-sky-700 font-bold text-xs hover:bg-sky-100 transition">
            Reset Filters
        </button>
    </div>

</div>

@push('scripts')
<script>
    let activeCategoryId = 'all';

    function selectCategory(catId, btn) {
        activeCategoryId = catId;
        document.querySelectorAll('.category-pill').forEach(p => {
            p.classList.remove('active', 'bg-sky-500', 'text-white');
            p.classList.add('bg-white', 'text-slate-600');
        });
        btn.classList.add('active', 'bg-sky-500', 'text-white');
        btn.classList.remove('bg-white', 'text-slate-600');
        filterProducts();
    }

    function filterProducts() {
        const query = (document.getElementById('productSearchInput')?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('#productsGrid .product-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const desc = card.getAttribute('data-desc') || '';
            const category = card.getAttribute('data-category') || '';

            const matchesCategory = (activeCategoryId === 'all') || (category === activeCategoryId);
            const matchesQuery = !query || name.includes(query) || desc.includes(query);

            if (matchesCategory && matchesQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noFound = document.getElementById('noProductsFound');
        if (noFound) {
            noFound.classList.toggle('hidden', visibleCount > 0);
        }
    }

    function resetFilters() {
        const searchInput = document.getElementById('productSearchInput');
        if (searchInput) searchInput.value = '';
        const allBtn = document.querySelector('.category-pill');
        if (allBtn) selectCategory('all', allBtn);
    }

    $(document).ready(function() {
        // Quantity Plus
        $(document).on('click', '.btn-qty-plus', function() {
            const card = $(this).closest('.product-card');
            const display = card.find('.qty-display');
            let current = parseInt(display.text()) || 1;
            if (current < 99) {
                display.text(current + 1);
            }
        });

        // Quantity Minus
        $(document).on('click', '.btn-qty-minus', function() {
            const card = $(this).closest('.product-card');
            const display = card.find('.qty-display');
            let current = parseInt(display.text()) || 1;
            if (current > 1) {
                display.text(current - 1);
            }
        });

        // Add to Cart handler
        $(document).on('click', '.btn-add-cart', function() {
            const card = $(this).closest('.product-card');
            const prodId = card.data('id');
            const qty = parseInt(card.find('.qty-display').text()) || 1;
            const prodName = card.find('h4').text().trim();
            const prodImg = card.find('img').attr('src');
            const prodPrice = parseFloat(card.data('price')) || 0;

            if (window.CartManager) {
                const existingQty = window.CartManager.getItemQty(prodId);
                window.CartManager.setItemQty(prodId, existingQty + qty);

                // Feedback button state
                const btn = $(this);
                const originalHtml = btn.html();
                btn.addClass('bg-emerald-50 text-emerald-700 border-emerald-300').html('<i class="fa-solid fa-check text-xs"></i> <span>Added!</span>');
                setTimeout(() => {
                    btn.removeClass('bg-emerald-50 text-emerald-700 border-emerald-300').html(originalHtml);
                }, 1200);

                if (window.CartManager.showCartPopup) {
                    window.CartManager.showCartPopup({
                        name: prodName,
                        img: prodImg,
                        subtitle: `<span class="text-sky-600 font-bold">+${qty} Added</span> &bull; ₹${(prodPrice * qty).toFixed(2)}`,
                        timer: 1800
                    });
                }
            }
        });

        // Buy Now handler
        $(document).on('click', '.btn-buy-now', function() {
            const card = $(this).closest('.product-card');
            const prodId = card.data('id');
            const qty = parseInt(card.find('.qty-display').text()) || 1;

            if (window.CartManager) {
                window.CartManager.setItemQty(prodId, qty);
                window.location.href = "{{ route('customer.cart') }}?product_id=" + prodId + "&qty=" + qty;
            }
        });
    });
</script>
@endpush
@endsection
