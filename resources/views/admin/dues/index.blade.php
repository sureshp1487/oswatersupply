@extends('layouts.admin')

@section('page_title', 'Customer Outstanding Due Management')

@section('content')
<div class="space-y-6">

    <!-- Overall Outstanding Due Summary Banner -->
    <div class="bg-gradient-to-r from-rose-700 via-rose-600 to-amber-600 rounded-3xl p-6 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs uppercase tracking-wider text-rose-200 font-extrabold block">Total Business Receivables</span>
            <h2 class="text-3xl font-black mt-1">₹{{ number_format($totalOutstandingAcrossBusiness, 2) }}</h2>
            <p class="text-xs text-rose-100 mt-1">Total pending dues across all Pay Later orders</p>
        </div>

        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-white/20 backdrop-blur text-white border border-white/30 rounded-xl text-xs font-bold hover:bg-white/30 transition">
                <i class="fa-solid fa-file-export mr-1"></i> Export Due Statement
            </button>
        </div>
    </div>

    <!-- Search filter -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <form method="GET" class="flex flex-col sm:flex-row gap-3 text-xs">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer by name, mobile number, or email..." class="w-full px-4 py-2.5 border rounded-xl font-medium">
            </div>
            <button type="submit" class="px-6 py-2.5 bg-sky-600 text-white rounded-xl font-bold shadow hover:bg-sky-700">
                Search Dues
            </button>
        </form>
    </div>

    <!-- Customers with Outstanding Dues Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900">Unsettled Customer Accounts</h3>
            <span class="text-xs text-slate-400 font-semibold">{{ $customersWithDues->total() }} Customers with Dues</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase tracking-wider text-[10px]">
                        <th class="p-4">Customer Details</th>
                        <th class="p-4">Mobile</th>
                        <th class="p-4">Total Due Balance</th>
                        <th class="p-4">Cans With Customer</th>
                        <th class="p-4">Customer Since</th>
                        <th class="p-4 text-right">Settlement Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customersWithDues as $profile)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-4">
                            <span class="font-extrabold text-slate-900 text-sm block">{{ $profile->user->name }}</span>
                            <span class="text-slate-400 text-[11px]">{{ $profile->user->email ?? 'No email' }}</span>
                        </td>
                        <td class="p-4 font-mono font-bold text-slate-700">
                            +91 {{ $profile->user->phone }}
                        </td>
                        <td class="p-4">
                            <span class="font-black text-rose-600 text-base">₹{{ number_format($profile->total_due_balance, 2) }}</span>
                        </td>
                        <td class="p-4 text-slate-700 font-semibold">
                            {{ $profile->total_empty_cans_held }} cans
                        </td>
                        <td class="p-4 text-slate-500">
                            {{ \Carbon\Carbon::parse($profile->created_at)->format('M d, Y') }}
                        </td>
                        <td class="p-4 text-right">
                            <button type="button" class="btn-open-settle-modal px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow text-xs transition"
                                    data-id="{{ $profile->user_id }}" data-name="{{ $profile->user->name }}" data-due="{{ $profile->total_due_balance }}">
                                Record Offline Collection
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-2xl mb-1 block"></i>
                            Excellent! There are no unsettled customer dues at the moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $customersWithDues->links() }}
        </div>
    </div>

</div>

<!-- Due Settlement Modal -->
<div id="settleDueModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <div>
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Record Offline Settlement</h3>
                <span id="modalCustomerName" class="text-xs text-sky-600 font-bold block"></span>
            </div>
            <button type="button" id="closeSettleModal" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="settleDueForm" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Collection Amount (₹)</label>
                <input type="number" step="0.01" min="1" name="amount" id="modalSettleAmount" required class="w-full px-3 py-2 border rounded-xl text-xs font-bold">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Payment Method</label>
                <select name="payment_method" class="w-full px-3 py-2 border rounded-xl text-xs font-bold">
                    <option value="cash_offline">Cash Collection (Direct)</option>
                    <option value="upi_manual">Manual UPI / QR Scan</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Receipt / Remarks</label>
                <input type="text" name="notes" placeholder="e.g. Settle balance via shop cash" class="w-full px-3 py-2 border rounded-xl text-xs">
            </div>

            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow">
                Post to Ledger & Settle Dues
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-open-settle-modal').on('click', function() {
        const userId = $(this).data('id');
        const name = $(this).data('name');
        const due = $(this).data('due');

        $('#modalCustomerName').text(name + ' (Outstanding: ₹' + parseFloat(due).toFixed(2) + ')');
        $('#modalSettleAmount').val(due).attr('max', due);
        $('#settleDueForm').attr('action', '/admin/dues/' + userId + '/settle');
        $('#settleDueModal').removeClass('hidden').addClass('flex');
    });

    $('#closeSettleModal').on('click', function() {
        $('#settleDueModal').removeClass('flex').addClass('hidden');
    });
});
</script>
@endpush
