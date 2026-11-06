@extends('layouts.admin')

@section('page_title', 'Dynamic Water Can & Inventory Catalog')

@section('content')
<div class="space-y-6">

    <!-- Add Product & Header -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-black text-slate-900">Manage Water Can Products</h2>
            <p class="text-xs text-slate-500">Add or dynamically edit sizes (20L, 10L, 5L, 2L, custom), prices, and stock inventory</p>
        </div>

        <button type="button" id="btnOpenNewProductModal" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Water Can Product</span>
        </button>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($products as $prod)
        <div class="bg-white rounded-3xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="w-full h-40 rounded-2xl bg-slate-50 overflow-hidden relative mb-3">
                    <img src="{{ $prod->image ?: 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=500' }}" class="w-full h-full object-cover">
                    <span class="absolute top-2 left-2 px-2.5 py-0.5 rounded-md bg-slate-900/80 backdrop-blur text-white font-extrabold text-[10px]">
                        {{ $prod->volume }} {{ $prod->unit }}
                    </span>
                    <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                        {{ $prod->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                        {{ $prod->is_active ? 'Active' : 'Disabled' }}
                    </span>
                </div>

                <h3 class="text-sm font-extrabold text-slate-900 leading-snug">{{ $prod->name }}</h3>
                <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $prod->description }}</p>

                <div class="mt-3 grid grid-cols-2 gap-2 text-[11px] bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <div>
                        <span class="text-slate-400 block text-[9px] uppercase font-bold">Selling Price</span>
                        <span class="font-extrabold text-slate-900 text-sm">₹{{ number_format($prod->selling_price, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[9px] uppercase font-bold">Inventory Stock</span>
                        <span class="font-extrabold {{ $prod->stock_quantity <= 15 ? 'text-rose-600' : 'text-slate-900' }} text-sm">
                            {{ $prod->stock_quantity }} units
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 text-[10px]">Min Qty: {{ $prod->min_order_quantity }}</span>
                <button type="button" class="btn-edit-prod text-sky-600 font-bold hover:underline"
                        data-id="{{ $prod->id }}"
                        data-name="{{ $prod->name }}"
                        data-volume="{{ $prod->volume }}"
                        data-unit="{{ $prod->unit }}"
                        data-price="{{ $prod->selling_price }}"
                        data-stock="{{ $prod->stock_quantity }}"
                        data-min="{{ $prod->min_order_quantity }}"
                        data-desc="{{ $prod->description }}"
                        data-image="{{ $prod->image }}"
                        data-active="{{ $prod->is_active ? 1 : 0 }}"
                        data-eligible="{{ $prod->is_delivery_eligible ? 1 : 0 }}">
                    Edit Product
                </button>
            </div>
        </div>
        @endforeach
    </div>

</div>

<!-- Product Create / Edit Modal -->
<div id="productModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 max-w-lg w-full shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 id="modalProductTitle" class="text-base font-extrabold text-slate-900">Add Water Can Product</h3>
            <button type="button" id="closeProductModal" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="productForm" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Product Title</label>
                <input type="text" name="name" id="prod_name" required placeholder="e.g. 20-Litre Pure Drinking Water Can" class="w-full px-3 py-2 border rounded-xl text-xs font-medium">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Volume / Capacity</label>
                    <input type="text" name="volume" id="prod_volume" required placeholder="e.g. 20, 10, 5, 2" class="w-full px-3 py-2 border rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit</label>
                    <input type="text" name="unit" id="prod_unit" required value="Litre" placeholder="Litre, Bottle" class="w-full px-3 py-2 border rounded-xl text-xs">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Selling Price (₹)</label>
                    <input type="number" step="0.01" min="0" name="selling_price" id="prod_price" required placeholder="50.00" class="w-full px-3 py-2 border rounded-xl text-xs font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Available Stock</label>
                    <input type="number" min="0" name="stock_quantity" id="prod_stock" required placeholder="100" class="w-full px-3 py-2 border rounded-xl text-xs font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Min Order Qty</label>
                    <input type="number" min="1" name="min_order_quantity" id="prod_min" required value="1" class="w-full px-3 py-2 border rounded-xl text-xs">
                </div>
            </div>

            <!-- Product Image Upload with Live Preview -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700">Product Image</label>
                <input type="hidden" name="image" id="prod_image">

                <div class="flex items-center space-x-3 p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                    <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center shadow-xs">
                        <img id="imagePreview" src="https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=500" alt="Preview" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 space-y-1">
                        <label class="block text-xs font-bold text-indigo-600 hover:text-indigo-700 cursor-pointer">
                            <span>Upload Image from Device</span>
                            <input type="file" name="image_file" id="prod_image_file" accept="image/*" class="hidden" onchange="previewProductImage(this)">
                        </label>
                        <p class="text-[10px] text-slate-400">PNG, JPG, WEBP or SVG up to 4MB</p>
                        <div id="imageFileName" class="text-[11px] font-semibold text-slate-600 truncate">No file chosen</div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                <textarea name="description" id="prod_desc" rows="2" placeholder="RO + UV purified, sanitized reusable container..." class="w-full px-3 py-2 border rounded-xl text-xs"></textarea>
            </div>

            <div class="flex items-center space-x-6 py-2">
                <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_active" id="prod_active" value="1" checked class="rounded text-sky-600">
                    <span>Active & Available for Sale</span>
                </label>
                <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_delivery_eligible" id="prod_eligible" value="1" checked class="rounded text-sky-600">
                    <span>Eligible for Doorstep Delivery</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-600/30 transition">
                Save Product
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewProductImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        $('#imageFileName').text(file.name);
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
    }
}

$(document).ready(function() {
    $('#btnOpenNewProductModal').on('click', function() {
        $('#modalProductTitle').text('Add Water Can Product');
        $('#productForm').attr('action', "{{ route('admin.products.store') }}");
        $('#prod_name').val('');
        $('#prod_volume').val('20');
        $('#prod_unit').val('Litre');
        $('#prod_price').val('50');
        $('#prod_stock').val('100');
        $('#prod_min').val('1');
        $('#prod_image').val('');
        $('#prod_image_file').val('');
        $('#imageFileName').text('No file chosen');
        $('#imagePreview').attr('src', 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=500');
        $('#prod_desc').val('');
        $('#prod_active').prop('checked', true);
        $('#prod_eligible').prop('checked', true);
        $('#productModal').removeClass('hidden').addClass('flex');
    });

    $('.btn-edit-prod').on('click', function() {
        const id = $(this).data('id');
        const img = $(this).data('image') || 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=500';
        $('#modalProductTitle').text('Edit Water Can Product');
        $('#productForm').attr('action', '/admin/products/' + id);
        $('#prod_name').val($(this).data('name'));
        $('#prod_volume').val($(this).data('volume'));
        $('#prod_unit').val($(this).data('unit'));
        $('#prod_price').val($(this).data('price'));
        $('#prod_stock').val($(this).data('stock'));
        $('#prod_min').val($(this).data('min'));
        $('#prod_image').val($(this).data('image'));
        $('#prod_image_file').val('');
        $('#imageFileName').text('Keep current image or choose file');
        $('#imagePreview').attr('src', img);
        $('#prod_desc').val($(this).data('desc'));
        $('#prod_active').prop('checked', $(this).data('active') == 1);
        $('#prod_eligible').prop('checked', $(this).data('eligible') == 1);
        $('#productModal').removeClass('hidden').addClass('flex');
    });

    $('#closeProductModal').on('click', function() {
        $('#productModal').removeClass('flex').addClass('hidden');
    });
});
</script>
@endpush
