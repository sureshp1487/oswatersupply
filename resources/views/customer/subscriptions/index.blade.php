@extends('layouts.customer')

@section('title', 'Water Delivery Subscriptions - ' . ($appSettings['business_name'] ?? 'AquaFlow'))

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-ocean-700 via-sky-600 to-cyan-600 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 max-w-2xl space-y-2">
            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-white/20 text-white text-[10px] font-extrabold uppercase tracking-widest backdrop-blur-sm border border-white/20">
                <i class="fa-solid fa-repeat text-xs"></i>
                <span>Automated Refill Plans</span>
            </span>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight">
                Never Run Out of Fresh Drinking Water
            </h2>
            <p class="text-xs sm:text-sm text-sky-100 font-medium leading-relaxed">
                Choose a hassle-free recurring subscription plan tailored for your home or office. Enjoy automatic scheduled deliveries, priority slots, and savings up to 15% OFF.
            </p>
        </div>
    </div>

    <!-- Tab Buttons -->
    <div class="flex items-center space-x-2 border-b border-slate-200 pb-1">
        <button type="button" id="tabBtnPlans" onclick="switchSubTab('plans')" class="px-4 py-2.5 rounded-2xl text-xs font-black transition flex items-center space-x-2 bg-ocean-600 text-white shadow-md shadow-ocean-600/20">
            <i class="fa-solid fa-layer-group"></i>
            <span>Browse Plans (3)</span>
        </button>
        <button type="button" id="tabBtnMySubs" onclick="switchSubTab('mysubs')" class="px-4 py-2.5 rounded-2xl text-xs font-black transition flex items-center space-x-2 bg-slate-100 text-slate-700 hover:bg-slate-200">
            <i class="fa-solid fa-calendar-check"></i>
            <span>My Subscriptions ({{ $mySubscriptions->count() }})</span>
        </button>
    </div>

    <!-- TAB 1: Subscription Plans Grid -->
    <div id="tabContentPlans" class="space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($plans as $plan)
            <div class="bg-white rounded-3xl p-6 border {{ $plan->slug === 'monthly' ? 'border-ocean-500 ring-2 ring-ocean-100 shadow-lg' : 'border-slate-100 shadow-sm' }} flex flex-col justify-between relative group hover:shadow-md transition">
                <div>
                    <!-- Badge -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $plan->slug === 'monthly' ? 'bg-ocean-100 text-ocean-700' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $plan->badge ?: ($plan->discount_percentage . '% OFF') }}
                        </span>
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-ocean-600 text-sm">
                            @if($plan->frequency === 'daily')
                                <i class="fa-solid fa-calendar-day"></i>
                            @elseif($plan->frequency === 'alternate_days')
                                <i class="fa-solid fa-arrows-split-up-and-left"></i>
                            @else
                                <i class="fa-solid fa-calendar-week"></i>
                            @endif
                        </div>
                    </div>

                    <!-- Plan Name & Description -->
                    <h3 class="text-lg font-black text-slate-900">{{ $plan->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                        {{ $plan->description }}
                    </p>

                    <!-- Delivery Count Highlight -->
                    <div class="my-4 p-3.5 rounded-2xl bg-slate-50 border border-slate-100/80 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Deliveries / Month</span>
                            <span class="text-lg font-black text-slate-900">{{ $plan->deliveries_count }} Cans</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider block">Direct Savings</span>
                            <span class="text-sm font-extrabold text-emerald-600">{{ (float)$plan->discount_percentage }}% OFF</span>
                        </div>
                    </div>

                    <!-- Plan Features List -->
                    <ul class="space-y-2 text-xs text-slate-600 mb-6">
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-[11px]"></i>
                            <span>Free doorstep delivery included</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-[11px]"></i>
                            <span>Priority morning or evening time slots</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-[11px]"></i>
                            <span>Pause or resume anytime with 1-click</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-[11px]"></i>
                            <span>100% sealed & sanitized 20L cans</span>
                        </li>
                    </ul>
                </div>

                <!-- CTA Button -->
                <button type="button" onclick="openSubscribeModal({{ json_encode($plan) }})" class="w-full py-3 rounded-2xl {{ $plan->slug === 'monthly' ? 'bg-ocean-600 hover:bg-ocean-700 text-white shadow-lg shadow-ocean-600/30' : 'bg-slate-900 hover:bg-slate-800 text-white' }} font-extrabold text-xs transition active:scale-95 flex items-center justify-center space-x-2">
                    <span>Subscribe to {{ explode(' ', $plan->name)[0] }}</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
            </div>
            @endforeach
        </div>

        <!-- Informational Banner -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-3xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-ocean-100 text-ocean-700 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-shield-heart"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Zero Commitment • Full Flexibility</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Going on holiday? Pause deliveries with a single tap in your dashboard and resume automatically when you return.</p>
                </div>
            </div>
            <a href="tel:{{ $appSettings['business_phone'] ?? '+919876543210' }}" class="text-xs font-extrabold text-ocean-600 hover:underline shrink-0">
                Call Support: {{ $appSettings['business_phone'] ?? '+91 98765 43210' }} &rarr;
            </a>
        </div>
    </div>

    <!-- TAB 2: My Active Subscriptions -->
    <div id="tabContentMySubs" class="space-y-4 hidden">
        @if($mySubscriptions->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm space-y-3">
            <div class="w-16 h-16 rounded-full bg-sky-50 text-ocean-600 flex items-center justify-center text-3xl mx-auto">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h4 class="text-base font-bold text-slate-800">No Active Subscriptions</h4>
            <p class="text-xs text-slate-400 max-w-sm mx-auto">
                You don't have any water delivery subscriptions yet. Select from Monthly, Alternate Days, or Weekly Once plans to automate your refills.
            </p>
            <button type="button" onclick="switchSubTab('plans')" class="px-5 py-2.5 rounded-xl bg-ocean-600 text-white font-black text-xs shadow hover:bg-ocean-700 transition">
                View Subscription Plans
            </button>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($mySubscriptions as $sub)
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-sm space-y-4 flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <span class="text-xs font-mono font-black text-slate-400">#{{ $sub->subscription_number }}</span>
                            <h4 class="text-base font-black text-slate-900 mt-0.5">{{ $sub->plan?->name ?? 'Custom Subscription' }}</h4>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                            @if($sub->status === 'active') bg-emerald-100 text-emerald-800
                            @elseif($sub->status === 'paused') bg-amber-100 text-amber-800
                            @elseif($sub->status === 'cancelled') bg-rose-100 text-rose-800
                            @else bg-slate-100 text-slate-700 @endif">
                            {{ $sub->status }}
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="pt-3 space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Deliveries Completed:</span>
                            <span class="font-extrabold text-slate-900">{{ $sub->completed_deliveries }} / {{ $sub->total_deliveries }}</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-ocean-600 to-sky-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $sub->progress_percentage }}%"></div>
                        </div>
                    </div>

                    <!-- Product & Delivery Details -->
                    <div class="my-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-700">
                            <span class="font-medium">Product & Qty:</span>
                            <span class="font-bold text-slate-900">{{ $sub->quantity_per_delivery }}x {{ $sub->product?->name ?? 'Water Can' }}</span>
                        </div>
                        <div class="flex justify-between text-slate-700">
                            <span class="font-medium">Next Scheduled Delivery:</span>
                            <span class="font-bold text-ocean-700">
                                @if($sub->status === 'active' && $sub->next_delivery_date)
                                    <i class="fa-regular fa-calendar-check mr-1"></i>
                                    {{ \Carbon\Carbon::parse($sub->next_delivery_date)->format('D, M d, Y') }}
                                @elseif($sub->status === 'paused')
                                    <span class="text-amber-600 font-bold">On Hold (Paused)</span>
                                @else
                                    <span class="text-slate-400">None</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-slate-700">
                            <span class="font-medium">Delivery Slot:</span>
                            <span class="font-bold text-slate-900">{{ $sub->deliverySlot?->name ?? 'Standard' }}</span>
                        </div>
                        <div class="flex justify-between text-slate-700">
                            <span class="font-medium">Address:</span>
                            <span class="font-bold text-slate-900 truncate max-w-[200px]" title="{{ $sub->address_snapshot['door_number'] ?? '' }} {{ $sub->address_snapshot['street'] ?? '' }}">
                                {{ $sub->address_snapshot['area_locality'] ?? ($sub->address_snapshot['door_number'] ?? 'Saved Address') }}
                            </span>
                        </div>
                    </div>

                    @if($sub->status === 'cancelled' && $sub->cancellation_reason)
                    <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-100 text-rose-800 text-[11px]">
                        <strong>Cancellation Reason:</strong> {{ $sub->cancellation_reason }}
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
                    @if($sub->status === 'active')
                    <button type="button" onclick="pauseSubscription({{ $sub->id }})" class="px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-extrabold border border-amber-200 transition flex items-center space-x-1.5 active:scale-95">
                        <i class="fa-solid fa-pause text-[10px]"></i>
                        <span>Pause</span>
                    </button>
                    <button type="button" onclick="openCancelSubModal({{ $sub->id }}, '{{ $sub->subscription_number }}')" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold border border-rose-200 transition flex items-center space-x-1.5 active:scale-95">
                        <i class="fa-solid fa-ban text-[10px]"></i>
                        <span>Cancel</span>
                    </button>
                    @elseif($sub->status === 'paused')
                    <button type="button" onclick="resumeSubscription({{ $sub->id }})" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow transition flex items-center space-x-1.5 active:scale-95">
                        <i class="fa-solid fa-play text-[10px]"></i>
                        <span>Resume Deliveries</span>
                    </button>
                    <button type="button" onclick="openCancelSubModal({{ $sub->id }}, '{{ $sub->subscription_number }}')" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold border border-rose-200 transition flex items-center space-x-1.5 active:scale-95">
                        <i class="fa-solid fa-ban text-[10px]"></i>
                        <span>Cancel</span>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

<!-- Modal: Subscribe to Plan -->
<div id="subscribeModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <span class="text-[10px] font-extrabold text-ocean-600 uppercase tracking-wider block" id="modalPlanBadge">Plan</span>
                <h3 class="text-base font-extrabold text-slate-900" id="modalPlanTitle">Subscribe</h3>
            </div>
            <button type="button" onclick="closeSubscribeModal()" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="subscribeForm" action="{{ route('customer.subscriptions.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <input type="hidden" name="plan_id" id="sub_plan_id">

            <!-- 1. Select Product & Quantity -->
            <div class="space-y-2">
                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[11px]">1. Select Water Product & Quantity</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div>
                        <select name="product_id" id="sub_product_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-bold focus:ring-2 focus:ring-ocean-500 text-xs">
                            @foreach($products as $prod)
                            <option value="{{ $prod->id }}" data-price="{{ $prod->selling_price }}">
                                {{ $prod->name }} (₹{{ number_format($prod->selling_price, 2) }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center justify-between px-3 py-2 border border-slate-200 rounded-xl bg-slate-50">
                        <span class="font-bold text-slate-600">Qty / Delivery:</span>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="adjustSubQty(-1)" class="w-6 h-6 rounded-lg bg-white border flex items-center justify-center font-bold text-slate-600 hover:bg-slate-100">-</button>
                            <span id="sub_qty_display" class="font-black text-slate-900 w-5 text-center">1</span>
                            <input type="hidden" name="quantity_per_delivery" id="sub_quantity_per_delivery" value="1">
                            <button type="button" onclick="adjustSubQty(1)" class="w-6 h-6 rounded-lg bg-white border flex items-center justify-center font-bold text-slate-600 hover:bg-slate-100">+</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Delivery Schedule & Slot -->
            <div class="space-y-2 pt-2 border-t border-slate-100">
                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[11px]">2. Start Date & Slot</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] text-slate-400 font-semibold mb-1">Start Date</label>
                        <input type="date" name="start_date" id="sub_start_date" min="{{ \Carbon\Carbon::today()->toDateString() }}" value="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-bold focus:ring-2 focus:ring-ocean-500 text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 font-semibold mb-1">Preferred Slot</label>
                        <select name="delivery_slot_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-bold focus:ring-2 focus:ring-ocean-500 text-xs">
                            @foreach($slots as $slot)
                            <option value="{{ $slot->id }}">{{ $slot->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Preferred Weekday (Only for weekly once) -->
                <div id="weeklyDaySelectRow" class="hidden pt-1">
                    <label class="block text-[10px] text-slate-400 font-semibold mb-1">Preferred Weekday for Delivery</label>
                    <select name="preferred_weekday" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-bold focus:ring-2 focus:ring-ocean-500 text-xs">
                        <option value="monday">Every Monday</option>
                        <option value="tuesday">Every Tuesday</option>
                        <option value="wednesday">Every Wednesday</option>
                        <option value="thursday">Every Thursday</option>
                        <option value="friday">Every Friday</option>
                        <option value="saturday">Every Saturday</option>
                        <option value="sunday">Every Sunday</option>
                    </select>
                </div>
            </div>

            <!-- 3. Delivery Address -->
            <div class="space-y-1.5 pt-2 border-t border-slate-100">
                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[11px]">3. Delivery Address</label>
                @if($addresses->isEmpty())
                <div class="p-3 rounded-xl bg-amber-50 text-amber-800 text-xs">
                    Please add a delivery address in your profile before subscribing.
                </div>
                @else
                <select name="address_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-medium focus:ring-2 focus:ring-ocean-500 text-xs">
                    @foreach($addresses as $addr)
                    <option value="{{ $addr->id }}">
                        {{ $addr->label }}: {{ $addr->door_number }}, {{ $addr->street }}, {{ $addr->area_locality }}
                    </option>
                    @endforeach
                </select>
                @endif
            </div>

            <!-- 4. Payment Method -->
            <div class="space-y-1.5 pt-2 border-t border-slate-100">
                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[11px]">4. Payment Option</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="p-2.5 rounded-xl border border-slate-200 hover:border-ocean-500 flex items-center space-x-2 cursor-pointer bg-slate-50/50">
                        <input type="radio" name="payment_method" value="cod" checked class="text-ocean-600">
                        <span class="font-bold text-[11px]">Pay on Delivery</span>
                    </label>
                    <label class="p-2.5 rounded-xl border border-slate-200 hover:border-ocean-500 flex items-center space-x-2 cursor-pointer bg-slate-50/50">
                        <input type="radio" name="payment_method" value="pay_later" class="text-ocean-600">
                        <span class="font-bold text-[11px]">Pay Later Due</span>
                    </label>
                    <label class="p-2.5 rounded-xl border border-slate-200 hover:border-ocean-500 flex items-center space-x-2 cursor-pointer bg-slate-50/50">
                        <input type="radio" name="payment_method" value="razorpay" class="text-ocean-600">
                        <span class="font-bold text-[11px]">Razorpay Online</span>
                    </label>
                </div>
            </div>

            <!-- Pricing Summary Card -->
            <div class="p-4 rounded-2xl bg-sky-50/70 border border-sky-100 space-y-1 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Regular Total (<span id="calc_total_cans">30</span> Cans):</span>
                    <span class="font-bold text-slate-800" id="calc_subtotal">₹0.00</span>
                </div>
                <div class="flex justify-between text-emerald-600 font-bold">
                    <span>Subscription Discount (<span id="calc_discount_pct">15</span>%):</span>
                    <span id="calc_discount_amount">-₹0.00</span>
                </div>
                <div class="flex justify-between text-sm font-black text-slate-900 pt-1.5 border-t border-sky-200">
                    <span>Total Payable:</span>
                    <span class="text-ocean-700" id="calc_final_total">₹0.00</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="closeSubscribeModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">
                    Cancel
                </button>
                <button type="submit" id="btnSubmitSub" class="px-5 py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white font-black shadow-md shadow-ocean-600/30 transition flex items-center space-x-2 active:scale-95">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Confirm Subscription</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Cancel Subscription -->
<div id="cancelSubModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900 flex items-center space-x-2 text-rose-600">
                <i class="fa-solid fa-ban"></i>
                <span id="cancelSubTitle">Cancel Subscription</span>
            </h3>
            <button type="button" onclick="$('#cancelSubModal').addClass('hidden')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="cancelSubForm" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">Reason for Cancellation <span class="text-rose-500">*</span></label>
                <select name="reason_preset" id="cancelSubReasonPreset" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-medium focus:ring-2 focus:ring-rose-500 focus:bg-white text-xs">
                    <option value="" disabled selected>-- Select a reason --</option>
                    <option value="Moving to a new address">Moving to a new address</option>
                    <option value="Delivery frequency didn't match need">Delivery frequency didn't match need</option>
                    <option value="Water consumption decreased">Water consumption decreased</option>
                    <option value="Found alternative water supplier">Found alternative water supplier</option>
                    <option value="Other reason">Other reason (please describe below)</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">Additional Comments</label>
                <textarea name="reason_details" rows="3" placeholder="Tell us more about why you are cancelling..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-medium focus:ring-2 focus:ring-rose-500 focus:bg-white text-xs resize-none"></textarea>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="$('#cancelSubModal').addClass('hidden')" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">
                    Keep Active
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-black shadow-md shadow-rose-600/30 transition flex items-center space-x-2 active:scale-95">
                    <span>Cancel Subscription</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentSelectedPlan = null;

function switchSubTab(tab) {
    if (tab === 'plans') {
        $('#tabBtnPlans').addClass('bg-ocean-600 text-white shadow-md shadow-ocean-600/20').removeClass('bg-slate-100 text-slate-700');
        $('#tabBtnMySubs').addClass('bg-slate-100 text-slate-700').removeClass('bg-ocean-600 text-white shadow-md shadow-ocean-600/20');
        $('#tabContentPlans').removeClass('hidden');
        $('#tabContentMySubs').addClass('hidden');
    } else {
        $('#tabBtnMySubs').addClass('bg-ocean-600 text-white shadow-md shadow-ocean-600/20').removeClass('bg-slate-100 text-slate-700');
        $('#tabBtnPlans').addClass('bg-slate-100 text-slate-700').removeClass('bg-ocean-600 text-white shadow-md shadow-ocean-600/20');
        $('#tabContentMySubs').removeClass('hidden');
        $('#tabContentPlans').addClass('hidden');
    }
}

function openSubscribeModal(plan) {
    currentSelectedPlan = plan;
    $('#sub_plan_id').val(plan.id);
    $('#modalPlanTitle').text('Subscribe to ' + plan.name);
    $('#modalPlanBadge').text(plan.badge || (plan.discount_percentage + '% Discount'));

    if (plan.frequency === 'weekly') {
        $('#weeklyDaySelectRow').removeClass('hidden');
    } else {
        $('#weeklyDaySelectRow').addClass('hidden');
    }

    recalcPricing();
    $('#subscribeModal').removeClass('hidden');
}

function closeSubscribeModal() {
    $('#subscribeModal').addClass('hidden');
}

function adjustSubQty(delta) {
    let current = parseInt($('#sub_quantity_per_delivery').val()) || 1;
    current = Math.max(1, Math.min(20, current + delta));
    $('#sub_quantity_per_delivery').val(current);
    $('#sub_qty_display').text(current);
    recalcPricing();
}

function recalcPricing() {
    if (!currentSelectedPlan) return;

    const qty = parseInt($('#sub_quantity_per_delivery').val()) || 1;
    const pricePerCan = parseFloat($('#sub_product_id option:selected').data('price')) || 50;
    const deliveriesCount = parseInt(currentSelectedPlan.deliveries_count) || 30;
    const discountPct = parseFloat(currentSelectedPlan.discount_percentage) || 0;

    const totalCans = qty * deliveriesCount;
    const subtotal = totalCans * pricePerCan;
    const discountAmount = (subtotal * (discountPct / 100));
    const finalTotal = subtotal - discountAmount;

    $('#calc_total_cans').text(totalCans);
    $('#calc_subtotal').text('₹' + subtotal.toFixed(2));
    $('#calc_discount_pct').text(discountPct);
    $('#calc_discount_amount').text('-₹' + discountAmount.toFixed(2));
    $('#calc_final_total').text('₹' + finalTotal.toFixed(2));
}

$('#sub_product_id').on('change', recalcPricing);

$('#subscribeForm').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const btn = $('#btnSubmitSub');

    btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Activating...</span>');

    $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        dataType: 'json'
    }).done(function(res) {
        closeSubscribeModal();
        Swal.fire({
            icon: 'success',
            title: 'Subscribed Successfully!',
            text: res.message || 'Your subscription is now active.',
            confirmButtonColor: '#0284c7'
        }).then(() => {
            location.reload();
        });
    }).fail(function(xhr) {
        btn.prop('disabled', false).html('<i class="fa-solid fa-check text-xs"></i> <span>Confirm Subscription</span>');
        const err = xhr.responseJSON?.message || 'Unable to create subscription. Please check your inputs.';
        Swal.fire('Subscription Failed', err, 'error');
    });
});

function pauseSubscription(id) {
    Swal.fire({
        title: 'Pause Deliveries?',
        text: 'Your scheduled refills will be paused. You can resume anytime.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d97706',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Pause'
    }).then((res) => {
        if (res.isConfirmed) {
            $.post('/app/subscriptions/' + id + '/pause', {
                _token: '{{ csrf_token() }}'
            }).done(function(r) {
                Swal.fire('Paused', r.message, 'success').then(() => location.reload());
            }).fail(function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed pausing', 'error');
            });
        }
    });
}

function resumeSubscription(id) {
    Swal.fire({
        title: 'Resume Deliveries?',
        text: 'Your subscription will resume and next delivery will be scheduled starting tomorrow.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Resume'
    }).then((res) => {
        if (res.isConfirmed) {
            $.post('/app/subscriptions/' + id + '/resume', {
                _token: '{{ csrf_token() }}'
            }).done(function(r) {
                Swal.fire('Resumed', r.message, 'success').then(() => location.reload());
            }).fail(function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed resuming', 'error');
            });
        }
    });
}

function openCancelSubModal(id, subNum) {
    $('#cancelSubTitle').text('Cancel Subscription #' + subNum);
    $('#cancelSubForm').attr('action', '/app/subscriptions/' + id + '/cancel');
    $('#cancelSubModal').removeClass('hidden');
}

$('#cancelSubForm').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);

    $.post(form.attr('action'), form.serialize())
        .done(function(r) {
            $('#cancelSubModal').addClass('hidden');
            Swal.fire('Cancelled', r.message, 'success').then(() => location.reload());
        })
        .fail(function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Failed cancelling', 'error');
        });
});
</script>
@endpush
