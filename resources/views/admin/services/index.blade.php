@extends('layouts.admin')

@section('page_title', 'Available Services Management')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-indigo-700/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-cyan-500/20 text-cyan-300 text-xs font-bold mb-2 border border-cyan-400/30">
                <i class="fa-solid fa-bell-concierge"></i>
                <span>Customer Home & App Services</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black tracking-tight">Available Services</h2>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
                Configure services displayed on the customer home page and services screen (Water Can Supply, Doorstep Car Wash, Rental House & Property).
            </p>
        </div>
        <div>
            <button type="button" onclick="$('#addServiceModal').removeClass('hidden')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-xs font-black shadow-lg shadow-indigo-600/30 flex items-center space-x-2 transition hover:scale-105 active:scale-95">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Service</span>
            </button>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Current Services ({{ $services->count() }})</h3>
                <p class="text-[11px] text-slate-500">Live service cards shown to customers on the home page and services directory</p>
            </div>
        </div>

        @if($services->isEmpty())
        <div class="p-12 text-center text-slate-400 space-y-3">
            <i class="fa-solid fa-bell-concierge text-4xl text-slate-300"></i>
            <p class="text-sm font-bold text-slate-600">No Services Configured Yet</p>
            <p class="text-xs">Click the "Add New Service" button above to add offerings like Water Can Supply, Car Wash, or Rental House.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($services as $service)
            <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50 flex flex-col justify-between group hover:shadow-md transition">
                <!-- Service Image Preview -->
                <div class="relative w-full h-44 bg-slate-900 overflow-hidden">
                    <img src="{{ $service->image_path }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent flex flex-col justify-end p-3.5 text-white">
                        <div class="flex items-center space-x-2">
                            <i class="{{ $service->icon ?? 'fa-solid fa-briefcase' }} text-sky-300 text-sm"></i>
                            <span class="text-sm font-black drop-shadow truncate">{{ $service->name }}</span>
                        </div>
                        @if($service->tagline)
                        <span class="text-[11px] text-slate-200 line-clamp-1 drop-shadow mt-0.5">{{ $service->tagline }}</span>
                        @endif
                    </div>
                    <div class="absolute top-2 right-2 flex items-center space-x-1.5">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $service->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-600 text-slate-200' }}">
                            {{ $service->is_active ? 'Active' : 'Disabled' }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-black/60 backdrop-blur text-white">
                            #{{ $service->display_order }}
                        </span>
                    </div>
                    @if($service->badge_text)
                    <div class="absolute top-2 left-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/90 text-slate-900 backdrop-blur shadow-sm">
                            {{ $service->badge_text }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Service Meta & Details -->
                <div class="p-4 bg-white flex flex-col justify-between flex-1 space-y-3">
                    <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                        {{ $service->description ?? 'No detailed description added.' }}
                    </p>

                    @if(!empty($service->features) && count($service->features) > 0)
                    <div class="space-y-1 pt-1 border-t border-slate-100 text-[11px] text-slate-600">
                        @foreach(array_slice($service->features, 0, 3) as $feat)
                        <div class="flex items-center space-x-1.5 truncate">
                            <i class="fa-solid fa-circle-check text-ocean-600 text-[10px] shrink-0"></i>
                            <span class="truncate">{{ $feat }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
                        <span class="text-slate-400 font-medium">Button:</span>
                        <span class="font-bold text-ocean-700 bg-sky-50 px-2 py-0.5 rounded-lg text-[11px]">{{ $service->action_text }}</span>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <button type="button" onclick="editService({{ json_encode($service) }})" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center space-x-1">
                            <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                            <span>Edit</span>
                        </button>
                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold transition flex items-center space-x-1">
                                <i class="fa-solid fa-trash text-[11px]"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

<!-- Add Service Modal -->
<div id="addServiceModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 my-auto">
        <div class="flex items-center justify-between border-b pb-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Add New Service</h3>
                <p class="text-xs text-slate-400">Card will appear in customer home page & services tab</p>
            </div>
            <button type="button" onclick="$('#addServiceModal').addClass('hidden')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Service Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Water Can Supply, Car Wash, Rental House" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Tagline / Subtitle</label>
                <input type="text" name="tagline" placeholder="e.g. 100% Hygienic 20L Doorstep Delivery" class="w-full px-3.5 py-2 rounded-xl border border-slate-200">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Detailed Description</label>
                <textarea name="description" rows="2" placeholder="Brief summary of what this service covers..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">FontAwesome Icon Class</label>
                    <input type="text" name="icon" value="fa-solid fa-bottle-water" placeholder="fa-solid fa-bottle-water" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 font-mono text-[11px]">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Badge Text (Optional)</label>
                    <input type="text" name="badge_text" placeholder="e.g. Active, Popular, Verified" class="w-full px-3.5 py-2 rounded-xl border border-slate-200">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Key Features / Bullet Points (one per line or comma-separated)</label>
                <textarea name="features" rows="2" placeholder="1:1 empty container digital ledger&#10;Morning & evening scheduled slots&#10;Doorstep delivery in 60 mins" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 font-mono text-[11px]"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Button Text</label>
                    <input type="text" name="action_text" value="Book Now" placeholder="e.g. Order Water Cans, Book Car Wash" class="w-full px-3.5 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Button Link / Action</label>
                    <input type="text" name="action_url" value="#serviceInquiry" placeholder="/app/cart or #serviceInquiry" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 font-mono text-[11px]">
                </div>
            </div>

            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2">
                <label class="block font-bold text-slate-700">Service Image <span class="text-rose-500">*</span></label>
                <div>
                    <input type="file" name="image" accept="image/*" class="w-full text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div class="flex items-center space-x-2 text-slate-400 font-bold my-1">
                    <span class="flex-1 border-b border-slate-200"></span>
                    <span class="text-[10px] uppercase">Or Use Image URL</span>
                    <span class="flex-1 border-b border-slate-200"></span>
                </div>
                <div>
                    <input type="url" name="image_url" placeholder="https://images.unsplash.com/... or /asset/image/watercan-1.png" class="w-full px-3 py-2 border rounded-xl font-mono text-[11px]">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-1">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Display Order</label>
                    <input type="number" name="display_order" value="1" min="0" max="999" class="w-full px-3.5 py-2 rounded-xl border border-slate-200">
                </div>
                <div class="flex items-center pt-5">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                        <span class="font-bold text-slate-700">Display as Active</span>
                    </label>
                </div>
            </div>

            <div class="flex space-x-2 pt-3 border-t">
                <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl transition shadow">
                    Save Service
                </button>
                <button type="button" onclick="$('#addServiceModal').addClass('hidden')" class="px-5 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Service Modal -->
<div id="editServiceModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-4 my-auto">
        <div class="flex items-center justify-between border-b pb-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Edit Service</h3>
                <p class="text-xs text-slate-400">Update details and imagery</p>
            </div>
            <button type="button" onclick="$('#editServiceModal').addClass('hidden')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="editServiceForm" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Service Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="edit_service_name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Tagline / Subtitle</label>
                <input type="text" name="tagline" id="edit_service_tagline" class="w-full px-3.5 py-2 rounded-xl border border-slate-200">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Detailed Description</label>
                <textarea name="description" id="edit_service_description" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-200"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">FontAwesome Icon Class</label>
                    <input type="text" name="icon" id="edit_service_icon" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 font-mono text-[11px]">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Badge Text</label>
                    <input type="text" name="badge_text" id="edit_service_badge_text" class="w-full px-3.5 py-2 rounded-xl border border-slate-200">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Key Features (one per line)</label>
                <textarea name="features" id="edit_service_features" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 font-mono text-[11px]"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Button Text</label>
                    <input type="text" name="action_text" id="edit_service_action_text" class="w-full px-3.5 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Button Link / Action</label>
                    <input type="text" name="action_url" id="edit_service_action_url" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 font-mono text-[11px]">
                </div>
            </div>

            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2">
                <label class="block font-bold text-slate-700">Change Image (optional)</label>
                <div>
                    <input type="file" name="image" accept="image/*" class="w-full text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div>
                    <input type="url" name="image_url" id="edit_service_image_url" placeholder="Or enter new image URL" class="w-full px-3 py-2 border rounded-xl font-mono text-[11px]">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-1">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Display Order</label>
                    <input type="number" name="display_order" id="edit_service_display_order" min="0" max="999" class="w-full px-3.5 py-2 rounded-xl border border-slate-200">
                </div>
                <div class="flex items-center pt-5">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="edit_service_is_active" value="1" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                        <span class="font-bold text-slate-700">Display as Active</span>
                    </label>
                </div>
            </div>

            <div class="flex space-x-2 pt-3 border-t">
                <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl transition shadow">
                    Update Service
                </button>
                <button type="button" onclick="$('#editServiceModal').addClass('hidden')" class="px-5 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function editService(service) {
    $('#editServiceForm').attr('action', `/admin/services/${service.id}`);
    $('#edit_service_name').val(service.name || '');
    $('#edit_service_tagline').val(service.tagline || '');
    $('#edit_service_description').val(service.description || '');
    $('#edit_service_icon').val(service.icon || '');
    $('#edit_service_badge_text').val(service.badge_text || '');
    $('#edit_service_action_text').val(service.action_text || 'Book Service');
    $('#edit_service_action_url').val(service.action_url || '#serviceInquiry');
    $('#edit_service_display_order').val(service.display_order || 0);
    $('#edit_service_is_active').prop('checked', !!service.is_active);

    if (service.features && Array.isArray(service.features)) {
        $('#edit_service_features').val(service.features.join('\n'));
    } else {
        $('#edit_service_features').val('');
    }

    if (service.image_path && service.image_path.startsWith('http')) {
        $('#edit_service_image_url').val(service.image_path);
    } else {
        $('#edit_service_image_url').val('');
    }

    $('#editServiceModal').removeClass('hidden').addClass('flex');
}

// Close modals when clicking backdrop
$('#addServiceModal, #editServiceModal').on('click', function(e) {
    if (e.target === this) {
        $(this).addClass('hidden').removeClass('flex');
    }
});
</script>
@endpush
@endsection
