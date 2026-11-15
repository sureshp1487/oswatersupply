@extends('layouts.admin')

@section('page_title', 'Staff Users (Admins & Delivery Drivers)')

@section('content')
<div class="space-y-6">

    <!-- Header KPI Summary Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold mb-2">
                <i class="fa-solid fa-user-shield"></i>
                <span>Staff Access & Fleet Management</span>
            </div>
            <h2 class="text-lg font-black text-slate-900">System Users</h2>
            <p class="text-xs text-slate-500">Manage administrator privileges and delivery driver captains who handle doorstep dispatch.</p>
        </div>

        <button type="button" id="btnOpenNewUserModal" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow flex items-center space-x-2 shrink-0 transition">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Staff User</span>
        </button>
    </div>

    <!-- Quick Role Tabs & Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center space-x-2 text-xs">
            <a href="{{ route('admin.users.index', ['role' => 'all', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-xl font-bold transition flex items-center space-x-1.5 {{ $roleFilter === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                <span>All Staff</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $roleFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $adminCount + $driverCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-xl font-bold transition flex items-center space-x-1.5 {{ $roleFilter === 'admin' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                <i class="fa-solid fa-shield-halved text-[11px]"></i>
                <span>Administrators</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $roleFilter === 'admin' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $adminCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'driver', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-xl font-bold transition flex items-center space-x-1.5 {{ $roleFilter === 'driver' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                <i class="fa-solid fa-truck-fast text-[11px]"></i>
                <span>Delivery Drivers</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $roleFilter === 'driver' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $driverCount }}</span>
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center space-x-2">
            <input type="hidden" name="role" value="{{ $roleFilter }}">
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, phone, email..." 
                       class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.users.index', ['role' => $roleFilter]) }}" class="px-2 py-2 text-slate-400 hover:text-slate-600 text-xs" title="Clear search">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-extrabold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Staff Member</th>
                        <th class="py-3.5 px-5">Role</th>
                        <th class="py-3.5 px-5">Contact Details</th>
                        <th class="py-3.5 px-5">Activity / Fleet Status</th>
                        <th class="py-3.5 px-5">Account Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-2xl {{ $user->role === 'admin' ? 'bg-indigo-600' : 'bg-cyan-600' }} text-white flex items-center justify-center font-black text-xs shadow-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-extrabold text-slate-900 block text-xs">{{ $user->name }}</span>
                                    <span class="text-[10px] text-slate-400">Created {{ $user->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-5">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10px] font-black uppercase tracking-wider">
                                    <i class="fa-solid fa-shield-halved text-[9px]"></i>
                                    <span>Administrator</span>
                                </span>
                            @else
                                <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg bg-cyan-50 text-cyan-700 border border-cyan-200 text-[10px] font-black uppercase tracking-wider">
                                    <i class="fa-solid fa-truck-fast text-[9px]"></i>
                                    <span>Delivery Driver</span>
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5">
                            <div>
                                <span class="block text-slate-900 font-mono text-[11px]"><i class="fa-solid fa-phone text-[9px] text-slate-400 mr-1"></i>+91 {{ $user->phone }}</span>
                                <span class="block text-slate-400 text-[11px]"><i class="fa-solid fa-envelope text-[9px] text-slate-400 mr-1"></i>{{ $user->email }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-5">
                            @if($user->role === 'driver')
                                <div class="space-y-0.5 text-[11px]">
                                    <span class="text-amber-600 font-bold block">
                                        <i class="fa-solid fa-box-open mr-1"></i>{{ $user->active_deliveries_count }} active dispatches
                                    </span>
                                    <span class="text-slate-400 text-[10px] block">
                                        {{ $user->completed_deliveries_count }} total completed
                                    </span>
                                </div>
                            @else
                                <span class="text-slate-400 text-[11px]">Full Portal Access</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ $user->status }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button type="button" 
                                        class="btn-edit-user p-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 border border-slate-200 transition" 
                                        data-user="{{ json_encode($user) }}"
                                        title="Edit User">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>

                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this staff user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition" title="Delete User">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-user-slash text-3xl text-slate-300 mb-2 block"></i>
                            <span>No staff users found matching your criteria.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Modal: Add / Edit Staff User -->
<div id="userModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 id="userModalTitle" class="text-base font-extrabold text-slate-900">Add New Staff User</h3>
            <button type="button" id="btnCloseUserModal" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="userForm" action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div id="userFormMethod"></div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="user_name" required placeholder="e.g. Anand Kumar" class="w-full px-3.5 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Role <span class="text-rose-500">*</span></label>
                    <select name="role" id="user_role" required class="w-full px-3.5 py-2.5 border rounded-xl font-bold text-xs focus:ring-2 focus:ring-indigo-500">
                        <option value="driver">Delivery Driver</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Account Status <span class="text-rose-500">*</span></label>
                    <select name="status" id="user_status" required class="w-full px-3.5 py-2.5 border rounded-xl font-bold text-xs focus:ring-2 focus:ring-indigo-500">
                        <option value="active">Active</option>
                        <option value="blocked">Blocked / Inactive</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Mobile Number <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" id="user_phone" required placeholder="9876543210" maxlength="10" class="w-full px-3.5 py-2.5 border rounded-xl font-semibold text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="user_email" required placeholder="user@domain.com" class="w-full px-3.5 py-2.5 border rounded-xl font-semibold text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    <span id="passwordLabel">Password</span> <span id="passwordRequiredStar" class="text-rose-500">*</span>
                </label>
                <input type="password" name="password" id="user_password" placeholder="Min 6 characters" class="w-full px-3.5 py-2.5 border rounded-xl text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
                <p id="passwordHelpText" class="text-[10px] text-slate-400 mt-1 hidden">Leave empty if you do not want to change the password.</p>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2 border-t">
                <button type="button" id="btnCancelUserModal" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" id="btnSubmitUser" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-md transition">
                    Save User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const modal = $('#userModal');
    const form = $('#userForm');
    const modalTitle = $('#userModalTitle');
    const passwordRequiredStar = $('#passwordRequiredStar');
    const passwordHelpText = $('#passwordHelpText');

    // Open Add User Modal
    $('#btnOpenNewUserModal').on('click', function() {
        form[0].reset();
        form.attr('action', "{{ route('admin.users.store') }}");
        modalTitle.text('Add New Staff User');
        $('#user_password').prop('required', true);
        passwordRequiredStar.removeClass('hidden');
        passwordHelpText.addClass('hidden');
        modal.removeClass('hidden').addClass('flex');
    });

    // Open Edit User Modal
    $('.btn-edit-user').on('click', function() {
        const user = $(this).data('user');
        form[0].reset();

        const updateUrl = "{{ route('admin.users.update', ':id') }}".replace(':id', user.id);
        form.attr('action', updateUrl);

        modalTitle.text('Edit Staff User');
        $('#user_name').val(user.name);
        $('#user_role').val(user.role);
        $('#user_status').val(user.status);
        $('#user_phone').val(user.phone);
        $('#user_email').val(user.email);

        $('#user_password').prop('required', false);
        passwordRequiredStar.addClass('hidden');
        passwordHelpText.removeClass('hidden');

        modal.removeClass('hidden').addClass('flex');
    });

    // Close Modal
    $('#btnCloseUserModal, #btnCancelUserModal').on('click', function() {
        modal.removeClass('flex').addClass('hidden');
    });

    modal.on('click', function(e) {
        if (e.target === this) {
            modal.removeClass('flex').addClass('hidden');
        }
    });
});
</script>
@endpush
@endsection
