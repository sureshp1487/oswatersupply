@extends('layouts.customer')

@section('title', 'Services - AquaFlow Water Logistics')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-br from-ocean-700 via-ocean-600 to-sky-500 rounded-3xl p-6 text-white shadow-xl shadow-sky-600/20 relative overflow-hidden">
        <div class="relative z-10 space-y-1">
            <span class="text-xs uppercase tracking-wider text-sky-200 font-bold">One-Stop Platform</span>
            <h1 class="text-2xl sm:text-3xl font-black">Our Services</h1>
            <p class="text-xs sm:text-sm text-sky-100 max-w-lg leading-relaxed pt-1">
                Explore our full suite of doorstep services tailored for households, apartments, and commercial facilities.
            </p>
        </div>
        <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full bg-white/10 pointer-events-none"></div>
    </div>

    <!-- 3 Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">

        <!-- 1. Water Can Supply -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-ocean-500 transition-all flex flex-col justify-between group">
            <div class="space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-ocean-600 flex items-center justify-center text-2xl group-hover:bg-ocean-600 group-hover:text-white transition-all shadow-sm">
                    <i class="fa-solid fa-bottle-water"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-lg font-black text-slate-900">Water Can Supply</h2>
                        <span class="text-[10px] bg-emerald-50 text-emerald-700 font-extrabold px-2 py-0.5 rounded-full border border-emerald-200">Active</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        100% hygienic, lab-tested 20-litre and 10-litre packaged drinking water cans delivered right to your doorstep.
                    </p>
                </div>
                <ul class="text-xs text-slate-600 space-y-2 pt-2 border-t border-slate-100">
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-ocean-600 text-[11px]"></i>
                        <span>1:1 empty container digital ledger</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-ocean-600 text-[11px]"></i>
                        <span>Morning & evening scheduled slots</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-ocean-600 text-[11px]"></i>
                        <span>Cash on Delivery & Razorpay UPI</span>
                    </li>
                </ul>
            </div>
            <div class="pt-6">
                <a href="{{ route('customer.cart') }}" class="block w-full py-3 rounded-2xl bg-ocean-600 hover:bg-ocean-700 text-white font-extrabold text-xs text-center shadow-md shadow-sky-600/25 active:scale-95 transition-all">
                    Order Water Cans Now &rarr;
                </a>
            </div>
        </div>

        <!-- 2. Car Wash -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-cyan-500 transition-all flex flex-col justify-between group">
            <div class="space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-2xl group-hover:bg-cyan-600 group-hover:text-white transition-all shadow-sm">
                    <i class="fa-solid fa-car-wash"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-lg font-black text-slate-900">Doorstep Car Wash</h2>
                        <span class="text-[10px] bg-sky-50 text-sky-700 font-extrabold px-2 py-0.5 rounded-full border border-sky-200">New</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Professional pressure wash, foam cleaning, and interior vacuuming at your apartment parking or villa doorstep.
                    </p>
                </div>
                <ul class="text-xs text-slate-600 space-y-2 pt-2 border-t border-slate-100">
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-cyan-600 text-[11px]"></i>
                        <span>Eco-friendly water-saving foam wash</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-cyan-600 text-[11px]"></i>
                        <span>Interior vacuum & dashboard polish</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-cyan-600 text-[11px]"></i>
                        <span>Daily & alternate day subscription plans</span>
                    </li>
                </ul>
            </div>
            <div class="pt-6">
                <button type="button" onclick="openServiceInquiry('Car Wash', 'Doorstep pressure foam wash and interior vacuuming')" class="block w-full py-3 rounded-2xl bg-white border border-slate-200 text-slate-800 hover:bg-cyan-600 hover:text-white hover:border-cyan-600 font-extrabold text-xs text-center transition-all">
                    Book Car Wash &rarr;
                </button>
            </div>
        </div>

        <!-- 3. Rental House, Lease, Buy & Sales -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-indigo-500 transition-all flex flex-col justify-between group">
            <div class="space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                    <i class="fa-solid fa-building-circle-check"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-lg font-black text-slate-900">Rental, Lease & Property Sales</h2>
                        <span class="text-[10px] bg-indigo-50 text-indigo-700 font-extrabold px-2 py-0.5 rounded-full border border-indigo-200">Verified</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Verified residential and commercial rental houses, long-term leases, flats, plots, and property buying/selling assistance.
                    </p>
                </div>
                <ul class="text-xs text-slate-600 space-y-2 pt-2 border-t border-slate-100">
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-indigo-600 text-[11px]"></i>
                        <span>1 BHK, 2 BHK, 3 BHK & Independent villas</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-indigo-600 text-[11px]"></i>
                        <span>Lease agreements & document assistance</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-indigo-600 text-[11px]"></i>
                        <span>Direct owner verified listings</span>
                    </li>
                </ul>
            </div>
            <div class="pt-6">
                <button type="button" onclick="openServiceInquiry('Rental & Property Services', 'Rental houses, commercial lease, buy and property sale assistance')" class="block w-full py-3 rounded-2xl bg-white border border-slate-200 text-slate-800 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 font-extrabold text-xs text-center transition-all">
                    Explore Properties &rarr;
                </button>
            </div>
        </div>

    </div>

    <!-- Quick Call-To-Action Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="space-y-1 text-center sm:text-left">
            <h3 class="text-base font-black text-slate-900">Need Custom or Bulk Commercial Requirements?</h3>
            <p class="text-xs text-slate-500">Contact our support desk directly for corporate water supply, society car wash, or commercial leases.</p>
        </div>
        <a href="tel:{{ $appSettings['business_phone'] ?? '+919876543210' }}" class="px-5 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs transition flex items-center space-x-2 shrink-0">
            <i class="fa-solid fa-phone"></i>
            <span>Call Support</span>
        </a>
    </div>

</div>

<!-- Service Inquiry Modal -->
<div id="serviceInquiryModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <div>
                <h3 id="inquiryServiceTitle" class="text-base font-black text-slate-900">Service Request</h3>
                <p id="inquiryServiceSubtitle" class="text-xs text-slate-400">Doorstep assistance request</p>
            </div>
            <button type="button" onclick="closeServiceInquiry()" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <form onsubmit="handleInquirySubmit(event)" class="space-y-3.5">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Your Name</label>
                <input type="text" id="inqName" value="{{ auth()->user()->name }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-ocean-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Contact Mobile Number</label>
                <input type="text" id="inqPhone" value="{{ auth()->user()->phone }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-ocean-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Preferred Location / Notes</label>
                <textarea id="inqNotes" rows="2" placeholder="e.g. 2 BHK in Madipakkam / Sedan wash on weekend" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-ocean-500"></textarea>
            </div>
            <button type="submit" class="w-full py-3.5 rounded-2xl bg-ocean-600 hover:bg-ocean-700 text-white font-extrabold text-xs shadow-md shadow-sky-600/30 transition flex items-center justify-center space-x-2">
                <span>Submit Request</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openServiceInquiry(title, subtitle) {
    document.getElementById('inquiryServiceTitle').textContent = title;
    document.getElementById('inquiryServiceSubtitle').textContent = subtitle;
    const modal = document.getElementById('serviceInquiryModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeServiceInquiry() {
    const modal = document.getElementById('serviceInquiryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
function handleInquirySubmit(e) {
    e.preventDefault();
    closeServiceInquiry();
    Swal.fire({
        icon: 'success',
        title: 'Request Received!',
        text: 'Our team will contact you shortly regarding your service requirements.',
        confirmButtonColor: '#0284c7'
    });
}
</script>
@endpush

@endsection
