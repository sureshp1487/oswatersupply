@extends('layouts.admin')

@section('page_title', 'Hero Banner Management')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-indigo-700/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-indigo-500/30 text-indigo-200 text-xs font-bold mb-2 border border-indigo-400/30">
                <i class="fa-solid fa-images"></i>
                <span>Customer App Hero Carousel</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black tracking-tight">Hero Banners & Carousel Sliders</h2>
            <p class="text-xs sm:text-sm text-indigo-200 mt-1">Upload and manage rotating hero banners displayed on the customer home page. Banners scroll automatically.</p>
        </div>
        <div>
            <button type="button" onclick="$('#addBannerModal').removeClass('hidden')" class="px-5 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-2xl text-xs font-black shadow-lg shadow-indigo-500/30 flex items-center space-x-2 transition hover:scale-105 active:scale-95">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Hero Banner</span>
            </button>
        </div>
    </div>

    <!-- Active Banners Grid -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Current Hero Banners ({{ $banners->count() }})</h3>
                <p class="text-[11px] text-slate-500">Live banners shown in the customer app hero carousel in their display order</p>
            </div>
        </div>

        @if($banners->isEmpty())
        <div class="p-12 text-center text-slate-400 space-y-3">
            <i class="fa-regular fa-image text-4xl text-slate-300"></i>
            <p class="text-sm font-bold text-slate-600">No Hero Banners Added Yet</p>
            <p class="text-xs">Click the "Add New Hero Banner" button above to add promotional sliders for your customers.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($banners as $banner)
            <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50 flex flex-col justify-between group hover:shadow-md transition">
                <!-- Banner Image Preview -->
                <div class="relative w-full h-44 bg-slate-200 overflow-hidden">
                    <img src="{{ $banner->image_path }}" alt="{{ $banner->title ?? 'Banner' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent flex flex-col justify-end p-3 text-white">
                        <span class="text-xs font-black drop-shadow">{{ $banner->title ?: 'No Title' }}</span>
                        @if($banner->subtitle)
                        <span class="text-[11px] text-slate-200 line-clamp-1 drop-shadow">{{ $banner->subtitle }}</span>
                        @endif
                    </div>
                    <div class="absolute top-2 right-2 flex items-center space-x-1.5">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $banner->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-600 text-slate-200' }}">
                            {{ $banner->is_active ? 'Active' : 'Disabled' }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-black/60 backdrop-blur text-white">
                            #{{ $banner->display_order }}
                        </span>
                    </div>
                </div>

                <!-- Banner Meta & Actions -->
                <div class="p-4 bg-white flex flex-col justify-between flex-1 space-y-3">
                    <div class="space-y-1 text-xs text-slate-600">
                        @if($banner->link_url)
                        <div class="flex items-center space-x-1 truncate text-sky-600 font-semibold">
                            <i class="fa-solid fa-link text-[10px]"></i>
                            <a href="{{ $banner->link_url }}" target="_blank" class="truncate hover:underline">{{ $banner->link_url }}</a>
                        </div>
                        @else
                        <span class="text-slate-400 text-[11px] italic">No click action link set</span>
                        @endif
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <button type="button" onclick="editBanner({{ json_encode($banner) }})" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center space-x-1">
                            <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                            <span>Edit</span>
                        </button>
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner?')">
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

<!-- Add Banner Modal -->
<div id="addBannerModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-plus-circle text-indigo-600"></i>
                <span>Add New Hero Banner</span>
            </h3>
            <button type="button" onclick="$('#addBannerModal').addClass('hidden')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 mb-1">Banner Title (Optional)</label>
                <input type="text" name="title" placeholder="e.g. Pure & Hygienic Drinking Water" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Subtitle / Tagline (Optional)</label>
                <input type="text" name="subtitle" placeholder="e.g. 10-Stage Purified RO + UV Doorstep Delivery" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Upload Banner Image File <span class="text-indigo-600 font-normal">(Recommended: 1200x500px, 16:9 or 21:9)</span></label>
                <input type="file" name="image" accept="image/*" class="w-full text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">OR External Image URL</label>
                <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Click Link URL (Optional)</label>
                    <input type="text" name="link_url" placeholder="/app/book" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Display Order</label>
                    <input type="number" name="display_order" value="1" min="0" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex items-center space-x-2 pt-1">
                <input type="checkbox" name="is_active" id="is_active_add" value="1" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                <label for="is_active_add" class="font-bold text-slate-700 cursor-pointer">Activate Banner immediately</label>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="$('#addBannerModal').addClass('hidden')" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-black shadow-md shadow-indigo-600/30 transition">Save Banner</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Banner Modal -->
<div id="editBannerModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-pen text-indigo-600"></i>
                <span>Edit Hero Banner</span>
            </h3>
            <button type="button" onclick="$('#editBannerModal').addClass('hidden')" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="editBannerForm" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Banner Title</label>
                <input type="text" id="edit_title" name="title" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Subtitle / Tagline</label>
                <input type="text" id="edit_subtitle" name="subtitle" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Change Image (Leave blank to keep existing)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">OR Image URL</label>
                <input type="url" id="edit_image_url" name="image_url" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Click Link URL</label>
                    <input type="text" id="edit_link_url" name="link_url" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Display Order</label>
                    <input type="number" id="edit_display_order" name="display_order" min="0" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex items-center space-x-2 pt-1">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                <label for="edit_is_active" class="font-bold text-slate-700 cursor-pointer">Active in Carousel</label>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="$('#editBannerModal').addClass('hidden')" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-black shadow-md shadow-indigo-600/30 transition">Update Banner</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editBanner(banner) {
        $('#editBannerForm').attr('action', '/admin/banners/' + banner.id);
        $('#edit_title').val(banner.title || '');
        $('#edit_subtitle').val(banner.subtitle || '');
        $('#edit_link_url').val(banner.link_url || '');
        $('#edit_display_order').val(banner.display_order || 0);
        $('#edit_image_url').val(banner.image_path.startsWith('http') ? banner.image_path : '');
        $('#edit_is_active').prop('checked', !!banner.is_active);
        $('#editBannerModal').removeClass('hidden');
    }
</script>
@endpush
@endsection
