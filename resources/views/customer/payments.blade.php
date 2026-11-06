@extends('layouts.customer')

@section('title', 'Payments & Dues - AquaFlow')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Payments & Due Account</h2>
            <p class="text-xs text-slate-500">Track outstanding balances, unpaid orders, and settlement receipts</p>
        </div>
        <a href="{{ route('customer.book') }}" class="text-xs font-bold text-ocean-600 hover:underline">
            + Book New Water Can
        </a>
    </div>

    <!-- Outstanding Balance Card -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-ocean-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 space-y-4 max-w-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wider text-slate-400 font-bold">Total Customer Outstanding Due</span>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                    {{ $profile->total_due_balance > 0 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                    {{ $profile->total_due_balance > 0 ? 'Pending Settlement' : 'All Clear' }}
                </span>
            </div>

            <div class="flex items-baseline space-x-2">
                <span class="text-4xl font-black text-white">₹{{ number_format($profile->total_due_balance, 2) }}</span>
                <span class="text-xs text-slate-400">INR</span>
            </div>

            @if($profile->total_due_balance > 0)
            <button type="button" id="btnPayAllDues" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-ocean-500 to-sky-500 hover:from-ocean-600 hover:to-sky-600 rounded-2xl text-xs font-extrabold shadow-lg shadow-sky-600/30 flex items-center justify-center space-x-2 transition active:scale-95">
                <i class="fa-solid fa-bolt"></i>
                <span>Pay Full Outstanding Balance (Razorpay)</span>
            </button>
            @endif
        </div>
    </div>

    <!-- Responsive Split: Left Unpaid Orders, Right Ledger Activity on Desktop -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Unpaid Orders Section -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs uppercase font-extrabold tracking-wider text-slate-900">Unpaid / Pay Later Orders</h3>
                <span class="text-xs text-slate-400 font-semibold">{{ $unpaidOrders->count() }} Orders Pending</span>
            </div>

            <div class="space-y-3">
                @forelse($unpaidOrders as $unpaid)
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm space-y-3 hover:shadow-md transition">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-extrabold text-slate-900 text-sm">Order #{{ $unpaid->order_number }}</span>
                        <span class="text-xs text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg font-bold">
                            Due: ₹{{ number_format($unpaid->due_amount, 2) }}
                        </span>
                    </div>

                    <div class="text-xs text-slate-500">
                        Booked: {{ \Carbon\Carbon::parse($unpaid->created_at)->format('d M Y, h:i A') }} • Scheduled Delivery: {{ \Carbon\Carbon::parse($unpaid->expected_delivery_date)->format('d M, Y') }}
                    </div>

                    <div class="pt-3 border-t border-slate-50 flex items-center justify-between">
                        <a href="{{ route('customer.order.show', $unpaid->id) }}" class="text-xs text-ocean-600 font-bold hover:underline">
                            View Order Details
                        </a>
                        <button type="button" class="btn-pay-single-order px-4 py-2 bg-ocean-600 text-white rounded-xl text-xs font-bold shadow hover:bg-ocean-700 active:scale-95 transition"
                                data-id="{{ $unpaid->id }}" data-amount="{{ $unpaid->due_amount }}" data-num="{{ $unpaid->order_number }}">
                            Pay ₹{{ number_format($unpaid->due_amount, 2) }}
                        </button>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl p-8 text-center border border-slate-100 text-slate-400 text-xs">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-3xl mb-2 block"></i>
                    No unpaid orders! All your water deliveries are fully settled.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Customer Payment Ledger (Double-Entry Record) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs uppercase font-extrabold tracking-wider text-slate-900">Payment & Account Ledger</h3>
                <span class="text-xs text-slate-400">Statement</span>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden divide-y divide-slate-100">
                @forelse($ledgerEntries as $entry)
                <div class="p-4 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm shrink-0
                            {{ $entry->entry_type === 'credit' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            <i class="fa-solid {{ $entry->entry_type === 'credit' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">{{ $entry->description }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($entry->created_at)->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>

                    <div class="text-right shrink-0">
                        <div class="font-black text-sm {{ $entry->entry_type === 'credit' ? 'text-emerald-600' : 'text-slate-800' }}">
                            {{ $entry->entry_type === 'credit' ? '-' : '+' }}₹{{ number_format($entry->amount, 2) }}
                        </div>
                        <div class="text-[10px] text-slate-400">Bal: ₹{{ number_format($entry->balance_after, 2) }}</div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    No ledger activity recorded yet.
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Pay Full Outstanding Balance
    $('#btnPayAllDues').on('click', function() {
        const fullDue = {{ $profile->total_due_balance }};
        if (fullDue <= 0) return;

        $(this).attr('disabled', 'disabled').text('Initiating...');

        $.post("{{ route('customer.razorpay.order') }}", {
            amount: fullDue,
            receipt: 'PAY_ALL_DUES_' + Date.now()
        }).done(function(order) {
            const options = {
                "key": "{{ env('RAZORPAY_KEY_ID', 'rzp_test_waterdelivery101') }}",
                "amount": order.amount,
                "currency": "INR",
                "name": "AquaFlow Water Delivery",
                "description": "Clear Full Outstanding Balance",
                "order_id": order.id,
                "handler": function (res) {
                    $.post("{{ route('customer.pay.all') }}", {
                        amount: fullDue,
                        razorpay_payment_id: res.razorpay_payment_id,
                        razorpay_order_id: res.razorpay_order_id,
                        razorpay_signature: res.razorpay_signature
                    }).done(function(r) {
                        Swal.fire('Cleared!', r.message, 'success').then(() => location.reload());
                    }).fail(function() {
                        Swal.fire('Error', 'Payment processing failed.', 'error');
                    });
                },
                "theme": { "color": "#0284c7" }
            };
            const rzp = new Razorpay(options);
            rzp.open();
        }).fail(function() {
            Swal.fire('Error', 'Gateway unreachable.', 'error');
            $('#btnPayAllDues').removeAttr('disabled').text('Pay Full Outstanding Balance (Razorpay)');
        });
    });

    // Pay Single Order Due
    $('.btn-pay-single-order').on('click', function() {
        const orderId = $(this).data('id');
        const amount = $(this).data('amount');
        const orderNum = $(this).data('num');
        const btn = $(this);

        btn.attr('disabled', 'disabled').text('...');

        $.post("{{ route('customer.razorpay.order') }}", {
            amount: amount,
            receipt: 'PAY_ORD_' + orderId
        }).done(function(order) {
            const options = {
                "key": "{{ env('RAZORPAY_KEY_ID', 'rzp_test_waterdelivery101') }}",
                "amount": order.amount,
                "currency": "INR",
                "name": "AquaFlow Water Delivery",
                "description": "Clear Due for Order #" + orderNum,
                "order_id": order.id,
                "handler": function (res) {
                    $.post("/app/payments/pay-order/" + orderId, {
                        amount: amount,
                        razorpay_payment_id: res.razorpay_payment_id,
                        razorpay_order_id: res.razorpay_order_id,
                        razorpay_signature: res.razorpay_signature
                    }).done(function(r) {
                        Swal.fire('Success!', r.message, 'success').then(() => location.reload());
                    }).fail(function() {
                        Swal.fire('Error', 'Payment recording failed.', 'error');
                    });
                },
                "theme": { "color": "#0284c7" }
            };
            const rzp = new Razorpay(options);
            rzp.open();
        }).fail(function() {
            Swal.fire('Error', 'Gateway unreachable.', 'error');
            btn.removeAttr('disabled').text('Pay');
        });
    });
});
</script>
@endpush
