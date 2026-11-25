@php
    $user = auth()->user();
    $slots = $slots ?? \App\Models\DeliverySlot::where('is_active', true)->get();
@endphp

<!-- ================= GLOBAL PROFILE & APP MODALS ================= -->

<!-- 1. Modal: View Profile Details (Account Settings) -->
<div id="profileDetailsModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 my-auto">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">Account Settings</h3>
            <button type="button" onclick="closeModal('profileDetailsModal')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                <input type="text" value="+91 {{ $user->phone }}" disabled class="w-full px-4 py-2.5 border rounded-xl text-xs font-semibold bg-slate-100 text-slate-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address (Optional for Invoices)</label>
                <input type="email" name="email" value="{{ $user->email }}" placeholder="Enter email" class="w-full px-4 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Preferred Daily Delivery Time Slot</label>
                <select name="preferred_slot_id" class="w-full px-4 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500">
                    @foreach($slots as $slot)
                    <option value="{{ $slot->id }}" {{ $user->customerProfile && $user->customerProfile->preferred_delivery_slot_id == $slot->id ? 'selected' : '' }}>
                        {{ $slot->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Change Password (leave empty to keep current)</label>
                <input type="password" name="new_password" placeholder="New password (min 6 characters)" class="w-full px-4 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500">
            </div>

            <button type="submit" class="w-full py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-extrabold shadow-md transition active:scale-95">
                Save Account Settings
            </button>
        </form>
    </div>
</div>

<!-- 2. Modal: Wallet -->
<div id="walletModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4 text-center">
        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-wallet"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-900">{{ explode(' ', $appSettings['business_name'] ?? 'AquaFlow')[0] }} Wallet</h3>
            <p class="text-xs text-slate-500 mt-1">Current Available Balance</p>
            <div class="text-3xl font-black text-slate-900 mt-2">₹0.00</div>
        </div>
        <div class="p-3 bg-slate-50 rounded-xl text-left text-xs text-slate-600 space-y-1">
            <div class="flex justify-between">
                <span>Account Status:</span>
                <span class="font-bold text-emerald-600">Active</span>
            </div>
            <div class="flex justify-between">
                <span>Total Due:</span>
                <span class="font-bold text-rose-500">₹{{ number_format($user->customerProfile?->total_due_balance ?? 0, 2) }}</span>
            </div>
        </div>
        <div class="flex space-x-2 pt-2">
            <a href="{{ route('customer.payments') }}" class="flex-1 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-bold rounded-xl transition text-center">
                View Ledger
            </a>
            <button type="button" onclick="closeModal('walletModal')" class="flex-1 py-2.5 border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition">
                Close
            </button>
        </div>
    </div>
</div>

<!-- 3. Modal: Bank Details -->
<div id="bankDetailsModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">Bank & Refund Details</h3>
            <button type="button" onclick="closeModal('bankDetailsModal')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <p class="text-xs text-slate-500">
            Direct refund payout details for returnable water can security deposits and cancelled orders.
        </p>
        <div class="space-y-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-1">Primary UPI ID</label>
                <div class="flex items-center space-x-2">
                    <input type="text" placeholder="e.g. mobile@upi" value="{{ $user->phone ? $user->phone . '@upi' : '' }}" class="w-full px-3 py-2 border rounded-xl text-xs font-semibold">
                </div>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-1">Bank Account / IFSC</label>
                <input type="text" placeholder="Account Number" class="w-full px-3 py-2 border rounded-xl text-xs font-semibold mb-2">
                <input type="text" placeholder="IFSC Code (e.g. HDFC0001234)" class="w-full px-3 py-2 border rounded-xl text-xs font-semibold">
            </div>
        </div>
        <button type="button" onclick="Swal.fire({icon: 'success', title: 'Saved', text: 'Bank details recorded for instant refund processing.', timer: 1500, showConfirmButton: false}); closeModal('bankDetailsModal');" class="w-full py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-bold transition">
            Save Bank Details
        </button>
    </div>
</div>

<!-- 4. Modal: Order Calendar -->
<div id="orderCalendarModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">Order & Delivery Calendar</h3>
            <button type="button" onclick="closeModal('orderCalendarModal')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="text-center py-4 bg-teal-50/50 rounded-2xl border border-teal-100">
            <i class="fa-regular fa-calendar-check text-3xl text-teal-600 mb-2 block"></i>
            <h4 class="text-xs font-bold text-slate-800">Recurring Water Deliveries</h4>
            <p class="text-[11px] text-slate-500 mt-1 max-w-xs mx-auto">
                Track scheduled daily, alternate-day, or weekly water can drop-offs and subscription dispatches.
            </p>
        </div>
        <div class="space-y-2">
            <a href="{{ route('customer.subscriptions.index') }}" class="w-full py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-bold flex items-center justify-center space-x-2 shadow-sm transition">
                <i class="fa-solid fa-calendar-days text-sm"></i>
                <span>Manage Subscriptions & Schedule</span>
            </a>
            <button type="button" onclick="closeModal('orderCalendarModal')" class="w-full py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50">
                Close
            </button>
        </div>
    </div>
</div>

<!-- 5. Modal: Favorite Orders -->
<div id="favoriteOrdersModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4 text-center">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-heart"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-900">Favorite Orders</h3>
            <p class="text-xs text-slate-500 mt-1">Reorder your frequent 20L cans or tabletop jars with a single tap.</p>
        </div>
        <div class="p-3 bg-slate-50 rounded-2xl text-left border border-slate-100 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-800">20L RO Mineral Can</span>
                <span class="text-xs font-black text-teal-600">₹35.00</span>
            </div>
            <p class="text-[11px] text-slate-400">Standard 20 Litre Exchange Can</p>
            <a href="{{ route('customer.book') }}" class="block w-full py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-bold text-center transition">
                Order Again
            </a>
        </div>
        <button type="button" onclick="closeModal('favoriteOrdersModal')" class="w-full py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50">
            Close
        </button>
    </div>
</div>

<!-- 6. Modal: Address Book (Manage Addresses) -->
<div id="addressBookModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-lg rounded-3xl p-5 sm:p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 max-h-[90vh] flex flex-col my-auto">
        <div class="flex items-center justify-between border-b pb-3 shrink-0">
            <div>
                <h3 class="text-base font-bold text-slate-900">Manage Addresses</h3>
                <p class="text-[11px] text-slate-400">Manage saved delivery addresses</p>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" id="btnProfileAddAddress" class="px-3 py-1.5 bg-teal-50 text-teal-700 rounded-xl text-xs font-bold hover:bg-sky-500 hover:text-white transition">
                    + Add New
                </button>
                <button type="button" onclick="closeModal('addressBookModal')" class="text-slate-400 hover:text-slate-700 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <div class="space-y-3 overflow-y-auto pr-1 flex-1">
            @forelse($user->addresses as $addr)
            <div class="p-3.5 rounded-2xl border transition flex items-start justify-between space-x-3 {{ $addr->is_default ? 'border-teal-300 bg-teal-50/40 ring-1 ring-teal-400/20' : 'border-slate-100 bg-slate-50/50 hover:border-slate-200' }}">
                <div class="flex items-start space-x-3 min-w-0">
                    <button type="button" 
                            onclick="{{ $addr->is_default ? '' : 'setDefaultAddress(' . $addr->id . ')' }}" 
                            class="mt-0.5 shrink-0 focus:outline-none transition {{ $addr->is_default ? 'cursor-default text-teal-600' : 'cursor-pointer text-slate-300 hover:text-teal-600' }}" 
                            title="{{ $addr->is_default ? 'Primary Default Address' : 'Click to select as default address' }}">
                        @if($addr->is_default)
                            <i class="fa-solid fa-circle-check text-lg text-teal-600"></i>
                        @else
                            <i class="fa-regular fa-circle text-lg text-slate-300 hover:text-teal-500"></i>
                        @endif
                    </button>

                    <div class="space-y-1 text-xs min-w-0">
                        <div class="flex items-center space-x-2 font-extrabold text-slate-900">
                            <span class="text-sm font-black">{{ $addr->label }}</span>
                            @if($addr->is_default)
                                <span class="text-[10px] bg-teal-600 text-white px-2 py-0.5 rounded-full font-bold shadow-xs">
                                    Default
                                </span>
                            @endif
                        </div>
                        <p class="text-slate-600 leading-relaxed font-medium break-words">{{ $addr->formatted_address }}</p>
                        @if($addr->delivery_instructions)
                            <p class="text-[11px] text-slate-400"><strong>Note:</strong> {{ $addr->delivery_instructions }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-1 shrink-0 pt-0.5">
                    @if(!$addr->is_default)
                    <button type="button" 
                            onclick="setDefaultAddress({{ $addr->id }})" 
                            class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-white text-teal-700 hover:bg-sky-500 hover:text-white border border-slate-200 transition">
                        Default
                    </button>
                    @endif

                    <button type="button" 
                            onclick="confirmDeleteAddress({{ $addr->id }}, '{{ addslashes($addr->label) }}')"
                            class="text-slate-300 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition" 
                            title="Delete address">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-location-dot text-2xl text-slate-300 mb-2 block"></i>
                No delivery addresses found. Click "+ Add New" to add one.
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- 7. Modal: Return Empty Jar -->
<div id="returnJarModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">Return Jar</h3>
            <button type="button" onclick="closeModal('returnJarModal')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-3 bg-teal-50/60 rounded-2xl flex items-center justify-between text-xs border border-teal-100">
            <span class="font-bold text-slate-700">Empty Cans Held:</span>
            <span class="text-base font-black text-teal-700">{{ $user->customerProfile?->total_empty_cans_held ?? 0 }} Cans</span>
        </div>
        <div class="space-y-3 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Cans to return for pickup</label>
                <input type="number" min="1" max="{{ max(1, $user->customerProfile?->total_empty_cans_held ?? 1) }}" value="{{ min(1, max(1, $user->customerProfile?->total_empty_cans_held ?? 1)) }}" class="w-full px-3 py-2 border rounded-xl font-bold">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pickup Date & Time</label>
                <select class="w-full px-3 py-2 border rounded-xl font-medium">
                    <option>Tomorrow Morning (07:00 AM - 10:00 AM)</option>
                    <option>Tomorrow Evening (04:00 PM - 07:00 PM)</option>
                </select>
            </div>
        </div>
        <button type="button" onclick="Swal.fire({icon: 'success', title: 'Pickup Scheduled', text: 'Our delivery agent will collect the empty cans during your selected slot.', timer: 2000, showConfirmButton: false}); closeModal('returnJarModal');" class="w-full py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-bold transition">
            Schedule Can Pickup
        </button>
    </div>
</div>

<!-- 8. Modal: Contact Information -->
<div id="contactInfoModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4 text-center">
        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-headset"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-900">Contact Information</h3>
            <p class="text-xs text-slate-500 mt-1">We are here to assist you 7 days a week.</p>
        </div>
        <div class="space-y-2 text-left text-xs">
            <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl">
                <i class="fa-solid fa-phone text-teal-600 text-base mt-0.5"></i>
                <div>
                    <span class="font-bold text-slate-700 block">Helpline Number</span>
                    <a href="tel:{{ $appSettings['business_phone'] ?? '+919876543210' }}" class="text-teal-700 font-extrabold hover:underline">
                        {{ $appSettings['business_phone'] ?? '+91 98765 43210' }}
                    </a>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl">
                <i class="fa-solid fa-envelope text-teal-600 text-base mt-0.5"></i>
                <div>
                    <span class="font-bold text-slate-700 block">Support Email</span>
                    <a href="mailto:{{ $appSettings['business_email'] ?? 'support@aquaflow.com' }}" class="text-teal-700 font-semibold hover:underline">
                        {{ $appSettings['business_email'] ?? 'support@aquaflow.com' }}
                    </a>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl">
                <i class="fa-solid fa-clock text-teal-600 text-base mt-0.5"></i>
                <div>
                    <span class="font-bold text-slate-700 block">Operating Hours</span>
                    <span class="text-slate-500">Monday - Sunday: 06:00 AM - 09:00 PM</span>
                </div>
            </div>
        </div>
        <button type="button" onclick="closeModal('contactInfoModal')" class="w-full py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50">
            Close
        </button>
    </div>
</div>

<!-- 9. Modal: Chat With Us -->
<div id="chatSupportModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4 text-center">
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-brands fa-whatsapp"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-900">Chat With Us</h3>
            <p class="text-xs text-slate-500 mt-1">Connect instantly with our customer care executive on WhatsApp.</p>
        </div>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $appSettings['business_phone'] ?? '919876543210') }}?text=Hello%20{{ urlencode($appSettings['business_name'] ?? 'AquaFlow') }}%2C%20I%20need%20assistance%20with%20my%20water%20delivery." target="_blank" class="block w-full py-3 bg-[#25D366] hover:bg-[#20ba5a] text-white rounded-xl text-xs font-bold transition shadow-sm">
            <i class="fa-brands fa-whatsapp mr-1 text-sm"></i> Open WhatsApp Chat
        </a>
        <button type="button" onclick="closeModal('chatSupportModal')" class="w-full py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50">
            Cancel
        </button>
    </div>
</div>

<!-- 10. Modal: FAQ -->
<div id="faqModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl space-y-4 max-h-[85vh] flex flex-col my-auto">
        <div class="flex items-center justify-between border-b pb-3 shrink-0">
            <h3 class="text-base font-bold text-slate-900">Frequently Asked Questions</h3>
            <button type="button" onclick="closeModal('faqModal')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="space-y-3 overflow-y-auto pr-1 flex-1 text-xs">
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <h4 class="font-bold text-slate-900">How does water can delivery exchange work?</h4>
                <p class="text-slate-600 leading-relaxed">For 20L cans, our delivery executive brings a fresh sanitized can and collects your empty can on a 1:1 exchange basis.</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <h4 class="font-bold text-slate-900">What if I don't have an empty can to exchange?</h4>
                <p class="text-slate-600 leading-relaxed">You can pay a refundable empty jar security deposit during your first order or when ordering additional cans.</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <h4 class="font-bold text-slate-900">How can I pause or change my daily subscription?</h4>
                <p class="text-slate-600 leading-relaxed">Navigate to "Order Calendar" or "Subscriptions" tab anytime to pause, resume, or change your delivery frequency.</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <h4 class="font-bold text-slate-900">How do I get my deposit refund?</h4>
                <p class="text-slate-600 leading-relaxed">Schedule a "Return Jar" pickup. Once our executive inspects the jar, the deposit is credited to your bank/UPI within 24 hours.</p>
            </div>
        </div>
    </div>
</div>

<!-- 11. Modal: Locate Us -->
<div id="locateUsModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4 text-center">
        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-location-dot"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-900">Our Hub & RO Plant</h3>
            <p class="text-xs text-slate-500 mt-1">Water Purification & Distribution Facility</p>
        </div>
        <div class="p-3 bg-slate-50 rounded-xl text-left text-xs text-slate-600 space-y-1">
            <p class="font-bold text-slate-900">{{ $appSettings['business_name'] ?? 'AquaFlow Water Delivery' }}</p>
            <p>Main Water Works Road, Industrial Area, Chennai, Tamil Nadu - 600040</p>
            <p class="text-teal-600 font-semibold pt-1">Delivering within a 15km Radius</p>
        </div>
        <button type="button" onclick="closeModal('locateUsModal')" class="w-full py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50">
            Close
        </button>
    </div>
</div>

<!-- 12. Modal: Collected Coupons -->
<div id="couponsModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">Your Coupons</h3>
            <button type="button" onclick="closeModal('couponsModal')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="space-y-2.5 text-xs">
            <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="font-mono font-bold text-amber-800 text-sm">AQUA20</span>
                    <p class="text-[11px] text-amber-700 mt-0.5">20% off on your next 3 water orders</p>
                </div>
                <button type="button" onclick="navigator.clipboard.writeText('AQUA20'); Swal.fire({icon: 'success', title: 'Copied', text: 'Coupon code AQUA20 copied to clipboard', timer: 1200, showConfirmButton: false});" class="px-2.5 py-1 bg-amber-600 text-white rounded-lg text-[11px] font-bold">
                    Copy
                </button>
            </div>
            <div class="p-3 bg-teal-50/70 border border-teal-200 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="font-mono font-bold text-teal-800 text-sm">FREESHIP</span>
                    <p class="text-[11px] text-teal-700 mt-0.5">Free doorstep delivery on recurring subscriptions</p>
                </div>
                <button type="button" onclick="navigator.clipboard.writeText('FREESHIP'); Swal.fire({icon: 'success', title: 'Copied', text: 'Coupon code FREESHIP copied to clipboard', timer: 1200, showConfirmButton: false});" class="px-2.5 py-1 bg-teal-600 text-white rounded-lg text-[11px] font-bold">
                    Copy
                </button>
            </div>
        </div>
        <button type="button" onclick="closeModal('couponsModal')" class="w-full py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50">
            Close
        </button>
    </div>
</div>

<!-- 13. Modal: About Us -->
<div id="aboutUsModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4 text-center">
        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-droplet"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-900">About {{ $appSettings['business_name'] ?? 'AquaFlow' }}</h3>
            <p class="text-xs text-slate-500 mt-1">Pioneering Pure Drinking Water Delivery</p>
        </div>
        <p class="text-xs text-slate-600 leading-relaxed text-left bg-slate-50 p-3 rounded-xl">
            We deliver purified, multi-stage RO + UV + Ozonized drinking water jars straight to your home or office. Every bottle undergoes stringent microbiological tests to guarantee crystal clear, healthy hydration.
        </p>
        <button type="button" onclick="closeModal('aboutUsModal')" class="w-full py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50">
            Close
        </button>
    </div>
</div>

<!-- 14. Modal: Terms & Conditions -->
<div id="termsModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl space-y-4 max-h-[85vh] flex flex-col my-auto">
        <div class="flex items-center justify-between border-b pb-3 shrink-0">
            <h3 class="text-base font-bold text-slate-900">Terms & Conditions</h3>
            <button type="button" onclick="closeModal('termsModal')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="space-y-2.5 overflow-y-auto pr-1 flex-1 text-xs text-slate-600 leading-relaxed">
            <p><strong>1. Delivery Service:</strong> Water deliveries are executed according to the chosen delivery slot. Delivery times may vary slightly during peak monsoon or traffic.</p>
            <p><strong>2. Empty Can Returns:</strong> Customers must provide an equivalent empty 20L can in clean condition or purchase a container deposit.</p>
            <p><strong>3. Bottle Security Deposit:</strong> Security deposits are refundable upon return of undamaged containers with intact neck rings.</p>
            <p><strong>4. Payments:</strong> Payments can be made online via UPI/Card or Cash on Delivery where permitted.</p>
        </div>
        <button type="button" onclick="closeModal('termsModal')" class="w-full py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50 shrink-0">
            Close
        </button>
    </div>
</div>

<!-- 15. Modal: Cancellation & Refunds Policy -->
<div id="refundPolicyModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl space-y-4 max-h-[85vh] flex flex-col my-auto">
        <div class="flex items-center justify-between border-b pb-3 shrink-0">
            <h3 class="text-base font-bold text-slate-900">Cancellation & Refunds</h3>
            <button type="button" onclick="closeModal('refundPolicyModal')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="space-y-2.5 overflow-y-auto pr-1 flex-1 text-xs text-slate-600 leading-relaxed">
            <p><strong>1. Order Cancellations:</strong> Orders may be cancelled free of cost prior to dispatch by the delivery partner.</p>
            <p><strong>2. Immediate Refunds:</strong> If an order is cancelled or not fulfilled, the prepaid amount is immediately refunded to your original payment mode within 3-5 working days.</p>
            <p><strong>3. Empty Can Deposit Return:</strong> Return any held jars using the "Return Jar" feature to receive your security deposit refund via UPI.</p>
        </div>
        <button type="button" onclick="closeModal('refundPolicyModal')" class="w-full py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50 shrink-0">
            Close
        </button>
    </div>
</div>

<!-- 16. Modal: Add Address (Leaflet Map + GPS Pinpoint) -->
<div id="addAddressModal" class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md rounded-3xl p-5 sm:p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 max-h-[90vh] flex flex-col my-auto">
        <div class="flex items-center justify-between border-b pb-3 shrink-0">
            <h3 class="text-base font-bold text-slate-900">Add Delivery Address</h3>
            <button type="button" id="closeAddressModal" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="newAddressForm" class="space-y-3 overflow-y-auto pr-1">
            @csrf
            <!-- Hidden GPS Lat / Long -->
            <input type="hidden" name="latitude" id="profile_address_lat">
            <input type="hidden" name="longitude" id="profile_address_lng">

            <!-- Interactive GPS Map Picker Button & Box -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-700">Pinpoint Delivery Gate on Map</label>
                    <button type="button" id="btnProfileGpsDetect" class="text-[11px] font-bold text-teal-600 hover:text-teal-700 flex items-center space-x-1">
                        <i class="fa-solid fa-crosshairs text-xs"></i>
                        <span>Use My GPS</span>
                    </button>
                </div>
                <!-- Leaflet Map Container -->
                <div id="profileAddressMap" class="w-full h-44 rounded-2xl border border-slate-200 overflow-hidden relative shadow-inner z-0"></div>
                <div class="flex items-center justify-between text-[11px] text-slate-500 px-1">
                    <span id="profileMapStatusText"><i class="fa-solid fa-hand-pointer text-slate-400 mr-1"></i>Drag marker to pinpoint your exact gate.</span>
                    <span id="profileCoordinatesBadge" class="font-mono text-[10px] text-emerald-600 font-bold hidden"></span>
                </div>
            </div>

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
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Door / Flat No. <span class="text-rose-500">*</span></label>
                    <input type="text" name="door_number" required placeholder="Flat 4B" class="w-full px-3 py-2 border rounded-xl text-xs font-medium">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Building Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="house_or_building" required placeholder="Skyline Towers" class="w-full px-3 py-2 border rounded-xl text-xs font-medium">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Street / Road <span class="text-rose-500">*</span></label>
                <input type="text" name="street" required placeholder="2nd Cross Street" class="w-full px-3 py-2 border rounded-xl text-xs font-medium">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Locality / Area <span class="text-rose-500">*</span></label>
                    <input type="text" name="area_locality" required placeholder="Indiranagar" class="w-full px-3 py-2 border rounded-xl text-xs font-medium">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pincode <span class="text-rose-500">*</span></label>
                    <input type="text" name="pincode" required placeholder="600040" maxlength="6" class="w-full px-3 py-2 border rounded-xl text-xs font-medium">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">City <span class="text-rose-500">*</span></label>
                <input type="text" name="city" value="Chennai" required class="w-full px-3 py-2 border rounded-xl text-xs font-medium">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Delivery Instructions (Optional)</label>
                <input type="text" name="delivery_instructions" placeholder="e.g. Leave with security" class="w-full px-3 py-2 border rounded-xl text-xs">
            </div>
            <div class="pt-1 pb-1">
                <button type="submit" id="btnSaveAddress" class="w-full py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-bold shadow transition flex items-center justify-center space-x-1.5">
                    <span>Save Address</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 17. Custom Confirmation Modal: Delete Address -->
<div id="deleteAddressModal" class="fixed inset-0 z-[70] bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl border border-slate-100 text-center space-y-4 animate-in fade-in zoom-in-95">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-trash-can"></i>
        </div>

        <div>
            <h3 class="text-base font-extrabold text-slate-900">Delete Address?</h3>
            <p class="text-xs text-slate-500 mt-1">
                You are about to remove your <span id="deleteAddressLabel" class="font-bold text-slate-700">Home</span> address. This action cannot be undone.
            </p>
        </div>

        <form id="deleteAddressForm" method="POST" action="" class="flex items-center space-x-2 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteAddressModal()" class="w-1/2 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                Cancel
            </button>
            <button type="submit" class="w-1/2 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold shadow-md shadow-rose-600/25 transition">
                Yes, Delete
            </button>
        </form>
    </div>
</div>

<!-- 18. Modal: Global Search (Desktop & Mobile) -->
<div id="globalSearchModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-start justify-center pt-20 px-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-5 sm:p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95">
        <div class="flex items-center justify-between border-b pb-3">
            <div class="flex items-center space-x-2 text-slate-800">
                <i class="fa-solid fa-magnifying-glass text-teal-600 text-base"></i>
                <h3 class="text-base font-extrabold">Search AquaFlow</h3>
            </div>
            <button type="button" onclick="closeModal('globalSearchModal')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
            <input type="text" id="globalSearchInput" onkeyup="filterGlobalSearch(this.value)" placeholder="Search for 20L cans, water jars, services, plans..." class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
        </div>

        <div id="globalSearchResults" class="space-y-1.5 max-h-72 overflow-y-auto">
            <a href="{{ route('customer.book') }}" class="search-item flex items-center justify-between p-3 rounded-2xl hover:bg-teal-50/60 transition group">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center">
                        <i class="fa-solid fa-bottle-water"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-teal-700">20L RO Mineral Water Can</h4>
                        <p class="text-[11px] text-slate-400">Order standard exchange can doorstep delivery</p>
                    </div>
                </div>
                <span class="text-xs font-extrabold text-teal-700">Order Now &rarr;</span>
            </a>

            <a href="{{ route('customer.services') }}" class="search-item flex items-center justify-between p-3 rounded-2xl hover:bg-teal-50/60 transition group">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center">
                        <i class="fa-solid fa-bell-concierge"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-sky-700">Doorstep Services</h4>
                        <p class="text-[11px] text-slate-400">Water Can, Doorstep Car Wash, House Rental</p>
                    </div>
                </div>
                <span class="text-xs font-extrabold text-sky-700">Explore &rarr;</span>
            </a>

            <a href="{{ route('customer.subscriptions.index') }}" class="search-item flex items-center justify-between p-3 rounded-2xl hover:bg-teal-50/60 transition group">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                        <i class="fa-solid fa-repeat"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-indigo-700">Water Subscriptions</h4>
                        <p class="text-[11px] text-slate-400">Automated daily & alternate-day deliveries</p>
                    </div>
                </div>
                <span class="text-xs font-extrabold text-indigo-700">Subscribe &rarr;</span>
            </a>

            <a href="{{ route('customer.orders') }}" class="search-item flex items-center justify-between p-3 rounded-2xl hover:bg-teal-50/60 transition group">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-amber-700">My Orders & Live Tracking</h4>
                        <p class="text-[11px] text-slate-400">Track current water drop-off & driver location</p>
                    </div>
                </div>
                <span class="text-xs font-extrabold text-amber-700">Track &rarr;</span>
            </a>
        </div>
    </div>
</div>

<!-- Hidden Logout Form -->
<form id="profileLogoutForm" action="{{ route('logout') }}" method="POST" class="hidden" onsubmit="if(window.CartManager){ window.CartManager.saveCart({}); }">
    @csrf
</form>

<script>
    // Generic Modal Open/Close Helpers
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    }

    function openSearchModal() {
        openModal('globalSearchModal');
        setTimeout(() => {
            const input = document.getElementById('globalSearchInput');
            if (input) input.focus();
        }, 150);
    }

    function filterGlobalSearch(val) {
        const query = val.toLowerCase().trim();
        document.querySelectorAll('#globalSearchResults .search-item').forEach(item => {
            const text = item.innerText.toLowerCase();
            if (!query || text.includes(query)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Toggle Desktop User Dropdown Menu
    function toggleDesktopUserMenu(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const dropdown = document.getElementById('desktopUserMenuDropdown');
        const chevron = document.getElementById('desktopUserChevron');
        if (dropdown) {
            const isHidden = dropdown.classList.contains('hidden');
            if (isHidden) {
                dropdown.classList.remove('hidden');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }
    }

    function closeDesktopUserMenu() {
        const dropdown = document.getElementById('desktopUserMenuDropdown');
        const chevron = document.getElementById('desktopUserChevron');
        if (dropdown) dropdown.classList.add('hidden');
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    }

    // Close Dropdown & Modals on Click Outside
    window.addEventListener('click', function(e) {
        const container = document.getElementById('desktopUserDropdownContainer');
        if (container && !container.contains(e.target)) {
            closeDesktopUserMenu();
        }

        document.querySelectorAll('.fixed.z-50, .fixed.z-\\[60\\]').forEach(modal => {
            if (e.target === modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        });
    });

    // Close Modals on ESC key
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDesktopUserMenu();
            document.querySelectorAll('.fixed.z-50, .fixed.z-\\[60\\], .fixed.z-\\[70\\]').forEach(modal => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            });
        }
    });

    // Logout Confirmation
    function confirmLogout() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Logout?',
                text: 'Are you sure you want to log out of your account?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Stay Logged In'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (window.CartManager) {
                        window.CartManager.saveCart({});
                    }
                    document.getElementById('profileLogoutForm').submit();
                }
            });
        } else {
            if (confirm('Are you sure you want to logout?')) {
                if (window.CartManager) window.CartManager.saveCart({});
                document.getElementById('profileLogoutForm').submit();
            }
        }
    }

    // Set Default Address via Ajax
    function setDefaultAddress(addressId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Updating default address...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        $.ajax({
            url: "/app/address/" + addressId + "/default",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Default Address Updated',
                        text: response.message || 'Your default delivery address has been changed.',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    location.reload();
                }
            },
            error: function(xhr) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: xhr.responseJSON?.message || 'Could not update default address. Please try again.'
                    });
                } else {
                    alert('Could not update default address.');
                }
            }
        });
    }

    // Open Delete Address Modal
    function confirmDeleteAddress(addressId, label) {
        $('#deleteAddressLabel').text(label || 'address');
        const deleteUrl = "{{ route('customer.address.delete', ':id') }}".replace(':id', addressId);
        $('#deleteAddressForm').attr('action', deleteUrl);
        $('#deleteAddressModal').removeClass('hidden').addClass('flex');
    }

    function closeDeleteAddressModal() {
        $('#deleteAddressModal').removeClass('flex').addClass('hidden');
    }

    // Leaflet GPS Map Picker for Add Address
    let profileLeafletMap = null;
    let profileMarker = null;

    function initProfileMap(defaultLat = 13.0827, defaultLng = 80.2707) {
        if (!document.getElementById('profileAddressMap') || typeof L === 'undefined') return;

        if (!profileLeafletMap) {
            profileLeafletMap = L.map('profileAddressMap').setView([defaultLat, defaultLng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(profileLeafletMap);

            profileMarker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(profileLeafletMap);

            profileMarker.on('dragend', function(e) {
                const pos = profileMarker.getLatLng();
                updateProfileCoordinates(pos.lat, pos.lng);
            });

            profileLeafletMap.on('click', function(e) {
                profileMarker.setLatLng(e.latlng);
                updateProfileCoordinates(e.latlng.lat, e.latlng.lng);
            });
        } else {
            profileLeafletMap.invalidateSize();
            profileLeafletMap.setView([defaultLat, defaultLng], 14);
            profileMarker.setLatLng([defaultLat, defaultLng]);
        }

        updateProfileCoordinates(defaultLat, defaultLng);
    }

    function updateProfileCoordinates(lat, lng) {
        $('#profile_address_lat').val(lat.toFixed(7));
        $('#profile_address_lng').val(lng.toFixed(7));
        $('#profileCoordinatesBadge').text(`GPS: ${lat.toFixed(4)}, ${lng.toFixed(4)}`).removeClass('hidden');
        $('#profileMapStatusText').html('<i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i>Location pinned for driver navigation.');
    }

    $(document).ready(function() {
        // Add Address Modal controls
        $(document).on('click', '#btnProfileAddAddress', function() {
            $('#addAddressModal').removeClass('hidden').addClass('flex');
            setTimeout(() => {
                initProfileMap();
            }, 200);
        });

        // Detect GPS Button click inside address modal
        $(document).on('click', '#btnProfileGpsDetect', function() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }

            const btn = $(this);
            const originalHtml = btn.html();
            btn.html('<i class="fa-solid fa-spinner fa-spin text-xs"></i> Detecting...');

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    initProfileMap(lat, lng);
                    btn.html('<i class="fa-solid fa-check text-emerald-500 text-xs"></i> Detected');
                    setTimeout(() => btn.html(originalHtml), 2000);
                },
                (err) => {
                    btn.html(originalHtml);
                    alert('Could not retrieve your GPS location. Please check browser permissions or drag the map marker.');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });

        $(document).on('click', '#closeAddressModal', function() {
            $('#addAddressModal').removeClass('flex').addClass('hidden');
        });

        $(document).on('click', '#addAddressModal', function(e) {
            if (e.target === this) {
                $('#addAddressModal').removeClass('flex').addClass('hidden');
            }
        });

        $(document).on('click', '#deleteAddressModal', function(e) {
            if (e.target === this) {
                closeDeleteAddressModal();
            }
        });

        // Submit new address via Ajax
        $(document).on('submit', '#newAddressForm', function(e) {
            e.preventDefault();
            const btn = $('#btnSaveAddress');
            btn.attr('disabled', 'disabled').html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: "{{ route('customer.address.save') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#addAddressModal').removeClass('flex').addClass('hidden');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Address Saved',
                            text: 'Your new delivery address has been added successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    btn.removeAttr('disabled').html('Save Address');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Save',
                            text: xhr.responseJSON?.message || 'Please check your inputs and try again.'
                        });
                    } else {
                        alert(xhr.responseJSON?.message || 'Failed to save address.');
                    }
                }
            });
        });
    });
</script>
