@extends('layouts.admin')

@section('page_title', 'Order #' . $order->order_number . ' Full Console')

@section('content')
<div class="space-y-6">

    <!-- Top Action Banner -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h2 class="text-xl font-black text-slate-900">#{{ $order->order_number }}</h2>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                    @if($order->order_status === 'delivered') bg-emerald-100 text-emerald-800
                    @elseif($order->order_status === 'out_for_delivery') bg-amber-100 text-amber-800
                    @else bg-sky-100 text-sky-800 @endif">
                    {{ str_replace('_', ' ', $order->order_status) }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                    {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                    {{ $order->payment_status }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">Placed on {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}</p>
        </div>

        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 border rounded-xl text-xs font-bold hover:bg-slate-50 transition">
                <i class="fa-solid fa-print mr-1"></i> Print Invoice
            </button>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">
                Back to Orders
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Details & Actions -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Ordered Water Cans Table -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Ordered Water Cans</h3>
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b text-slate-400 uppercase text-[10px]">
                            <th class="py-2">Item</th>
                            <th class="py-2">Capacity</th>
                            <th class="py-2">Unit Price</th>
                            <th class="py-2 text-center">Qty</th>
                            <th class="py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="py-3 font-bold text-slate-800">{{ $item->product_name }}</td>
                            <td class="py-3 text-slate-500">{{ $item->volume_snapshot }}</td>
                            <td class="py-3 text-slate-500">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 text-center font-bold">{{ $item->quantity }}</td>
                            <td class="py-3 text-right font-extrabold text-slate-900">₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pt-3 border-t space-y-1 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal:</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Delivery Fee:</span>
                        <span>₹{{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-black text-slate-900 text-base pt-2 border-t">
                        <span>Total Order Amount:</span>
                        <span>₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-600 font-bold pt-1">
                        <span>Paid Amount:</span>
                        <span>₹{{ number_format($order->paid_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between {{ $order->due_amount > 0 ? 'text-rose-600 font-black' : 'text-slate-400' }} text-sm">
                        <span>Pending Due:</span>
                        <span>₹{{ number_format($order->due_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Status & History Log -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Status Lifecycle & History</h3>

                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                    @csrf
                    <select name="order_status" class="px-3 py-2 border rounded-xl text-xs font-bold">
                        @foreach(['placed', 'confirmed', 'preparing', 'out_for_delivery', 'delivered', 'cancelled'] as $st)
                        <option value="{{ $st }}" {{ $order->order_status === $st ? 'selected' : '' }}>
                            Set as: {{ ucfirst(str_replace('_', ' ', $st)) }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" name="notes" placeholder="Optional notes for status update" class="flex-1 px-3 py-2 border rounded-xl text-xs">
                    <button type="submit" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow">
                        Update Status
                    </button>
                </form>

                <div class="space-y-3 pt-2">
                    @foreach($order->histories as $hist)
                    <div class="flex items-start space-x-3 text-xs border-l-2 border-sky-400 pl-3">
                        <div class="flex-1">
                            <span class="font-bold text-slate-800 capitalize">{{ str_replace('_', ' ', $hist->status) }}</span>
                            <p class="text-slate-500 mt-0.5">{{ $hist->notes }}</p>
                        </div>
                        <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($hist->created_at)->format('d M, h:i A') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Offline Payment Recording Form (For Pay Later / Due Orders) -->
            @if($order->due_amount > 0)
            <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 shadow-sm space-y-4">
                <div>
                    <h3 class="text-sm font-black text-amber-900 uppercase tracking-wider">Record Offline Payment (Cash / Manual UPI)</h3>
                    <p class="text-xs text-amber-700">Record cash or UPI payment collected from this customer to settle dues</p>
                </div>

                <form action="{{ route('admin.orders.record_payment', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-amber-900 mb-1">Amount to Record (₹)</label>
                            <input type="number" step="0.01" name="amount" value="{{ $order->due_amount }}" max="{{ $order->due_amount }}" required class="w-full px-3 py-2 rounded-xl border border-amber-300 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-amber-900 mb-1">Collection Channel</label>
                            <select name="payment_method" class="w-full px-3 py-2 rounded-xl border border-amber-300 text-xs font-bold">
                                <option value="cash_offline">Cash Collection</option>
                                <option value="upi_manual">Manual UPI / QR Transfer</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-amber-900 mb-1">Remarks</label>
                        <input type="text" name="notes" placeholder="e.g. Collected cash at warehouse / GPay transaction ref" class="w-full px-3 py-2 rounded-xl border border-amber-300 text-xs">
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow">
                        Record Payment & Settle Ledger
                    </button>
                </form>
            </div>
            @endif

        </div>

        <!-- Right Col: Customer & Dispatch Information -->
        <div class="space-y-6">

            <!-- Customer & Delivery Details -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Customer Details</h3>
                <div class="text-xs space-y-2">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Customer Name</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $order->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Phone Number</span>
                        <a href="tel:{{ $order->user->phone }}" class="text-sky-600 font-bold hover:underline">+91 {{ $order->user->phone }}</a>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Email</span>
                        <span class="text-slate-700">{{ $order->user->email ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="pt-3 border-t space-y-1 text-xs">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Destination Address</span>
                    <p class="text-slate-800 font-medium leading-relaxed">
                        {{ $order->address_snapshot['door_number'] ?? '' }}, {{ $order->address_snapshot['house_or_building'] ?? '' }}, {{ $order->address_snapshot['street'] ?? '' }}, {{ $order->address_snapshot['area_locality'] ?? '' }}, {{ $order->address_snapshot['city'] ?? '' }} - {{ $order->address_snapshot['pincode'] ?? '' }}
                    </p>
                    @if($order->delivery_instructions)
                    <div class="mt-2 p-2 bg-amber-50 text-amber-800 rounded-lg text-[11px]">
                        <strong>Notes:</strong> {{ $order->delivery_instructions }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Driver Assignment Panel -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Delivery Captain Assignment</h3>

                @if($order->deliveryAssignment)
                <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs space-y-1">
                    <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-700 block">Assigned Captain</span>
                    <span class="font-extrabold text-emerald-900 text-sm">{{ $order->deliveryAssignment->driver->name }}</span>
                    <span class="block text-slate-500">Phone: {{ $order->deliveryAssignment->driver->phone }}</span>
                    <span class="block text-[10px] text-slate-400">Assigned at: {{ \Carbon\Carbon::parse($order->deliveryAssignment->assigned_at)->format('d M, h:i A') }}</span>
                </div>
                @endif

                <form action="{{ route('admin.orders.assign_driver', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Select Driver</label>
                        <select name="driver_id" required class="w-full px-3 py-2 border rounded-xl text-xs font-semibold">
                            <option value="">-- Choose Captain --</option>
                            @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ $order->deliveryAssignment && $order->deliveryAssignment->driver_id == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }} ({{ $driver->phone }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow">
                        {{ $order->deliveryAssignment ? 'Reassign Driver' : 'Assign to Route' }}
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
