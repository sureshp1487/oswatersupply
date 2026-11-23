@extends('layouts.customer')

@section('title', 'Order #' . $order->order_number . ' Tracking')

@section('content')
<div class="px-4 py-4 space-y-4">
    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('customer.orders') }}" class="flex items-center space-x-2 text-xs font-bold text-slate-600 hover:text-ocean-600">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Orders</span>
        </a>
        <span class="text-xs font-mono font-bold text-slate-400">#{{ $order->order_number }}</span>
    </div>

    <!-- Order Status Summary Card -->
    <div class="rounded-3xl p-5 text-white shadow-xl {{ $order->order_status === 'cancelled' ? 'bg-gradient-to-r from-rose-700 to-red-600' : 'bg-gradient-to-r from-ocean-700 to-sky-600' }}">
        <div class="flex items-center justify-between mb-3">
            <div>
                <span class="text-[10px] uppercase tracking-wider text-white/80 font-bold block">Current Order Status</span>
                <h3 class="text-xl font-extrabold capitalize">{{ str_replace('_', ' ', $order->order_status) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl">
                @if($order->order_status === 'delivered')
                    <i class="fa-solid fa-circle-check"></i>
                @elseif($order->order_status === 'out_for_delivery')
                    <i class="fa-solid fa-truck-fast"></i>
                @elseif($order->order_status === 'cancelled')
                    <i class="fa-solid fa-ban"></i>
                @else
                    <i class="fa-solid fa-bottle-water"></i>
                @endif
            </div>
        </div>

        <div class="pt-3 border-t border-white/20 flex items-center justify-between text-xs text-sky-100">
            <span>Scheduled Delivery:</span>
            <span class="font-bold text-white">{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }} ({{ $order->slot?->name ?? 'Standard' }})</span>
        </div>
    </div>

    @if($order->order_status === 'cancelled')
    <!-- Cancellation Detail Alert Box -->
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 space-y-1.5 shadow-sm text-xs">
        <div class="flex items-center space-x-2 font-black text-rose-800 uppercase tracking-wider text-[11px]">
            <i class="fa-solid fa-circle-xmark text-sm"></i>
            <span>Order Cancelled</span>
        </div>
        @if($order->cancellation_reason)
            <div class="text-rose-700 pt-0.5">
                <span class="font-bold text-rose-900">Reason:</span> {{ $order->cancellation_reason }}
            </div>
        @endif
        @if($order->cancelled_at)
            <div class="text-[11px] text-rose-500">
                Cancelled on {{ $order->cancelled_at->format('d M Y, h:i A') }}
            </div>
        @endif
    </div>
    @endif

    <!-- Visual Tracking Progress Timeline -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm space-y-4">
        <h4 class="text-xs uppercase font-extrabold tracking-wider text-slate-900 border-b pb-2">Delivery Timeline</h4>

        @php
            $statuses = [
                'placed' => ['label' => 'Order Placed', 'desc' => 'Order details received in system'],
                'confirmed' => ['label' => 'Order Confirmed', 'desc' => 'Stock verified and driver assigned'],
                'preparing' => ['label' => 'Cans Sanitized & Dispatched', 'desc' => 'Loaded into delivery vehicle'],
                'out_for_delivery' => ['label' => 'Out for Delivery', 'desc' => 'Driver en route to your location'],
                'delivered' => ['label' => 'Delivered to Doorstep', 'desc' => 'Water can successfully delivered']
            ];

            $statusOrder = ['placed', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
            $currentIndex = array_search($order->order_status, $statusOrder);
            if ($currentIndex === false && $order->order_status !== 'cancelled') {
                $currentIndex = 0;
            }
        @endphp

        <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
            @foreach($statusOrder as $idx => $step)
            @php
                $isCompleted = ($order->order_status !== 'cancelled') && ($currentIndex !== false && $currentIndex >= $idx);
                $isCurrent = ($order->order_status !== 'cancelled') && ($currentIndex === $idx);
            @endphp
            <div class="relative">
                <!-- Status Dot -->
                <div class="absolute -left-6 top-0.5 w-5 h-5 rounded-full flex items-center justify-center text-[10px]
                    {{ $isCompleted ? 'bg-ocean-600 text-white ring-4 ring-sky-100' : 'bg-slate-200 text-slate-400' }}">
                    @if($isCompleted)
                        <i class="fa-solid fa-check"></i>
                    @else
                        <span>{{ $idx + 1 }}</span>
                    @endif
                </div>

                <div>
                    <h5 class="text-xs font-bold {{ $isCurrent ? 'text-ocean-700 font-extrabold' : ($isCompleted ? 'text-slate-800' : 'text-slate-400') }}">
                        {{ $statuses[$step]['label'] }}
                    </h5>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $statuses[$step]['desc'] }}</p>
                </div>
            </div>
            @endforeach

            @if($order->order_status === 'cancelled')
            <div class="relative">
                <!-- Cancelled Dot -->
                <div class="absolute -left-6 top-0.5 w-5 h-5 rounded-full flex items-center justify-center text-[10px] bg-rose-600 text-white ring-4 ring-rose-100">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <div>
                    <h5 class="text-xs font-bold text-rose-700 font-extrabold">Order Cancelled</h5>
                    <p class="text-[11px] text-rose-500 mt-0.5">
                        {{ $order->cancellation_reason ?: 'Cancelled by customer' }}
                        @if($order->cancelled_at)
                            &bull; {{ $order->cancelled_at->format('d M Y, h:i A') }}
                        @endif
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Items & Delivery Details -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm space-y-3">
        <h4 class="text-xs uppercase font-extrabold tracking-wider text-slate-900 border-b pb-2">Order Items</h4>

        <div class="space-y-2">
            @foreach($order->items as $item)
            <div class="flex items-center justify-between text-xs py-1">
                <div>
                    <div class="font-bold text-slate-800">{{ $item->product_name }}</div>
                    <div class="text-[11px] text-slate-400">{{ $item->volume_snapshot }} • Qty: {{ $item->quantity }}</div>
                </div>
                <div class="font-extrabold text-slate-900">₹{{ number_format($item->total_price, 2) }}</div>
            </div>
            @endforeach
        </div>

        <div class="pt-3 border-t border-slate-100 space-y-1 text-xs">
            <div class="flex justify-between text-slate-500">
                <span>Subtotal:</span>
                <span>₹{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between text-slate-500">
                <span>Delivery Fee:</span>
                <span class="text-emerald-600 font-semibold">FREE</span>
            </div>
            <div class="flex justify-between font-extrabold text-slate-900 text-sm pt-1 border-t">
                <span>Total Amount:</span>
                <span>₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Delivery Address Details -->
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm space-y-2 text-xs">
        <h4 class="text-xs uppercase font-extrabold tracking-wider text-slate-900">Delivery Address</h4>
        <p class="text-slate-700 leading-relaxed">
            <strong>{{ $order->address_snapshot['door_number'] ?? '' }}</strong>, {{ $order->address_snapshot['house_or_building'] ?? '' }}, {{ $order->address_snapshot['street'] ?? '' }}, {{ $order->address_snapshot['area_locality'] ?? '' }}, {{ $order->address_snapshot['city'] ?? '' }} - {{ $order->address_snapshot['pincode'] ?? '' }}
        </p>
        @if($order->delivery_instructions)
            <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-100 text-amber-800 text-[11px]">
                <strong>Instructions:</strong> {{ $order->delivery_instructions }}
            </div>
        @endif
    </div>

    <!-- Payment & Outstanding Due Handling -->
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <h4 class="text-xs uppercase font-extrabold tracking-wider text-slate-900">Payment Status</h4>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                {{ $order->payment_status }}
            </span>
        </div>

        <div class="flex items-center justify-between text-xs text-slate-600">
            <span>Payment Method:</span>
            <span class="font-bold uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
        </div>

        @if($order->due_amount > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-center justify-between">
            <div>
                <span class="text-[10px] text-amber-700 uppercase font-bold block">Pending Due on this Order</span>
                <span class="text-base font-extrabold text-amber-900">₹{{ number_format($order->due_amount, 2) }}</span>
            </div>
            <button type="button" id="btnPayOrderOnline" class="bg-ocean-600 hover:bg-ocean-700 text-white font-bold text-xs px-4 py-2 rounded-xl shadow active:scale-95 transition">
                Pay Now (Razorpay)
            </button>
        </div>
        @endif
    </div>

    <!-- Self-Service Order Cancellation Card -->
    @if($order->canBeCancelled())
    <div class="bg-white rounded-2xl p-4 border border-rose-100 shadow-sm flex items-center justify-between">
        <div>
            <h4 class="text-xs font-bold text-slate-800">Need to cancel this order?</h4>
            <p class="text-[11px] text-slate-400">You can cancel before water cans leave for delivery.</p>
        </div>
        <button type="button" onclick="$('#cancelOrderModal').removeClass('hidden')" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold text-xs border border-rose-200 transition active:scale-95 flex items-center space-x-1.5 shadow-sm">
            <i class="fa-solid fa-ban text-[11px]"></i>
            <span>Cancel Order</span>
        </button>
    </div>
    @elseif(in_array($order->order_status, ['preparing', 'out_for_delivery']))
    <div class="bg-amber-50/80 rounded-2xl p-3.5 border border-amber-200/60 flex items-start space-x-2.5 text-xs text-amber-900">
        <i class="fa-solid fa-truck-ramp-box text-amber-600 text-sm shrink-0 mt-0.5"></i>
        <div class="leading-relaxed">
            <span class="font-bold">Order in transit / packing:</span> This order is loaded or on the way. If you need urgent assistance, call our helpline: <a href="tel:{{ $appSettings['business_phone'] ?? '+919876543210' }}" class="font-bold text-amber-700 underline">{{ $appSettings['business_phone'] ?? '+91 98765 43210' }}</a>.
        </div>
    </div>
    @endif

    <!-- Repeat Order Action -->
    <a href="{{ route('customer.book') }}" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold text-center block shadow transition">
        Repeat or Place Another Order
    </a>
</div>

<!-- Order Cancellation Modal -->
@if($order->canBeCancelled())
<div id="cancelOrderModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center space-x-2 text-rose-600">
                <div class="w-8 h-8 rounded-xl bg-rose-50 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Cancel Order #{{ $order->order_number }}</h3>
            </div>
            <button type="button" onclick="$('#cancelOrderModal').addClass('hidden')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Please select a reason for cancelling this delivery order. Any reserved water cans will be returned to stock.
        </p>

        <form id="cancelOrderForm" action="{{ route('customer.order.cancel', $order->id) }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <!-- Cancellation Reason Preset -->
            <div>
                <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">
                    Reason for Cancellation <span class="text-rose-500">*</span>
                </label>
                <select name="reason_preset" id="cancelReasonPreset" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-medium focus:ring-2 focus:ring-rose-500 focus:bg-white text-xs">
                    <option value="" disabled selected>-- Select a reason --</option>
                    <option value="Ordered by mistake">Ordered by mistake</option>
                    <option value="Change in delivery time or date">Change in delivery time or date</option>
                    <option value="Delivery taking too long / needed urgently">Delivery taking too long / needed urgently</option>
                    <option value="Already purchased water from elsewhere">Already purchased water from elsewhere</option>
                    <option value="Entered wrong delivery address or quantity">Entered wrong delivery address or quantity</option>
                    <option value="Other reason">Other reason (please specify below)</option>
                </select>
            </div>

            <!-- Custom / Additional Cancellation Reason Details Input -->
            <div>
                <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">
                    Cancellation Details / Additional Notes
                </label>
                <textarea name="reason_details" id="cancelReasonDetails" rows="3" placeholder="Please provide any additional details or notes about your cancellation..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-medium focus:ring-2 focus:ring-rose-500 focus:bg-white text-xs resize-none"></textarea>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="$('#cancelOrderModal').addClass('hidden')" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">
                    Keep Order
                </button>
                <button type="submit" id="btnSubmitCancel" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-black shadow-md shadow-rose-600/30 transition flex items-center space-x-2 active:scale-95">
                    <i class="fa-solid fa-ban text-[11px]"></i>
                    <span>Confirm Cancellation</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#btnPayOrderOnline').on('click', function() {
        const orderId = "{{ $order->id }}";
        const dueAmount = {{ $order->due_amount }};

        $(this).attr('disabled', 'disabled').text('Connecting...');

        $.post("{{ route('customer.razorpay.order') }}", {
            amount: dueAmount,
            receipt: 'PAY_ORD_' + orderId
        }).done(function(data) {
            const options = {
                "key": "{{ env('RAZORPAY_KEY_ID', 'rzp_test_waterdelivery101') }}",
                "amount": data.amount,
                "currency": "INR",
                "name": "AquaFlow Water Delivery",
                "description": "Clear Due for Order #{{ $order->order_number }}",
                "order_id": data.id,
                "handler": function (res) {
                    $.post("/app/payments/pay-order/" + orderId, {
                        amount: dueAmount,
                        razorpay_payment_id: res.razorpay_payment_id,
                        razorpay_order_id: res.razorpay_order_id,
                        razorpay_signature: res.razorpay_signature
                    }).done(function(r) {
                        Swal.fire('Paid!', r.message, 'success').then(() => location.reload());
                    }).fail(function() {
                        Swal.fire('Error', 'Failed recording payment.', 'error');
                    });
                },
                "theme": { "color": "#0284c7" }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        }).fail(function() {
            Swal.fire('Error', 'Unable to reach payment gateway.', 'error');
            $('#btnPayOrderOnline').removeAttr('disabled').text('Pay Now (Razorpay)');
        });
    });

    // Handle Self-Service Order Cancellation
    $('#cancelOrderForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const preset = $('#cancelReasonPreset').val();

        if (!preset) {
            Swal.fire({
                icon: 'warning',
                title: 'Cancellation Reason Required',
                text: 'Please select a reason for cancellation from the list.',
                confirmButtonColor: '#0284c7'
            });
            return;
        }

        Swal.fire({
            title: 'Confirm Order Cancellation',
            text: 'Are you sure you want to cancel Order #{{ $order->order_number }}? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Cancel Order',
            cancelButtonText: 'Keep My Order'
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = $('#btnSubmitCancel');
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Cancelling...</span>');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    dataType: 'json'
                }).done(function(res) {
                    $('#cancelOrderModal').addClass('hidden');
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Cancelled',
                        text: res.message || 'Your order has been cancelled successfully.',
                        confirmButtonColor: '#0284c7'
                    }).then(() => {
                        location.reload();
                    });
                }).fail(function(xhr) {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-ban text-[11px]"></i> <span>Confirm Cancellation</span>');
                    const err = xhr.responseJSON?.message || 'Unable to cancel order at this moment. Please try again.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Cancellation Failed',
                        text: err,
                        confirmButtonColor: '#0284c7'
                    });
                });
            }
        });
    });
});
</script>
@endpush
