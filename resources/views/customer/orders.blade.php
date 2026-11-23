@extends('layouts.customer')

@section('title', 'My Orders - AquaFlow')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Order History</h2>
            <p class="text-xs text-slate-500">Track and manage your scheduled water deliveries</p>
        </div>
        <a href="{{ route('customer.book') }}" class="px-4 py-2.5 bg-ocean-600 text-white rounded-xl text-xs font-bold shadow hover:bg-ocean-700 transition w-fit">
            + Book New Water Can
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($orders as $order)
        <a href="{{ route('customer.order.show', $order->id) }}" class="block bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-extrabold text-slate-900">#{{ $order->order_number }}</span>
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                    @if($order->order_status === 'delivered') bg-emerald-100 text-emerald-800
                    @elseif($order->order_status === 'out_for_delivery') bg-amber-100 text-amber-800
                    @elseif($order->order_status === 'cancelled') bg-rose-100 text-rose-800
                    @else bg-sky-100 text-ocean-800 @endif">
                    {{ str_replace('_', ' ', $order->order_status) }}
                </span>
            </div>

            <!-- Products snapshot -->
            <div class="my-3 border-y border-slate-50 py-2.5 space-y-1">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between text-xs text-slate-700">
                    <span class="font-medium">{{ $item->quantity }}x {{ $item->product_name }}</span>
                    <span class="font-bold text-slate-900">₹{{ number_format($item->total_price, 2) }}</span>
                </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                <div class="flex items-center space-x-1.5">
                    <i class="fa-regular fa-calendar-check text-ocean-600"></i>
                    <span>Delivery: {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase font-bold
                        {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $order->payment_status }}
                    </span>
                    <div class="text-sm font-black text-slate-900">₹{{ number_format($order->total_amount, 2) }}</div>
                </div>
            </div>

            @if($order->order_status === 'cancelled' && $order->cancellation_reason)
            <div class="mt-2.5 text-[11px] text-rose-700 bg-rose-50/80 rounded-xl px-3 py-1.5 flex items-center space-x-1.5 border border-rose-100">
                <i class="fa-solid fa-ban text-[10px] shrink-0 text-rose-500"></i>
                <span class="truncate font-medium"><span class="font-bold">Cancelled:</span> {{ $order->cancellation_reason }}</span>
            </div>
            @elseif($order->canBeCancelled())
            <div class="mt-2.5 pt-2 border-t border-slate-50 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Order placed • Ready for delivery</span>
                <span class="text-rose-600 font-extrabold hover:underline flex items-center space-x-1">
                    <span>Manage / Cancel</span>
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </span>
            </div>
            @endif
        </a>
        @empty
        <div class="col-span-2 text-center py-16 bg-white rounded-3xl border border-slate-100 p-8 space-y-4">
            <div class="w-16 h-16 rounded-full bg-sky-50 text-ocean-600 flex items-center justify-center text-3xl mx-auto">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div>
                <h4 class="text-base font-bold text-slate-800">No Orders Placed Yet</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">You haven't ordered any water cans yet. Place your first delivery order now!</p>
            </div>
            <a href="{{ route('customer.book') }}" class="inline-block px-6 py-3 rounded-2xl bg-ocean-600 text-white font-extrabold text-xs shadow hover:bg-ocean-700 transition">
                Book 20L Water Can
            </a>
        </div>
        @endforelse
    </div>

    <div class="pt-2">
        {{ $orders->links() }}
    </div>
</div>
@endsection
