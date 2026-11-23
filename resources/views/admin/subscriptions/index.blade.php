@extends('layouts.admin')

@section('page_title', 'Subscription Plans & Customer Subscriptions')

@section('content')
<div class="space-y-6">

    <!-- Top Stats Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-indigo-500/30 text-indigo-200 text-xs font-bold mb-2 border border-indigo-400/30">
                <i class="fa-solid fa-repeat"></i>
                <span>Recurring Water Delivery</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black tracking-tight">Customer Subscription Plans</h2>
            <p class="text-xs sm:text-sm text-indigo-200 mt-1">Configure subscription plans, discounts, delivery intervals, and monitor customer refill schedules.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            <div class="p-3 rounded-2xl bg-white/10 backdrop-blur border border-white/15 text-center min-w-[90px]">
                <span class="text-[10px] text-indigo-200 uppercase font-bold block">Plans</span>
                <span class="text-xl font-black text-white">{{ $plans->count() }}</span>
            </div>
            <div class="p-3 rounded-2xl bg-white/10 backdrop-blur border border-white/15 text-center min-w-[90px]">
                <span class="text-[10px] text-emerald-300 uppercase font-bold block">Active</span>
                <span class="text-xl font-black text-emerald-400">{{ $totalActive }}</span>
            </div>
            <div class="p-3 rounded-2xl bg-white/10 backdrop-blur border border-white/15 text-center min-w-[90px]">
                <span class="text-[10px] text-amber-200 uppercase font-bold block">Paused</span>
                <span class="text-xl font-black text-amber-300">{{ $totalPaused }}</span>
            </div>
            <div class="p-3 rounded-2xl bg-white/10 backdrop-blur border border-white/15 text-center min-w-[90px]">
                <span class="text-[10px] text-rose-200 uppercase font-bold block">Cancelled</span>
                <span class="text-xl font-black text-rose-300">{{ $totalCancelled }}</span>
            </div>
        </div>
    </div>

    <!-- SECTION 1: SUBSCRIPTION PLANS (CRUD) -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-layer-group"></i>
                    </span>
                    <h3 class="text-base font-extrabold text-slate-900">Subscription Plans ({{ $plans->count() }})</h3>
                </div>
                <p class="text-xs text-slate-500 mt-1">Configure subscription packages available for customers on the mobile app.</p>
            </div>
            <button type="button" id="btnOpenNewPlanModal" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow flex items-center justify-center space-x-2 shrink-0 transition active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add New Plan</span>
            </button>
        </div>

        @if($plans->isEmpty())
        <div class="p-10 text-center text-slate-400 space-y-3">
            <i class="fa-solid fa-box-open text-4xl text-slate-300"></i>
            <p class="text-sm font-bold text-slate-600">No Subscription Plans Configured</p>
            <p class="text-xs">Click "Add New Plan" to create your first water delivery subscription tier.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($plans as $plan)
            <div class="rounded-2xl border {{ $plan->is_active ? 'border-slate-200/90 bg-white' : 'border-slate-200 bg-slate-50/70 opacity-75' }} p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <!-- Plan Card Header -->
                    <div class="flex items-start justify-between gap-2 pb-3 border-b border-slate-100">
                        <div class="min-w-0">
                            <div class="flex items-center space-x-2">
                                <h4 class="text-sm font-black text-slate-900 truncate">{{ $plan->name }}</h4>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $plan->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $plan->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </div>
                            @if($plan->badge)
                            <span class="inline-block px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10px] font-bold rounded-md mt-1">
                                <i class="fa-solid fa-tag text-[9px] mr-1"></i>{{ $plan->badge }}
                            </span>
                            @endif
                        </div>
                        <span class="px-2.5 py-1 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 text-xs font-black shrink-0">
                            {{ rtrim(rtrim(number_format($plan->discount_percentage, 2), '0'), '.') }}% OFF
                        </span>
                    </div>

                    <!-- Plan Details -->
                    <div class="mt-3.5 space-y-2 text-xs">
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 font-semibold">Frequency:</span>
                            <span class="font-bold text-slate-800 capitalize">
                                @if($plan->frequency === 'daily')
                                    <i class="fa-solid fa-calendar-day text-indigo-500 mr-1"></i> Daily (Every Day)
                                @elseif($plan->frequency === 'alternate_days')
                                    <i class="fa-solid fa-calendar-week text-cyan-500 mr-1"></i> Alternate Days (Every 2 Days)
                                @else
                                    <i class="fa-regular fa-calendar-check text-emerald-500 mr-1"></i> Weekly (Once a Week)
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 font-semibold">Deliveries Count:</span>
                            <span class="font-extrabold text-indigo-600">{{ $plan->deliveries_count }} Doorstep Deliveries</span>
                        </div>

                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 font-semibold">Subscribers:</span>
                            <span class="font-bold text-slate-800">{{ $plan->subscriptions_count }} customer(s)</span>
                        </div>

                        @if($plan->description)
                        <p class="text-[11px] text-slate-500 bg-slate-50 rounded-xl p-2.5 border border-slate-100 mt-2 line-clamp-2">
                            {{ $plan->description }}
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Plan Card Actions -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center space-x-2">
                    <button type="button" 
                            class="btn-edit-plan flex-1 py-2 px-3 rounded-xl bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-bold text-xs border border-slate-200 transition flex items-center justify-center space-x-1.5"
                            data-plan="{{ json_encode($plan) }}">
                        <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                        <span>Edit Plan</span>
                    </button>

                    <form action="{{ route('admin.subscriptions.plans.destroy', $plan->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete or disable this plan?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition" title="Delete Plan">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- SECTION 2: CUSTOMER SUBSCRIBERS TABLE -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-users-line"></i>
                    </span>
                    <h3 class="text-base font-extrabold text-slate-900">Customer Subscriptions ({{ $subscriptions->total() }})</h3>
                </div>
                <p class="text-[11px] text-slate-500 mt-0.5">Live refill orders and automated schedules placed by customers</p>
            </div>
            <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                <a href="{{ route('admin.subscriptions.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All</a>
                <a href="{{ route('admin.subscriptions.index', ['status' => 'active']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold {{ request('status') === 'active' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Active ({{ $totalActive }})</a>
                <a href="{{ route('admin.subscriptions.index', ['status' => 'paused']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold {{ request('status') === 'paused' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Paused ({{ $totalPaused }})</a>
                <a href="{{ route('admin.subscriptions.index', ['status' => 'cancelled']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold {{ request('status') === 'cancelled' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Cancelled ({{ $totalCancelled }})</a>
            </div>
        </div>

        @if($subscriptions->isEmpty())
        <div class="p-12 text-center text-slate-400 space-y-3">
            <i class="fa-solid fa-repeat text-4xl text-slate-300"></i>
            <p class="text-sm font-bold text-slate-600">No Customer Subscriptions Found</p>
            <p class="text-xs">No customer subscriptions currently match the selected filter.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b text-slate-400 uppercase text-[10px]">
                        <th class="py-3 px-2">Sub #</th>
                        <th class="py-3 px-2">Customer</th>
                        <th class="py-3 px-2">Plan & Product</th>
                        <th class="py-3 px-2">Next Delivery</th>
                        <th class="py-3 px-2">Slot</th>
                        <th class="py-3 px-2 text-center">Progress</th>
                        <th class="py-3 px-2 text-center">Status</th>
                        <th class="py-3 px-2 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($subscriptions as $sub)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-2 font-mono font-bold text-indigo-600">
                            {{ $sub->subscription_number }}
                        </td>
                        <td class="py-3.5 px-2">
                            <div class="font-bold text-slate-800">{{ $sub->user?->name ?? 'Customer' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $sub->user?->phone }}</div>
                        </td>
                        <td class="py-3.5 px-2">
                            <div class="font-bold text-slate-800">{{ $sub->plan?->name }}</div>
                            <div class="text-[11px] text-slate-500">{{ $sub->quantity_per_delivery }}x {{ $sub->product?->name }} ({{ ucfirst($sub->frequency) }})</div>
                        </td>
                        <td class="py-3.5 px-2 font-semibold text-slate-700">
                            @if($sub->status === 'active' && $sub->next_delivery_date)
                                <span class="text-indigo-600 font-bold">{{ \Carbon\Carbon::parse($sub->next_delivery_date)->format('d M Y') }}</span>
                            @elseif($sub->status === 'paused')
                                <span class="text-amber-600 font-bold">On Hold</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-2 text-slate-600 font-medium">
                            {{ $sub->deliverySlot?->name ?? '-' }}
                        </td>
                        <td class="py-3.5 px-2 text-center">
                            <span class="font-bold text-slate-800">{{ $sub->completed_deliveries }} / {{ $sub->total_deliveries }}</span>
                            <div class="w-16 mx-auto bg-slate-100 h-1.5 rounded-full mt-1 overflow-hidden">
                                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $sub->progress_percentage }}%"></div>
                            </div>
                        </td>
                        <td class="py-3.5 px-2 text-center">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                @if($sub->status === 'active') bg-emerald-100 text-emerald-800
                                @elseif($sub->status === 'paused') bg-amber-100 text-amber-800
                                @elseif($sub->status === 'cancelled') bg-rose-100 text-rose-800
                                @else bg-slate-100 text-slate-700 @endif">
                                {{ $sub->status }}
                            </span>
                        </td>
                        <td class="py-3.5 px-2 text-right">
                            <button type="button" 
                                    class="btn-manage-sub px-2.5 py-1 rounded-lg border border-slate-200 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 font-bold text-[11px] transition"
                                    data-sub="{{ json_encode($sub) }}">
                                Manage
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $subscriptions->links() }}
        </div>
        @endif
    </div>

