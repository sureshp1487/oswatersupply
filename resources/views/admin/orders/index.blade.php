@extends('layouts.admin')

@section('page_title', 'Order Management & Dispatch')

@section('content')
<div class="space-y-6">

    <!-- Filter Header -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Search Customer or Order #</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="AQF-XXXX or Name/Phone" class="w-full px-3 py-2 border rounded-xl">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Order Status</label>
                <select name="order_status" class="w-full px-3 py-2 border rounded-xl">
                    <option value="">All Statuses</option>
                    @foreach(['placed', 'confirmed', 'preparing', 'out_for_delivery', 'delivered', 'cancelled'] as $st)
                    <option value="{{ $st }}" {{ request('order_status') === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Payment Status</label>
                <select name="payment_status" class="w-full px-3 py-2 border rounded-xl">
                    <option value="">All Payments</option>
                    @foreach(['pending', 'partially_paid', 'paid', 'failed'] as $pst)
                    <option value="{{ $pst }}" {{ request('payment_status') === $pst ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $pst)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Delivery Date</label>
                <input type="date" name="delivery_date" value="{{ request('delivery_date') }}" class="w-full px-3 py-2 border rounded-xl">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl font-bold shadow">
                    Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase tracking-wider text-[10px]">
                        <th class="p-4">Order #</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Delivery Scheduled</th>
                        <th class="p-4">Items</th>
                        <th class="p-4">Financials</th>
                        <th class="p-4">Delivery Captain</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-4 font-mono font-bold text-slate-900">#{{ $order->order_number }}</td>
                        <td class="p-4">
                            <span class="font-bold text-slate-900 block">{{ $order->user->name }}</span>
                            <span class="text-slate-400 text-[11px]">{{ $order->user->phone }}</span>
                            <span class="text-[10px] text-slate-400 block truncate max-w-xs">{{ $order->address_snapshot['area_locality'] ?? '' }}</span>
                        </td>
                        <td class="p-4">
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}</span>
                            <span class="block text-[10px] text-slate-400">{{ $order->slot?->name ?? 'Standard' }}</span>
                        </td>
                        <td class="p-4">
                            @foreach($order->items as $it)
                                <div class="text-[11px]">{{ $it->quantity }}x {{ $it->product_name }}</div>
                            @endforeach
                        </td>
                        <td class="p-4">
                            <div class="font-extrabold text-slate-900">₹{{ number_format($order->total_amount, 2) }}</div>
                            <div class="text-[10px] {{ $order->due_amount > 0 ? 'text-rose-600 font-bold' : 'text-emerald-600' }}">
                                Due: ₹{{ number_format($order->due_amount, 2) }}
                            </div>
                        </td>
                        <td class="p-4">
                            @if($order->deliveryAssignment)
                                <span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold">
                                    {{ $order->deliveryAssignment->driver->name }}
                                </span>
                            @else
                                <span class="text-slate-400 text-[11px]">Unassigned</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                @if($order->order_status === 'delivered') bg-emerald-100 text-emerald-800
                                @elseif($order->order_status === 'out_for_delivery') bg-amber-100 text-amber-800
                                @else bg-sky-100 text-sky-800 @endif">
                                {{ str_replace('_', ' ', $order->order_status) }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.orders.detail', $order->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-sky-600 hover:text-white rounded-xl font-bold transition">
                                Manage Order
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-6 text-center text-slate-400">No orders match query.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
