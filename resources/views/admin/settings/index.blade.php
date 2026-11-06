@extends('layouts.admin')

@section('page_title', 'Application & Business Settings')

@section('content')
<div class="max-w-4xl space-y-6">

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- General Info -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Business Information</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Business Name</label>
                    <input type="text" name="business_name" value="{{ $settings['business_name'] ?? 'AquaFlow Water Delivery' }}" class="w-full px-3 py-2 border rounded-xl">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tagline</label>
                    <input type="text" name="business_tagline" value="{{ $settings['business_tagline'] ?? 'Pure Hydration Delivered To Your Doorstep' }}" class="w-full px-3 py-2 border rounded-xl">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Customer Support Phone</label>
                    <input type="text" name="business_phone" value="{{ $settings['business_phone'] ?? '+91 98765 43210' }}" class="w-full px-3 py-2 border rounded-xl">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Official Business Email</label>
                    <input type="email" name="business_email" value="{{ $settings['business_email'] ?? 'support@aquaflowdelivery.test' }}" class="w-full px-3 py-2 border rounded-xl">
                </div>
            </div>
        </div>

        <!-- Payment Gateway Settings -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Razorpay Payment Gateway Credentials</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Razorpay Key ID</label>
                    <input type="text" name="razorpay_key_id" value="{{ $settings['razorpay_key_id'] ?? 'rzp_test_waterdelivery101' }}" class="w-full px-3 py-2 border rounded-xl font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Razorpay Key Secret</label>
                    <input type="password" name="razorpay_key_secret" value="{{ $settings['razorpay_key_secret'] ?? 'demoSecretKeyAqua123456' }}" class="w-full px-3 py-2 border rounded-xl font-mono">
                </div>
            </div>
        </div>

        <!-- Operational Rules -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Delivery Logistics Rules</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Default Delivery Fee (₹)</label>
                    <input type="number" step="0.01" name="delivery_fee" value="{{ $settings['delivery_fee'] ?? '0' }}" class="w-full px-3 py-2 border rounded-xl font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Minimum Order Amount (₹)</label>
                    <input type="number" step="0.01" name="min_order_amount" value="{{ $settings['min_order_amount'] ?? '50' }}" class="w-full px-3 py-2 border rounded-xl font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Admin Notification Email</label>
                    <input type="email" name="admin_notification_email" value="{{ $settings['admin_notification_email'] ?? 'admin@aquaflowdelivery.test' }}" class="w-full px-3 py-2 border rounded-xl">
                </div>
            </div>
        </div>

        <button type="submit" class="px-8 py-3 bg-sky-600 hover:bg-sky-700 text-white rounded-2xl text-xs font-bold shadow-lg shadow-sky-600/30">
            Save System Configurations
        </button>
    </form>

</div>
@endsection
