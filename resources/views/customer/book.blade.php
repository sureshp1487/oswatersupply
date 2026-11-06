@extends('layouts.customer')

@section('title', 'My Cart & Checkout - ' . ($appSettings['business_name'] ?? 'AquaFlow'))

@section('content')
<div class="space-y-6">

    <!-- Header title -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center space-x-2.5">
                <i class="fa-solid fa-cart-shopping text-ocean-600"></i>
                <span>My Cart & Checkout</span>
            </h2>
            <p class="text-xs text-slate-500">Review selected water cans, select delivery address, schedule time, and confirm order</p>
        </div>
        <a href="{{ route('customer.home') }}" class="text-xs font-bold text-ocean-600 hover:underline flex items-center space-x-1">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Continue Shopping</span>
        </a>
    </div>

    <!-- Booking Form Container (2-Column Grid on Large Devices) -->
    <form id="orderBookingForm" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <!-- Left 2 Cols: Cart Items & Delivery Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. Cart Items (Only products added to cart are shown here) -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-full bg-ocean-100 text-ocean-700 flex items-center justify-center text-xs font-black">1</span>
                        <span>Items in Cart</span>
                    </h3>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-ocean-600 font-bold" id="totalItemsCountLabel">0 items</span>
                        <button type="button" id="btnClearCart" class="text-[11px] text-rose-500 hover:text-rose-700 font-bold hidden">
                            Clear Cart
                        </button>
                    </div>
                </div>

                <!-- Empty Cart State -->
                <div id="cartEmptyState" class="hidden py-10 text-center space-y-3">
                    <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mx-auto text-2xl shadow-inner">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-800">Your Cart is Empty</h4>
                        <p class="text-xs text-slate-400 mt-0.5">You haven't added any water cans yet.</p>
                    </div>
                    <a href="{{ route('customer.home') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-ocean-600 text-white rounded-xl text-xs font-bold shadow hover:bg-ocean-700 transition">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Browse Available Cans</span>
                    </a>
                </div>

                <!-- Cart Products Container -->
                <div id="cartProductsContainer" class="space-y-3">
                    @foreach($products as $prod)
                    <div class="p-3.5 rounded-2xl border border-slate-200/90 product-row bg-slate-50/40 hover:bg-slate-50/80 transition flex items-center justify-between gap-3 hidden" 
                         data-id="{{ $prod->id }}" 
                         data-price="{{ $prod->selling_price }}"
                         data-name="{{ $prod->name }}">
                        
                        <div class="flex items-center space-x-3 min-w-0">
                            <img src="{{ $prod->image ?: 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=500' }}" class="w-14 h-14 rounded-xl object-cover shrink-0 border border-slate-100">
                            <div class="truncate">
                                <h4 class="text-xs font-extrabold text-slate-900 truncate">{{ $prod->name }}</h4>
                                <div class="flex items-center space-x-2 mt-0.5">
                                    <span class="text-xs font-black text-ocean-700">₹{{ number_format($prod->selling_price, 2) }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">({{ $prod->volume }} {{ $prod->unit }})</span>
                                    <span class="text-[10px] px-1.5 py-0.2 rounded bg-sky-50 text-sky-600 font-bold border border-sky-100">1:1 Exchange</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stepper and Line Total -->
                        <div class="flex items-center space-x-3 shrink-0">
                            <!-- Stepper -->
                            <div class="flex items-center space-x-2 bg-white rounded-xl p-1 border border-slate-200 shadow-sm">
                                <button type="button" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center shadow-sm text-xs btn-qty-minus active:scale-95 transition">
                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                </button>
                                <span class="w-7 text-center text-xs font-black qty-input text-slate-800">0</span>
                                <button type="button" class="w-7 h-7 rounded-lg bg-ocean-600 hover:bg-ocean-700 text-white flex items-center justify-center shadow-sm text-xs btn-qty-plus active:scale-95 transition">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </button>
                            </div>

                            <!-- Remove item button -->
                            <button type="button" class="btn-remove-item text-slate-300 hover:text-rose-600 p-2 rounded-xl hover:bg-rose-50 transition" title="Remove from cart">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. Delivery Address Selection -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-full bg-ocean-100 text-ocean-700 flex items-center justify-center text-xs font-black">2</span>
                        <span>Delivery Address</span>
                    </h3>
                    <button type="button" id="btnAddNewAddress" class="text-xs text-ocean-600 font-bold hover:underline">
                        + Add Address
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="addressListContainer">
                    @forelse($addresses as $addr)
                    <label class="block cursor-pointer">
                        <input type="radio" name="address_id" value="{{ $addr->id }}" class="peer hidden" {{ $addr->is_default ? 'checked' : ($loop->first ? 'checked' : '') }}>
                        <div class="p-3.5 rounded-2xl border border-slate-200 peer-checked:border-ocean-600 peer-checked:bg-sky-50/50 transition h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900 flex items-center space-x-1.5">
                                        <i class="fa-solid fa-house-chimney text-ocean-600 text-xs"></i>
                                        <span>{{ $addr->label }}</span>
                                    </span>
                                    <span class="text-[10px] text-ocean-700 font-bold peer-checked:inline hidden bg-ocean-100 px-2 py-0.5 rounded-full">Selected</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1.5 leading-snug">{{ $addr->formatted_address }}</p>
                            </div>
                        </div>
                    </label>
                    @empty
                    <div class="col-span-2 text-center py-6 bg-slate-50 rounded-2xl">
                        <p class="text-xs text-slate-500 mb-2">No saved address found</p>
                        <button type="button" id="btnEmptyAddAddress" class="text-xs font-bold text-white bg-ocean-600 px-4 py-2 rounded-xl shadow">Add Address</button>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- 3. Future Delivery Schedule & Slot Selection -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-full bg-ocean-100 text-ocean-700 flex items-center justify-center text-xs font-black">3</span>
                        <span>Delivery Date & Slot</span>
                    </h3>
                </div>

                <!-- Date Picker -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Select Expected Delivery Date</label>
                    <input type="date" id="expected_delivery_date" name="expected_delivery_date"
                           min="{{ \Carbon\Carbon::today()->toDateString() }}"
                           value="{{ \Carbon\Carbon::tomorrow()->toDateString() }}"
                           class="w-full sm:w-72 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-ocean-500">
                    <span class="text-[10px] text-slate-400 mt-1 block">Past dates are automatically disabled; select today or any future date</span>
                </div>

                <!-- Time Slots Grid -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Available Time Slot</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        @foreach($slots as $slot)
                        <label class="block cursor-pointer">
                            <input type="radio" name="delivery_slot_id" value="{{ $slot->id }}" class="peer hidden" {{ $loop->first ? 'checked' : '' }}>
                            <div class="p-3 rounded-2xl border border-slate-200 peer-checked:border-ocean-600 peer-checked:bg-sky-50/50 flex items-center justify-between text-xs transition">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-regular fa-clock text-ocean-600"></i>
                                    <span class="font-bold text-slate-800">{{ $slot->name }}</span>
                                </div>
                                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $slot->max_daily_capacity }}/day</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Delivery Instructions -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Special Delivery Instructions (Optional)</label>
                    <input type="text" name="delivery_instructions" placeholder="e.g. Leave near doorway, call security on arrival" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-ocean-500">
                </div>
            </div>

            <!-- 4. Payment Method Selection (Razorpay, COD, Pay Later) -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-full bg-ocean-100 text-ocean-700 flex items-center justify-center text-xs font-black">4</span>
                        <span>Payment Method</span>
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Razorpay Online -->
                    <label class="block cursor-pointer">
                        <input type="radio" name="payment_method" value="razorpay" class="peer hidden" checked>
                        <div class="p-4 rounded-2xl border border-slate-200 peer-checked:border-ocean-600 peer-checked:bg-sky-50/50 flex flex-col justify-between transition h-full">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm shrink-0">
                                    <i class="fa-solid fa-credit-card"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-slate-900 block">Razorpay Online</span>
                                    <span class="text-[10px] text-slate-500">UPI, Cards, NetBanking</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <i class="fa-solid fa-circle-check text-ocean-600 peer-checked:opacity-100 opacity-0 transition text-sm"></i>
                            </div>
                        </div>
                    </label>

                    <!-- Pay Later / Outstanding Due -->
                    <label class="block cursor-pointer">
                        <input type="radio" name="payment_method" value="pay_later" class="peer hidden">
                        <div class="p-4 rounded-2xl border border-slate-200 peer-checked:border-ocean-600 peer-checked:bg-sky-50/50 flex flex-col justify-between transition h-full">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm shrink-0">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </div>
                                <div>
                                    <div class="flex items-center space-x-1.5">
                                        <span class="text-xs font-extrabold text-slate-900">Pay Later</span>
                                        <span class="bg-amber-100 text-amber-800 text-[9px] font-bold px-1.5 py-0.2 rounded">Due</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500">Add to customer balance</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <i class="fa-solid fa-circle-check text-ocean-600 peer-checked:opacity-100 opacity-0 transition text-sm"></i>
                            </div>
                        </div>
                    </label>

                    <!-- Cash On Delivery (COD) -->
                    <label class="block cursor-pointer">
                        <input type="radio" name="payment_method" value="cod" class="peer hidden">
                        <div class="p-4 rounded-2xl border border-slate-200 peer-checked:border-ocean-600 peer-checked:bg-sky-50/50 flex flex-col justify-between transition h-full">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm shrink-0">
                                    <i class="fa-solid fa-hand-holding-dollar"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-slate-900 block">Cash on Delivery</span>
                                    <span class="text-[10px] text-slate-500">Pay captain at doorstep</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <i class="fa-solid fa-circle-check text-ocean-600 peer-checked:opacity-100 opacity-0 transition text-sm"></i>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Sticky Order Summary & Submit -->
        <div class="space-y-4 mb-24 md:mb-0">
            <div class="bg-slate-900 rounded-3xl p-6 text-white shadow-xl space-y-4 lg:sticky lg:top-24">
                <h4 class="text-xs uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-800 pb-2">Order Summary</h4>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Items Subtotal</span>
                        <span id="summarySubtotal" class="font-bold">₹0.00</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Delivery Fee</span>
                        <span class="text-emerald-400 font-bold">FREE</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Taxes & Cess</span>
                        <span class="text-slate-400">Included</span>
                    </div>
                    <div class="flex items-center justify-between text-lg font-black text-white pt-3 border-t border-slate-800">
                        <span>Total Payable</span>
                        <span id="summaryTotal" class="text-sky-400">₹0.00</span>
                    </div>
                </div>

                <!-- Confirm Order Button -->
                <button type="submit" id="btnSubmitOrder" disabled class="w-full py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 disabled:bg-slate-800 disabled:text-slate-600 text-white font-extrabold text-xs shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center space-x-2 active:scale-95">
                    <span>Confirm & Book Delivery</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
            <!-- Extra mobile space to guarantee full clearance above bottom menu -->
            <div class="h-16 md:hidden"></div>
        </div>

    </form>
