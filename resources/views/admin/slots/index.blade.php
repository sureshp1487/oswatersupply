@extends('layouts.admin')

@section('page_title', 'Delivery Time Slots')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold mb-2">
                <i class="fa-regular fa-clock"></i>
                <span>Customer Checkout & Scheduling</span>
            </div>
            <h2 class="text-lg font-black text-slate-900">Delivery Time Slots</h2>
            <p class="text-xs text-slate-500">Add, edit, enable/disable, or configure daily order capacity for delivery slots displayed on the customer checkout page.</p>
        </div>

        <button type="button" id="btnOpenNewSlotModal" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow flex items-center space-x-2 shrink-0 transition">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Time Slot</span>
        </button>
    </div>

    <!-- Slots Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($slots as $slot)
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="w-9 h-9 rounded-2xl {{ $slot->is_active ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-sm font-bold shadow-xs">
                        <i class="fa-regular fa-clock"></i>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $slot->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $slot->is_active ? 'Active' : 'Disabled' }}
                    </span>
                </div>

                <div class="mt-3">
                    <h3 class="text-sm font-black text-slate-900 leading-snug">{{ $slot->name }}</h3>
                    <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-semibold mt-1">
                        <i class="fa-regular fa-hourglass-half text-[11px] text-indigo-500"></i>
                        <span>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} &ndash; {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</span>
                    </div>
                </div>

                <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="text-[11px] font-bold text-slate-500">Daily Max Capacity:</span>
                        <span class="font-extrabold text-slate-900">{{ $slot->max_daily_capacity }} orders</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="text-[11px] font-bold text-slate-500">Total Orders Placed:</span>
                        <span class="font-extrabold text-indigo-600">{{ $slot->orders_count ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center space-x-2">
                <button type="button" 
                        class="btn-edit-slot flex-1 py-2 px-3 rounded-xl bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-bold text-xs border border-slate-200 transition flex items-center justify-center space-x-1"
                        data-slot="{{ json_encode($slot) }}">
                    <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                    <span>Edit</span>
                </button>

                <form action="{{ route('admin.slots.destroy', $slot->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete or disable this slot?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition" title="Delete Slot">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl p-12 text-center text-slate-400">
            <i class="fa-regular fa-clock text-4xl text-slate-300 mb-3 block"></i>
            <h3 class="text-sm font-bold text-slate-700">No Delivery Slots Configured</h3>
            <p class="text-xs text-slate-400 mt-1">Click "Add New Time Slot" to configure delivery timings for your customers.</p>
        </div>
        @endforelse
    </div>

</div>

<!-- Modal: Add / Edit Slot -->
<div id="slotModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 id="slotModalTitle" class="text-base font-extrabold text-slate-900">Add New Delivery Time Slot</h3>
            <button type="button" id="btnCloseSlotModal" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="slotForm" action="{{ route('admin.slots.store') }}" method="POST" class="space-y-4">
            @csrf
            <div id="slotFormMethod"></div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Display Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="slot_name" required placeholder="e.g. Morning Express (06:30 AM - 09:30 AM)" class="w-full px-3.5 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
                <p class="text-[10px] text-slate-400 mt-1">This text appears directly on the customer checkout page.</p>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Start Time <span class="text-rose-500">*</span></label>
                    <input type="time" name="start_time" id="slot_start_time" required class="w-full px-3.5 py-2.5 border rounded-xl font-mono text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">End Time <span class="text-rose-500">*</span></label>
                    <input type="time" name="end_time" id="slot_end_time" required class="w-full px-3.5 py-2.5 border rounded-xl font-mono text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Maximum Daily Order Capacity <span class="text-rose-500">*</span></label>
                <input type="number" name="max_daily_capacity" id="slot_max_daily_capacity" min="1" max="1000" value="50" required class="w-full px-3.5 py-2.5 border rounded-xl text-xs font-bold">
                <p class="text-[10px] text-slate-400 mt-1">Maximum number of water can delivery orders allowed in this slot per day.</p>
            </div>

            <div class="pt-2">
                <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" id="slot_is_active" value="1" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <span>Active and Available for Customers</span>
                </label>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2 border-t">
                <button type="button" id="btnCancelSlotModal" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" id="btnSubmitSlot" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-md transition">
                    Save Time Slot
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const modal = $('#slotModal');
    const form = $('#slotForm');
    const modalTitle = $('#slotModalTitle');
    const methodDiv = $('#slotFormMethod');

    // Open Add Slot Modal
    $('#btnOpenNewSlotModal').on('click', function() {
        form[0].reset();
        methodDiv.empty();
        form.attr('action', "{{ route('admin.slots.store') }}");
        modalTitle.text('Add New Delivery Time Slot');
        $('#slot_is_active').prop('checked', true);
        modal.removeClass('hidden').addClass('flex');
    });

    // Open Edit Slot Modal
    $('.btn-edit-slot').on('click', function() {
        const slot = $(this).data('slot');
        form[0].reset();
        methodDiv.empty();

        const updateUrl = "{{ route('admin.slots.update', ':id') }}".replace(':id', slot.id);
        form.attr('action', updateUrl);

        modalTitle.text('Edit Delivery Time Slot');
        $('#slot_name').val(slot.name);
        $('#slot_start_time').val(slot.start_time.substring(0, 5));
        $('#slot_end_time').val(slot.end_time.substring(0, 5));
        $('#slot_max_daily_capacity').val(slot.max_daily_capacity);
        $('#slot_is_active').prop('checked', !!slot.is_active);

        modal.removeClass('hidden').addClass('flex');
    });

    // Close Modal
    $('#btnCloseSlotModal, #btnCancelSlotModal').on('click', function() {
        modal.removeClass('flex').addClass('hidden');
    });

    modal.on('click', function(e) {
        if (e.target === this) {
            modal.removeClass('flex').addClass('hidden');
        }
    });
});
</script>
@endpush
@endsection
