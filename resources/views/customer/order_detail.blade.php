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
    <div class="bg-gradient-to-r from-ocean-700 to-sky-600 rounded-3xl p-5 text-white shadow-xl">
        <div class="flex items-center justify-between mb-3">
            <div>
                <span class="text-[10px] uppercase tracking-wider text-sky-200 font-bold block">Current Order Status</span>
                <h3 class="text-xl font-extrabold capitalize">{{ str_replace('_', ' ', $order->order_status) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl">
                @if($order->order_status === 'delivered')
                    <i class="fa-solid fa-circle-check"></i>
                @elseif($order->order_status === 'out_for_delivery')
                    <i class="fa-solid fa-truck-fast"></i>
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

    <!-- Payment & Pay Later Due Handling -->
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

    <!-- Repeat Order Action -->
    <a href="{{ route('customer.book') }}" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold text-center block shadow transition">
        Repeat or Place Another Order
    </a>
</div>
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
});
</script>
@endpush
