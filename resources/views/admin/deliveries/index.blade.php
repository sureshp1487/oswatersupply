@extends('layouts.admin')

@section('page_title', 'Daily Delivery Dispatch & Schedule')

@section('content')
<div class="space-y-6">

    <!-- Date Filter -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-sm font-extrabold text-slate-900">Delivery Route Schedule</h3>
            <p class="text-xs text-slate-400">View deliveries organized by time slot and assign routes to captains</p>
        </div>

        <form method="GET" class="flex items-center space-x-2 text-xs">
            <label class="font-bold text-slate-700">Filter Date:</label>
            <input type="date" name="date" value="{{ $date }}" class="px-3 py-2 border rounded-xl font-bold">
            <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-xl font-bold shadow">Load Schedule</button>
        </form>
    </div>

    <!-- Scheduled Orders List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($orders as $order)
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between border-b pb-2">
                    <span class="font-mono font-bold text-slate-900 text-xs">#{{ $order->order_number }}</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase
                        @if($order->order_status === 'delivered') bg-emerald-100 text-emerald-800
                        @elseif($order->order_status === 'out_for_delivery') bg-amber-100 text-amber-800
                        @else bg-sky-100 text-sky-800 @endif">
                        {{ str_replace('_', ' ', $order->order_status) }}
                    </span>
                </div>

                <div class="space-y-1 text-xs">
                    <div class="font-extrabold text-slate-900 text-sm">{{ $order->user->name }}</div>
                    <div class="text-slate-500 font-mono">+91 {{ $order->user->phone }}</div>
                    <div class="text-slate-600 line-clamp-2 mt-1">
                        {{ $order->address_snapshot['door_number'] ?? '' }}, {{ $order->address_snapshot['street'] ?? '' }}, {{ $order->address_snapshot['area_locality'] ?? '' }}
                    </div>
                </div>

                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs space-y-1">
                    <div class="flex justify-between font-bold text-slate-800">
                        <span>Time Slot:</span>
                        <span class="text-sky-700">{{ $order->slot?->name ?? 'Standard' }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Items:</span>
                        <span>{{ $order->items->sum('quantity') }} Cans</span>
                    </div>
                    @if($order->delivery_instructions)
                    <div class="text-[11px] text-amber-800 pt-1 border-t">
                        <strong>Note:</strong> {{ $order->delivery_instructions }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="pt-2 border-t flex items-center justify-between">
                <div>
                    @if($order->deliveryAssignment)
                        <span class="text-[10px] bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded">
                            {{ $order->deliveryAssignment->driver->name }}
                        </span>
                    @else
                        <span class="text-[10px] text-slate-400">Unassigned</span>
                    @endif
                </div>

                <a href="{{ route('admin.orders.detail', $order->id) }}" class="text-xs text-sky-600 font-bold hover:underline">
                    Dispatch Console &rarr;
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-3xl p-12 text-center text-slate-400 text-xs border border-slate-200">
            No deliveries scheduled for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}.
        </div>
        @endforelse
    </div>

</div>
@endsection
