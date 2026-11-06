<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Captain Portal - {{ $appSettings['business_name'] ?? 'HydroPro' }}</title>
    <!-- Brand Favicon & Icons -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/icons/logo.svg">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 flex flex-col justify-between">

    <!-- Header -->
    <header class="bg-slate-950 text-white p-4 sticky top-0 z-30 shadow border-b border-indigo-950">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <img src="/icons/logo.svg" alt="HydroPro Logo" class="w-8 h-8 rounded-lg p-0.5 bg-gradient-to-tr from-blue-500 to-indigo-600 object-contain shadow">
                <div>
                    <h1 class="text-xs font-black uppercase tracking-wider text-indigo-400">{{ $appSettings['business_name'] ?? 'HydroPro Delivery' }}</h1>
                    <span class="text-sm font-bold block leading-none">{{ $driver->name }}</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-slate-400 hover:text-white">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 max-w-md w-full mx-auto p-4 space-y-4">
        <!-- Daily KPI -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                <span class="text-[10px] text-slate-400 uppercase font-bold block">Delivered Today</span>
                <span class="text-2xl font-black text-emerald-600">{{ $completedToday }} Orders</span>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                <span class="text-[10px] text-slate-400 uppercase font-bold block">Cash Collected</span>
                <span class="text-2xl font-black text-slate-900">₹{{ number_format($cashCollectedToday, 2) }}</span>
            </div>
        </div>

        <!-- Assignments List -->
        <div class="space-y-3">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-wider">Today's Assigned Route Deliveries</h2>

            @forelse($assignments as $assign)
            @php $order = $assign->order; @endphp
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 space-y-3">
                <div class="flex items-center justify-between text-xs border-b pb-2">
                    <span class="font-mono font-bold text-slate-900">#{{ $order->order_number }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                        {{ $assign->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $assign->status }}
                    </span>
                </div>

                <div class="space-y-1 text-xs">
                    <div class="font-extrabold text-slate-900 text-sm">{{ $order->user->name }}</div>
                    <div class="text-sky-600 font-bold">
                        <a href="tel:{{ $order->user->phone }}"><i class="fa-solid fa-phone mr-1"></i>+91 {{ $order->user->phone }}</a>
                    </div>
                    <p class="text-slate-600 mt-1">
                        {{ $order->address_snapshot['door_number'] ?? '' }}, {{ $order->address_snapshot['street'] ?? '' }}, {{ $order->address_snapshot['area_locality'] ?? '' }}
                    </p>
                </div>

                <div class="bg-slate-50 p-2.5 rounded-xl text-xs space-y-1">
                    <div class="flex justify-between font-bold text-slate-800">
                        <span>Items to Deliver:</span>
                        <span>{{ $order->items->sum('quantity') }} Cans</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Order Total / Due:</span>
                        <span class="font-bold {{ $order->due_amount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $order->due_amount > 0 ? 'Collect ₹' . number_format($order->due_amount, 2) : 'Already Paid' }}
                        </span>
                    </div>
                </div>

                @if($assign->status !== 'delivered')
                <form action="{{ route('driver.delivery.status', $assign->id) }}" method="POST" class="space-y-2 pt-2 border-t">
                    @csrf
                    @if($order->due_amount > 0)
                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Cash Amount Collected at Doorstep (₹)</label>
                        <input type="number" step="0.01" name="collected_cash" value="{{ $order->due_amount }}" class="w-full px-3 py-1.5 border rounded-xl text-xs font-bold">
                    </div>
                    @endif

                    <div class="flex items-center space-x-2">
                        <button type="submit" name="status" value="in_transit" class="flex-1 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow">
                            Start Route
                        </button>
                        <button type="submit" name="status" value="delivered" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow">
                            Mark Delivered
                        </button>
                    </div>
                </form>
                @endif
            </div>
            @empty
            <div class="bg-white rounded-2xl p-8 text-center text-slate-400 text-xs border border-slate-200">
                No deliveries assigned to your route today.
            </div>
            @endforelse
        </div>
    </main>

</body>
</html>
