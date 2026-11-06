@extends('layouts.admin')

@section('page_title', 'Business Analytics & Dashboard')

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Revenue Card -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider block">Total Revenue</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">₹{{ number_format($metrics['total_revenue'], 2) }}</span>
                <span class="text-[11px] text-emerald-600 font-bold mt-1 inline-block">Today: +₹{{ number_format($metrics['today_revenue'], 2) }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
        </div>

        <!-- Total Outstanding Balance (Due) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider block">Outstanding Dues</span>
                <span class="text-2xl font-black text-rose-600 mt-1 block">₹{{ number_format($metrics['total_outstanding_dues'], 2) }}</span>
                <a href="{{ route('admin.dues.index') }}" class="text-[11px] text-sky-600 font-bold hover:underline mt-1 inline-block">Manage Dues &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
        </div>

        <!-- Today's Orders -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider block">Today's Orders</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $metrics['today_orders'] }}</span>
                <span class="text-[11px] text-slate-400 mt-1 inline-block">Total: {{ $metrics['total_orders'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- Registered Customers -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider block">Active Customers</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $metrics['total_customers'] }}</span>
                <span class="text-[11px] text-emerald-600 font-bold mt-1 inline-block">Recurring Subscribers</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 7-Day Revenue Trend Chart (Chart.js) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Weekly Revenue & Collection Trend</h3>
                    <p class="text-xs text-slate-400">Payment receipts recorded across all channels</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>

        <!-- Top Selling Water Cans -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900">Top Selling Water Cans</h3>
            <div class="space-y-3">
                @foreach($topProducts as $item)
                <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-100 last:border-none">
                    <div>
                        <span class="font-bold text-slate-800 block">{{ $item->product_name }}</span>
                        <span class="text-[11px] text-slate-400">{{ $item->total_qty }} units sold</span>
                    </div>
                    <span class="font-extrabold text-slate-900">₹{{ number_format($item->total_sales, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Order Fulfillment Pipeline -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Recent Customer Orders</h3>
                <p class="text-xs text-slate-400">Real-time delivery fulfillment updates</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-sky-600 hover:underline">
                View All Orders &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase tracking-wider text-[10px]">
                        <th class="p-4">Order #</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Scheduled Date</th>
                        <th class="p-4">Items</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Payment</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-4 font-mono font-bold text-slate-900">#{{ $order->order_number }}</td>
                        <td class="p-4">
                            <span class="font-bold text-slate-900 block">{{ $order->user->name }}</span>
                            <span class="text-slate-400 text-[11px]">{{ $order->user->phone }}</span>
                        </td>
                        <td class="p-4">
                            <span>{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}</span>
                            <span class="block text-[10px] text-slate-400">{{ $order->slot?->name ?? 'Standard' }}</span>
                        </td>
                        <td class="p-4 text-slate-600">
                            {{ $order->items->sum('quantity') }} cans
                        </td>
                        <td class="p-4 font-extrabold text-slate-900">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $order->payment_status }}
                            </span>
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
                            <a href="{{ route('admin.orders.detail', $order->id) }}" class="text-sky-600 hover:text-sky-800 font-bold">
                                Manage &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-6 text-center text-slate-400">No orders placed yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const chartData = @json($last7Days);
    const ctx = document.getElementById('revenueTrendChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.date),
            datasets: [{
                label: 'Revenue (₹)',
                data: chartData.map(d => d.revenue),
                borderColor: '#0284c7',
                backgroundColor: 'rgba(2, 132, 199, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#0284c7',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return '₹' + value; }
                    }
                }
            }
        }
    });
});
</script>
@endpush
