@extends('layouts.admin')

@section('page_title', 'Customer Profile: ' . $customer->name)

@section('content')
<div class="space-y-6">

    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
            &larr; Back to Customers
        </a>
    </div>

    <!-- Customer Overview Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center text-2xl font-black">
                {{ substr($customer->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-xl font-black text-slate-900">{{ $customer->name }}</h2>
                <div class="flex items-center space-x-3 text-xs text-slate-500 mt-1">
                    <span>Phone: +91 {{ $customer->phone }}</span>
                    <span>•</span>
                    <span>Email: {{ $customer->email ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-6">
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Spent</span>
                <span class="text-xl font-black text-emerald-600">₹{{ number_format($totalSpent, 2) }}</span>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Outstanding Due</span>
                <span class="text-xl font-black text-rose-600">₹{{ number_format($customer->customerProfile?->total_due_balance ?? 0, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden space-y-4 p-6">
        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Customer Order History</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b text-slate-400 uppercase text-[10px]">
                        <th class="py-2">Order #</th>
                        <th class="py-2">Scheduled Delivery</th>
                        <th class="py-2">Items</th>
                        <th class="py-2">Total Amount</th>
                        <th class="py-2">Payment Status</th>
                        <th class="py-2">Order Status</th>
                        <th class="py-2 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customer->orders as $o)
                    <tr>
                        <td class="py-3 font-mono font-bold text-slate-900">#{{ $o->order_number }}</td>
                        <td class="py-3">{{ \Carbon\Carbon::parse($o->expected_delivery_date)->format('M d, Y') }}</td>
                        <td class="py-3">
                            @foreach($o->items as $it)
                            <div>{{ $it->quantity }}x {{ $it->product_name }}</div>
                            @endforeach
                        </td>
                        <td class="py-3 font-bold text-slate-900">₹{{ number_format($o->total_amount, 2) }}</td>
                        <td class="py-3">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $o->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                {{ $o->payment_status }}
                            </span>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                {{ str_replace('_', ' ', $o->order_status) }}
                            </span>
                        </td>
                        <td class="py-3 text-right">
                            <a href="{{ route('admin.orders.detail', $o->id) }}" class="text-sky-600 font-bold hover:underline">
                                Details &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-slate-400">No orders placed by this customer.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
