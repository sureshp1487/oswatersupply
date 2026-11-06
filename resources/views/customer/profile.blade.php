@extends('layouts.customer')

@section('title', 'My Profile - ' . ($appSettings['business_name'] ?? 'AquaFlow'))

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Customer Profile</h2>
            <p class="text-xs text-slate-500">Manage delivery addresses, preferred time slots, and account security</p>
        </div>
        <a href="{{ route('customer.home') }}" class="text-xs font-bold text-ocean-600 hover:underline">
            &larr; Back to Home
        </a>
    </div>

    <!-- 2-Column Responsive Layout on Desktop -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Personal Info Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-100 shadow-sm space-y-5">
            <div class="flex items-center space-x-4 pb-4 border-b border-slate-100">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 to-ocean-700 text-white flex items-center justify-center text-2xl font-black shadow-lg shadow-sky-500/25">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-500 flex items-center space-x-1.5 mt-0.5">
                        <i class="fa-solid fa-phone text-xs text-ocean-600"></i>
                        <span>+91 {{ $user->phone }}</span>
                    </p>
                    @if($user->email)
                        <p class="text-xs text-slate-400 mt-0.5">{{ $user->email }}</p>
                    @endif
                </div>
            </div>

            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-ocean-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address (Optional for Receipts)</label>
                    <input type="email" name="email" value="{{ $user->email }}" placeholder="Enter email" class="w-full px-4 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-ocean-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Preferred Daily Delivery Time Slot</label>
                    <select name="preferred_slot_id" class="w-full px-4 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-ocean-500">
                        @foreach($slots as $slot)
                        <option value="{{ $slot->id }}" {{ $user->customerProfile && $user->customerProfile->preferred_delivery_slot_id == $slot->id ? 'selected' : '' }}>
                            {{ $slot->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Change Password (leave empty to keep current)</label>
                    <input type="password" name="new_password" placeholder="New password" class="w-full px-4 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-ocean-500">
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-sky-600 to-ocean-700 hover:from-sky-700 hover:to-ocean-800 text-white rounded-xl text-xs font-extrabold shadow-md shadow-sky-600/25 transition active:scale-95">
                    Save Profile Changes
                </button>
            </form>
        </div>

        <!-- Saved Delivery Addresses Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h4 class="text-xs uppercase font-extrabold tracking-wider text-slate-900">Saved Delivery Addresses</h4>
                    <span class="text-[11px] text-slate-400">Multiple locations supported</span>
                </div>
                <button type="button" id="btnProfileAddAddress" class="px-3.5 py-1.5 bg-sky-50 text-ocean-700 rounded-xl text-xs font-bold hover:bg-ocean-600 hover:text-white transition">
                    + Add New
                </button>
            </div>

            <div class="space-y-3">
                @forelse($user->addresses as $addr)
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50 flex items-start justify-between space-x-3 hover:border-slate-200 transition">
                    <div class="space-y-1 text-xs">
                        <div class="flex items-center space-x-2 font-extrabold text-slate-900">
                            <i class="fa-solid fa-location-dot text-ocean-600"></i>
                            <span>{{ $addr->label }}</span>
                            @if($addr->is_default)
                                <span class="text-[9px] bg-ocean-100 text-ocean-700 px-2 py-0.5 rounded-full font-bold">Default</span>
                            @endif
                        </div>
                        <p class="text-slate-600 leading-relaxed font-medium">{{ $addr->formatted_address }}</p>
                        @if($addr->delivery_instructions)
                            <p class="text-[11px] text-slate-400"><strong>Note:</strong> {{ $addr->delivery_instructions }}</p>
                        @endif
                    </div>

                    <!-- Custom Confirm Delete Trigger Button -->
                    <button type="button" 
                            onclick="confirmDeleteAddress({{ $addr->id }}, '{{ addslashes($addr->label) }}')"
                            class="text-slate-300 hover:text-rose-600 p-2 rounded-lg hover:bg-rose-50 transition" 
                            title="Delete address">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
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
</div>

<!-- Modal: Add Address -->
<div id="addAddressModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">Add Delivery Address</h3>
            <button type="button" id="closeAddressModal" class="text-slate-400 hover:text-slate-700 p-1">
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
            <button type="submit" id="btnSaveAddress" class="w-full py-3 bg-ocean-600 text-white rounded-xl text-xs font-bold hover:bg-ocean-700 shadow transition flex items-center justify-center space-x-1.5">
                <span>Save Address</span>
            </button>
        </form>
    </div>
</div>

<!-- Custom Confirmation Modal: Delete Address -->
<div id="deleteAddressModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl border border-slate-100 text-center space-y-4 animate-in fade-in zoom-in-95">
        <!-- Warning Icon -->
        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-trash-can"></i>
        </div>

        <div>
            <h3 class="text-base font-extrabold text-slate-900">Are you sure you want to delete?</h3>
            <p class="text-xs text-slate-500 mt-1">
                You are about to remove your <span id="deleteAddressLabel" class="font-bold text-slate-700">Home</span> address. This action cannot be undone.
            </p>
        </div>

        <!-- Form for address removal -->
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

@push('scripts')
<script>
    // Open Delete Address Modal
    function confirmDeleteAddress(addressId, label) {
        $('#deleteAddressLabel').text(label || 'address');
        const deleteUrl = "{{ route('customer.address.delete', ':id') }}".replace(':id', addressId);
        $('#deleteAddressForm').attr('action', deleteUrl);
        $('#deleteAddressModal').removeClass('hidden').addClass('flex');
    }

    // Close Delete Address Modal
    function closeDeleteAddressModal() {
        $('#deleteAddressModal').removeClass('flex').addClass('hidden');
    }

    // Close when clicking backdrop
    $('#deleteAddressModal').on('click', function(e) {
        if (e.target === this) {
            closeDeleteAddressModal();
        }
    });

    // Add Address Modal controls
    $('#btnProfileAddAddress').on('click', function() {
        $('#addAddressModal').removeClass('hidden').addClass('flex');
    });

    $('#closeAddressModal').on('click', function() {
        $('#addAddressModal').removeClass('flex').addClass('hidden');
    });

    $('#addAddressModal').on('click', function(e) {
        if (e.target === this) {
            $('#addAddressModal').removeClass('flex').addClass('hidden');
        }
    });

    // Submit new address via Ajax
    $('#newAddressForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#btnSaveAddress');
        btn.attr('disabled', 'disabled').html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: "{{ route('customer.address.save') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                $('#addAddressModal').removeClass('flex').addClass('hidden');
                Swal.fire({
                    icon: 'success',
                    title: 'Address Saved',
                    text: 'Your new delivery address has been added successfully.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                btn.removeAttr('disabled').html('Save Address');
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Save',
                    text: xhr.responseJSON?.message || 'Please check your inputs and try again.'
                });
            }
        });
    });
</script>
@endpush
@endsection