</div>

<!-- MODAL: ADD / EDIT SUBSCRIPTION PLAN -->
<div id="planModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 id="planModalTitle" class="text-base font-extrabold text-slate-900">Add New Subscription Plan</h3>
            <button type="button" id="btnClosePlanModal" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="planForm" action="{{ route('admin.subscriptions.plans.store') }}" method="POST" class="space-y-4">
            @csrf
            <div id="planFormMethod"></div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Plan Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="plan_name" required placeholder="e.g. Monthly Daily Supply" class="w-full px-3.5 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Frequency <span class="text-rose-500">*</span></label>
                    <select name="frequency" id="plan_frequency" required class="w-full px-3 py-2.5 border rounded-xl text-xs font-semibold bg-white">
                        <option value="daily">Daily (Every Day)</option>
                        <option value="alternate_days">Alternate Days (Every 2 Days)</option>
                        <option value="weekly">Weekly (Once a Week)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deliveries Count <span class="text-rose-500">*</span></label>
                    <input type="number" name="deliveries_count" id="plan_deliveries_count" min="1" max="365" value="30" required class="w-full px-3.5 py-2.5 border rounded-xl text-xs font-bold">
                    <p class="text-[10px] text-slate-400 mt-0.5">e.g. 30 for monthly, 15 for alternate days, 4 for weekly</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Discount Percentage (%) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" max="100" name="discount_percentage" id="plan_discount_percentage" value="15.00" required class="w-full px-3.5 py-2.5 border rounded-xl text-xs font-bold pr-8">
                        <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400">%</span>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Badge Text (Optional)</label>
                    <input type="text" name="badge" id="plan_badge" placeholder="e.g. 15% OFF • Most Popular" class="w-full px-3.5 py-2.5 border rounded-xl text-xs font-semibold">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description (Optional)</label>
                <textarea name="description" id="plan_description" rows="2" placeholder="Brief plan summary visible to customers..." class="w-full px-3.5 py-2.5 border rounded-xl text-xs font-semibold"></textarea>
            </div>

            <div class="pt-1">
                <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" id="plan_is_active" value="1" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <span>Active and Available for Customers to Subscribe</span>
                </label>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2 border-t">
                <button type="button" id="btnCancelPlanModal" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" id="btnSubmitPlan" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-md transition">
                    Save Plan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: MANAGE CUSTOMER SUBSCRIPTION STATUS -->
