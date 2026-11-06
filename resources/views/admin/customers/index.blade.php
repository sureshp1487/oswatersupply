@extends('layouts.admin')

@section('page_title', 'Customer Directory')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold shadow-sm">
            <div class="flex items-center space-x-2 mb-1">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                <span>Please fix the following issues:</span>
            </div>
            <ul class="list-disc pl-5 space-y-0.5 text-[11px]">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Search filter -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <form method="GET" class="flex flex-col sm:flex-row gap-3 text-xs">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer by name, mobile, email..." class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl font-medium focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <button type="submit" class="px-6 py-2.5 bg-sky-600 text-white rounded-xl font-bold shadow hover:bg-sky-700 transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.customers.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition text-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900">Registered Customers</h3>
            <span class="text-xs text-slate-400 font-semibold">{{ $customers->total() }} Customers</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase tracking-wider text-[10px]">
                        <th class="p-4">Customer Name</th>
                        <th class="p-4">Mobile</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Default Address</th>
                        <th class="p-4">Due Balance</th>
                        <th class="p-4">Cans Held</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $c)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-4 font-bold text-slate-900">
                            <div class="flex items-center space-x-2">
                                <span>{{ $c->name }}</span>
                                @if($c->status === 'blocked')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-100 text-rose-700">Blocked</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-4 font-mono font-bold text-slate-700">+91 {{ $c->phone }}</td>
                        <td class="p-4 text-slate-500">{{ $c->email ?? '-' }}</td>
                        <td class="p-4 text-slate-600 max-w-xs truncate" title="{{ $c->defaultAddress ? $c->defaultAddress->formatted_address : ($c->addresses->first()?->formatted_address ?? 'No address') }}">
                            {{ $c->defaultAddress ? $c->defaultAddress->formatted_address : ($c->addresses->first()?->formatted_address ?? 'No address') }}
                        </td>
                        <td class="p-4">
                            <span class="font-extrabold {{ $c->customerProfile && $c->customerProfile->total_due_balance > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                ₹{{ number_format($c->customerProfile?->total_due_balance ?? 0, 2) }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-700 font-bold">
                            {{ $c->customerProfile?->total_empty_cans_held ?? 0 }}
                        </td>
                        <td class="p-4 text-center relative">
                            <!-- 3-Dots Vertical Action Menu -->
                            <div class="relative inline-block text-left dropdown-container">
                                <button type="button" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition btn-dropdown-toggle">
                                    <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                                </button>

                                <!-- Dropdown Menu Popover -->
                                <div class="hidden dropdown-menu fixed sm:absolute right-4 sm:right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50 animate-in fade-in zoom-in-95">
                                    <!-- View Action -->
                                    <a href="{{ route('admin.customers.detail', $c->id) }}" class="flex items-center space-x-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 hover:text-sky-600 transition font-medium">
                                        <i class="fa-solid fa-eye text-sky-600 w-4"></i>
                                        <span>View Profile</span>
                                    </a>

                                    <!-- Edit Action -->
                                    <button type="button" 
                                            onclick="openEditCustomerModal({{ json_encode([
                                                'id' => $c->id,
                                                'name' => $c->name,
                                                'phone' => $c->phone,
                                                'email' => $c->email,
                                                'status' => $c->status
                                            ]) }})"
                                            class="w-full flex items-center space-x-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 hover:text-amber-600 transition font-medium text-left">
                                        <i class="fa-solid fa-pen-to-square text-amber-500 w-4"></i>
                                        <span>Edit Customer</span>
                                    </button>

                                    <div class="my-1 border-t border-slate-100"></div>

                                    <!-- Delete Action -->
                                    <button type="button" 
                                            onclick="confirmDeleteCustomer({{ $c->id }}, '{{ addslashes($c->name) }}', {{ $c->customerProfile?->total_due_balance ?? 0 }})"
                                            class="w-full flex items-center space-x-2.5 px-4 py-2.5 text-xs text-rose-600 hover:bg-rose-50 transition font-medium text-left">
                                        <i class="fa-solid fa-trash-can text-rose-500 w-4"></i>
                                        <span>Delete Customer</span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">
                            <i class="fa-solid fa-user-slash text-2xl text-slate-300 mb-2 block"></i>
                            No customers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $customers->links() }}
        </div>
    </div>

</div>

<!-- Edit Customer Modal -->
<div id="editCustomerModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-5 animate-in fade-in zoom-in-95">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-pen text-sm"></i>
                </div>
                <h3 class="text-sm font-extrabold text-slate-900">Edit Customer Information</h3>
            </div>
            <button type="button" onclick="closeEditCustomerModal()" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form id="editCustomerForm" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="editCustomerName" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Number <span class="text-rose-500">*</span></label>
                <input type="text" name="phone" id="editCustomerPhone" required maxlength="10" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email ID <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="editCustomerEmail" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Account Status <span class="text-rose-500">*</span></label>
                <select name="status" id="editCustomerStatus" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="active">Active</option>
                    <option value="blocked">Blocked / Suspended</option>
                </select>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditCustomerModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-extrabold shadow-md shadow-sky-600/20 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Form for Delete Request -->
<form id="deleteCustomerForm" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    // Toggle 3-dots dropdown menu
    $(document).on('click', '.btn-dropdown-toggle', function(e) {
        e.stopPropagation();
        const currentMenu = $(this).siblings('.dropdown-menu');
        $('.dropdown-menu').not(currentMenu).addClass('hidden');
        currentMenu.toggleClass('hidden');
    });

    // Close dropdown menu when clicking anywhere else
    $(document).on('click', function() {
        $('.dropdown-menu').addClass('hidden');
    });

    // Open Edit Customer Modal
    function openEditCustomerModal(customer) {
        $('.dropdown-menu').addClass('hidden');
        $('#editCustomerName').val(customer.name);
        $('#editCustomerPhone').val(customer.phone);
        $('#editCustomerEmail').val(customer.email || '');
        $('#editCustomerStatus').val(customer.status || 'active');

        const updateUrl = "{{ route('admin.customers.index') }}/" + customer.id;
        $('#editCustomerForm').attr('action', updateUrl);

        $('#editCustomerModal').removeClass('hidden');
    }

    // Close Edit Customer Modal
    function closeEditCustomerModal() {
        $('#editCustomerModal').addClass('hidden');
    }

    // Confirm and Delete Customer
    function confirmDeleteCustomer(customerId, customerName, dueBalance) {
        $('.dropdown-menu').addClass('hidden');

        if (dueBalance > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Delete Customer',
                html: `<b>${customerName}</b> has an outstanding due balance of <b>₹${dueBalance.toFixed(2)}</b>.<br><span class="text-xs text-slate-500">Please clear all dues before deleting this customer.</span>`,
                confirmButtonColor: '#0284c7'
            });
            return;
        }

        Swal.fire({
            title: 'Delete Customer?',
            html: `Are you sure you want to permanently delete <b>${customerName}</b>? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteUrl = "{{ route('admin.customers.index') }}/" + customerId;
                const form = $('#deleteCustomerForm');
                form.attr('action', deleteUrl);
                form.submit();
            }
        });
    }
</script>
@endsection