</div>

<!-- Modal: Add Address -->
<div id="addAddressModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">Add Delivery Address</h3>
            <button type="button" id="closeAddressModal" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="newAddressForm" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Address Label</label>
                <select name="label" class="w-full px-3 py-2 border rounded-xl text-xs">
                    <option value="Home">Home</option>
                    <option value="Office">Office</option>
                    <option value="Apartment">Apartment</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Door / Flat No.</label>
                    <input type="text" name="door_number" required placeholder="Flat 4B" class="w-full px-3 py-2 border rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Building Name</label>
                    <input type="text" name="house_or_building" required placeholder="Skyline Towers" class="w-full px-3 py-2 border rounded-xl text-xs">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Street / Road</label>
                <input type="text" name="street" required placeholder="2nd Cross Street" class="w-full px-3 py-2 border rounded-xl text-xs">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Locality / Area</label>
                    <input type="text" name="area_locality" required placeholder="Indiranagar" class="w-full px-3 py-2 border rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pincode</label>
                    <input type="text" name="pincode" required placeholder="600040" maxlength="6" class="w-full px-3 py-2 border rounded-xl text-xs">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">City</label>
                <input type="text" name="city" value="Chennai" required class="w-full px-3 py-2 border rounded-xl text-xs">
            </div>
            <button type="submit" class="w-full py-3 bg-ocean-600 text-white rounded-xl text-xs font-bold hover:bg-ocean-700 shadow">Save Address</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Read cart contents from local CartManager and URL query
    const urlParams = new URLSearchParams(window.location.search);
    const preSelectedProdId = urlParams.get('product_id');
    const preSelectedQty = parseInt(urlParams.get('qty')) || 1;

    let cartData = window.CartManager ? window.CartManager.getCart() : {};

    // If query string has a product (e.g. from Buy Now), set or add it
    if (preSelectedProdId) {
        cartData[preSelectedProdId] = preSelectedQty;
        if (window.CartManager) {
            window.CartManager.setItemQty(preSelectedProdId, preSelectedQty);
        }
    }

    renderCartView();

    function renderCartView() {
        let visibleCount = 0;

        $('.product-row').each(function() {
            const prodId = String($(this).data('id'));
            const qty = parseInt(cartData[prodId]) || 0;

            if (qty > 0) {
                $(this).find('.qty-input').text(qty);
                $(this).removeClass('hidden');
                visibleCount++;
            } else {
                $(this).find('.qty-input').text(0);
                $(this).addClass('hidden');
            }
        });

        if (visibleCount === 0) {
            $('#cartEmptyState').removeClass('hidden');
            $('#btnClearCart').addClass('hidden');
        } else {
            $('#cartEmptyState').addClass('hidden');
            $('#btnClearCart').removeClass('hidden');
        }

        updateCartCalculations();
    }

    // Plus quantity
    $(document).on('click', '.btn-qty-plus', function() {
        const row = $(this).closest('.product-row');
        const prodId = String(row.data('id'));
        const qtySpan = $(this).siblings('.qty-input');
        let current = parseInt(qtySpan.text()) || 0;
        const nextQty = current + 1;
        qtySpan.text(nextQty);
        cartData[prodId] = nextQty;

        if (window.CartManager) {
            window.CartManager.setItemQty(prodId, nextQty);
        }
        updateCartCalculations();
    });

    // Minus quantity
    $(document).on('click', '.btn-qty-minus', function() {
        const row = $(this).closest('.product-row');
        const prodId = String(row.data('id'));
        const qtySpan = $(this).siblings('.qty-input');
        let current = parseInt(qtySpan.text()) || 0;

        if (current > 1) {
            const nextQty = current - 1;
            qtySpan.text(nextQty);
            cartData[prodId] = nextQty;

            if (window.CartManager) {
                window.CartManager.setItemQty(prodId, nextQty);
            }
            updateCartCalculations();
        } else {
            // If decreasing from 1, remove item
            removeItemFromCart(row, prodId);
        }
    });

    // Remove single item button
    $(document).on('click', '.btn-remove-item', function() {
        const row = $(this).closest('.product-row');
        const prodId = String(row.data('id'));
        removeItemFromCart(row, prodId);
    });

    function removeItemFromCart(row, prodId) {
        delete cartData[prodId];
        if (window.CartManager) {
            window.CartManager.setItemQty(prodId, 0);
        }
        row.addClass('hidden');
        row.find('.qty-input').text(0);
        renderCartView();
    }

    // Clear entire cart
    $('#btnClearCart').on('click', function() {
        cartData = {};
        if (window.CartManager) {
            window.CartManager.saveCart({});
        }
        renderCartView();
    });

    function updateCartCalculations() {
        let subtotal = 0;
        let totalCount = 0;

        $('.product-row').each(function() {
            if (!$(this).hasClass('hidden')) {
                const price = parseFloat($(this).data('price')) || 0;
                const qty = parseInt($(this).find('.qty-input').text()) || 0;

                if (qty > 0) {
                    subtotal += (price * qty);
                    totalCount += qty;
                }
            }
        });

        $('#totalItemsCountLabel').text(`${totalCount} items in cart`);
        $('#summarySubtotal').text(`₹${subtotal.toFixed(2)}`);
        $('#summaryTotal').text(`₹${subtotal.toFixed(2)}`);

        if (totalCount > 0) {
            $('#btnSubmitOrder').removeAttr('disabled');
        } else {
            $('#btnSubmitOrder').attr('disabled', 'disabled');
        }
    }

    // Modal Add Address toggles
    $('#btnAddNewAddress, #btnEmptyAddAddress').on('click', function() {
        $('#addAddressModal').removeClass('hidden').addClass('flex');
    });

    $('#closeAddressModal').on('click', function() {
        $('#addAddressModal').removeClass('flex').addClass('hidden');
    });

    // Save Address via AJAX
    $('#newAddressForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.post("{{ route('customer.address.save') }}", formData)
            .done(function(res) {
                $('#addAddressModal').removeClass('flex').addClass('hidden');
                Swal.fire({
                    icon: 'success',
                    title: 'Address Saved',
                    text: 'Your new address was added.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            })
            .fail(function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed saving address', 'error');
            });
    });

    // Order Submit Handler
    $('#orderBookingForm').on('submit', function(e) {
        e.preventDefault();

        const selectedAddress = $('input[name="address_id"]:checked').val();
        if (!selectedAddress) {
            Swal.fire('Address Missing', 'Please select or add a delivery address.', 'warning');
            return;
        }

        const items = [];
        $('.product-row').each(function() {
            if (!$(this).hasClass('hidden')) {
                const prodId = $(this).data('id');
                const qty = parseInt($(this).find('.qty-input').text()) || 0;
                if (qty > 0) {
                    items.push({ product_id: prodId, quantity: qty });
                }
            }
        });

        if (items.length === 0) {
            Swal.fire('Cart Empty', 'Please add at least 1 water can to your cart.', 'warning');
            return;
        }

        const paymentMethod = $('input[name="payment_method"]:checked').val();
        const expectedDate = $('#expected_delivery_date').val();
        const slotId = $('input[name="delivery_slot_id"]:checked').val();
        const instructions = $('input[name="delivery_instructions"]').val();

        // If Razorpay, initiate online checkout flow
        if (paymentMethod === 'razorpay') {
            initiateRazorpayOrder(items, selectedAddress, expectedDate, slotId, instructions);
        } else {
            // Direct submission for COD or Pay Later
            submitOrderPayload({
                items: items,
                address_id: selectedAddress,
                expected_delivery_date: expectedDate,
                delivery_slot_id: slotId,
                payment_method: paymentMethod,
                delivery_instructions: instructions
            });
        }
    });

    function submitOrderPayload(payload) {
        $('#btnSubmitOrder').attr('disabled', 'disabled').html('<i class="fa-solid fa-spinner fa-spin"></i> Processing Order...');

        $.ajax({
            url: "{{ route('customer.order.place') }}",
            type: "POST",
            data: JSON.stringify(payload),
            contentType: "application/json",
            dataType: "json",
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Booking Confirmed!',
                    text: res.message,
                    confirmButtonColor: '#0284c7'
                }).then(() => {
                    if (window.CartManager) {
                        window.CartManager.saveCart({});
                    }
                    window.location.href = res.redirect_url;
                });
            },
            error: function(xhr) {
                $('#btnSubmitOrder').removeAttr('disabled').html('<span>Confirm & Book Delivery</span> <i class="fa-solid fa-arrow-right text-xs"></i>');
                Swal.fire('Order Failed', xhr.responseJSON?.message || 'Something went wrong.', 'error');
            }
        });
    }

    function initiateRazorpayOrder(items, addressId, expectedDate, slotId, instructions) {
        let total = 0;
        $('.product-row').each(function() {
            if (!$(this).hasClass('hidden')) {
                const price = parseFloat($(this).data('price')) || 0;
                const qty = parseInt($(this).find('.qty-input').text()) || 0;
                total += (price * qty);
            }
        });

        $('#btnSubmitOrder').attr('disabled', 'disabled').html('<i class="fa-solid fa-spinner fa-spin"></i> Connecting Payment Gateway...');

        $.post("{{ route('customer.razorpay.order') }}", {
            amount: total,
            receipt: 'ORD_' + Date.now()
        }).done(function(orderData) {
            const options = {
                "key": "{{ env('RAZORPAY_KEY_ID', 'rzp_test_waterdelivery101') }}",
                "amount": orderData.amount,
                "currency": "INR",
                "name": "AquaFlow Water Delivery",
                "description": "Payment for Scheduled Water Delivery",
                "order_id": orderData.id,
                "handler": function (response){
                    submitOrderPayload({
                        items: items,
                        address_id: addressId,
                        expected_delivery_date: expectedDate,
                        delivery_slot_id: slotId,
                        payment_method: 'razorpay',
                        delivery_instructions: instructions,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature
                    });
                },
                "prefill": {
                    "name": "{{ auth()->user()->name }}",
                    "email": "{{ auth()->user()->email ?? 'customer@aquaflow.com' }}",
                    "contact": "{{ auth()->user()->phone }}"
                },
                "theme": {
                    "color": "#0284c7"
                },
                "modal": {
                    "ondismiss": function() {
                        $('#btnSubmitOrder').removeAttr('disabled').html('<span>Confirm & Book Delivery</span> <i class="fa-solid fa-arrow-right text-xs"></i>');
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        }).fail(function() {
            $('#btnSubmitOrder').removeAttr('disabled').html('<span>Confirm & Book Delivery</span> <i class="fa-solid fa-arrow-right text-xs"></i>');
            Swal.fire('Error', 'Unable to initiate online payment session.', 'error');
        });
    }
});
</script>
@endpush
@endsection