<div id="manageSubModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-extrabold text-slate-900">Manage Customer Subscription</h3>
            <button type="button" id="btnCloseManageSubModal" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="manageSubForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <span class="text-xs text-slate-400 block font-semibold">Subscription Number</span>
                <span id="manageSubNumber" class="text-sm font-mono font-bold text-indigo-600"></span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Update Status</label>
                <select name="status" id="manageSubStatus" required class="w-full px-3 py-2.5 border rounded-xl text-xs font-semibold bg-white">
                    <option value="active">Active (Resume delivery schedule)</option>
                    <option value="paused">Paused (Temporarily hold refills)</option>
                    <option value="cancelled">Cancelled (Cancel remaining refills)</option>
                    <option value="completed">Completed (Mark all finished)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Reason / Note (Optional)</label>
                <textarea name="reason" id="manageSubReason" rows="2" placeholder="e.g. Customer requested pause due to vacation" class="w-full px-3 py-2 border rounded-xl text-xs font-semibold"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2 border-t">
                <button type="button" id="btnCancelManageSubModal" class="px-4 py-2 rounded-xl border text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-md transition">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const planModal = $('#planModal');
    const planForm = $('#planForm');
    const planModalTitle = $('#planModalTitle');
    const planMethodDiv = $('#planFormMethod');

    // Open Add Plan Modal
    $('#btnOpenNewPlanModal').on('click', function() {
        planForm[0].reset();
        planMethodDiv.empty();
        planForm.attr('action', "{{ route('admin.subscriptions.plans.store') }}");
        planModalTitle.text('Add New Subscription Plan');
        $('#plan_frequency').val('daily');
        $('#plan_deliveries_count').val(30);
        $('#plan_discount_percentage').val('15.00');
        $('#plan_is_active').prop('checked', true);
        planModal.removeClass('hidden').addClass('flex');
    });

    // Open Edit Plan Modal
    $('.btn-edit-plan').on('click', function() {
        const plan = $(this).data('plan');
        planForm[0].reset();
        planMethodDiv.empty();

        const updateUrl = "{{ route('admin.subscriptions.plans.update', ':id') }}".replace(':id', plan.id);
        planForm.attr('action', updateUrl);

        planModalTitle.text('Edit Subscription Plan: ' + plan.name);
        $('#plan_name').val(plan.name);
        $('#plan_frequency').val(plan.frequency);
        $('#plan_deliveries_count').val(plan.deliveries_count);
        $('#plan_discount_percentage').val(parseFloat(plan.discount_percentage));
        $('#plan_badge').val(plan.badge || '');
        $('#plan_description').val(plan.description || '');
        $('#plan_is_active').prop('checked', !!plan.is_active);

        planModal.removeClass('hidden').addClass('flex');
    });

    // Close Plan Modal
    $('#btnClosePlanModal, #btnCancelPlanModal').on('click', function() {
        planModal.removeClass('flex').addClass('hidden');
    });

    planModal.on('click', function(e) {
        if (e.target === this) {
            planModal.removeClass('flex').addClass('hidden');
        }
    });

    // Manage Customer Subscription Modal
    const manageModal = $('#manageSubModal');
    const manageForm = $('#manageSubForm');

    $('.btn-manage-sub').on('click', function() {
        const sub = $(this).data('sub');
        $('#manageSubNumber').text(sub.subscription_number + ' (' + (sub.user ? sub.user.name : 'Customer') + ')');
        $('#manageSubStatus').val(sub.status);
        $('#manageSubReason').val(sub.cancellation_reason || '');

        const statusUrl = "{{ route('admin.subscriptions.status', ':id') }}".replace(':id', sub.id);
        manageForm.attr('action', statusUrl);

        manageModal.removeClass('hidden').addClass('flex');
    });

    $('#btnCloseManageSubModal, #btnCancelManageSubModal').on('click', function() {
        manageModal.removeClass('flex').addClass('hidden');
    });

    manageModal.on('click', function(e) {
        if (e.target === this) {
            manageModal.removeClass('flex').addClass('hidden');
        }
    });
});
</script>
@endpush
@endsection
