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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .swal2-actions {
            display: flex !important;
            flex-direction: column !important;
            gap: 6px !important;
            width: 100% !important;
            padding: 0 0.85rem 0.85rem 0.85rem !important;
            margin: 0.5rem 0 0 0 !important;
        }
        .swal2-actions button {
            width: 100% !important;
            margin: 0 !important;
            padding: 9px 12px !important;
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
        }
        div:where(.swal2-container) .swal2-html-container {
            margin: 0.4rem 0.85rem 0 0.85rem !important;
            padding: 0 !important;
        }
        div:where(.swal2-icon) {
            margin: 0.6rem auto 0.2rem auto !important;
            transform: scale(0.65) !important;
            margin-bottom: -10px !important;
        }
        .swal2-title {
            padding: 0.4rem 1rem 0 1rem !important;
        }
    </style>
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

                <div class="space-y-1.5 text-xs">
                    <div class="flex items-center justify-between">
                        <div class="font-extrabold text-slate-900 text-sm">{{ $order->user->name }}</div>
                        <a href="tel:{{ $order->user->phone }}" class="text-xs font-bold text-sky-600 bg-sky-50 hover:bg-sky-100 px-2.5 py-1 rounded-lg border border-sky-100 transition inline-flex items-center space-x-1">
                            <i class="fa-solid fa-phone text-[10px]"></i>
                            <span>Call</span>
                        </a>
                    </div>

                    <div class="text-slate-600 text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <div class="flex items-start space-x-1.5">
                            <i class="fa-solid fa-location-dot text-rose-500 mt-0.5 shrink-0"></i>
                            <div>
                                <span class="font-semibold text-slate-800">
                                    {{ $order->address_snapshot['door_number'] ?? '' }}, {{ $order->address_snapshot['house_or_building'] ?? '' }}, {{ $order->address_snapshot['street'] ?? '' }}
                                </span>
                                <span class="text-slate-500 block text-[11px]">
                                    {{ $order->address_snapshot['area_locality'] ?? '' }}, {{ $order->address_snapshot['city'] ?? '' }} - {{ $order->address_snapshot['pincode'] ?? '' }}
                                </span>
                            </div>
                        </div>

                        <!-- 1-Tap Google Maps Turn-by-Turn Navigation Button for Driver -->
                        <div class="mt-2.5 pt-2 border-t border-slate-200/80 flex items-center justify-between">
                            @if(!empty($order->address_snapshot['latitude']) && !empty($order->address_snapshot['longitude']))
                                <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                    <i class="fa-solid fa-crosshairs mr-1"></i>Exact GPS Pinned
                                </span>
                            @else
                                <span class="text-[10px] font-semibold text-slate-400">
                                    Address Nav
                                </span>
                            @endif

                            <a href="{{ $order->google_maps_url }}" target="_blank" rel="noopener noreferrer" 
                               class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black shadow-md shadow-emerald-600/30 transition active:scale-95">
                                <i class="fa-solid fa-diamond-turn-right text-xs"></i>
                                <span>Navigate on Maps &rarr;</span>
                            </a>
                        </div>
                    </div>
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
                <form id="delivery-form-{{ $assign->id }}" action="{{ route('driver.delivery.status', $assign->id) }}" method="POST" class="space-y-2 pt-2 border-t">
                    @csrf
                    <input type="hidden" name="status" id="status-input-{{ $assign->id }}" value="">
                    <input type="hidden" name="collected_cash" id="collected-cash-{{ $assign->id }}" value="{{ $order->due_amount }}">

                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="handleStartRoute('{{ $assign->id }}', '{{ $order->google_maps_url }}', '{{ $assign->status }}')" class="flex-1 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow">
                            <i class="fa-solid fa-truck-fast mr-1"></i> Start Route
                        </button>
                        <button type="button" onclick="handleMarkDelivered('{{ $assign->id }}', {{ (float)$order->due_amount }}, '{{ addslashes($order->user->name ?? 'Customer') }}', '{{ $order->order_number }}')" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow">
                            <i class="fa-solid fa-circle-check mr-1"></i> Mark Delivered
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

    <script>
    function handleStartRoute(assignId, mapUrl, currentStatus) {
        if (mapUrl) {
            window.open(mapUrl, '_blank');
        }
        if (currentStatus !== 'in_transit') {
            submitStatus(assignId, 'in_transit');
        }
    }

    function submitStatus(assignId, status) {
        document.getElementById('status-input-' + assignId).value = status;
        document.getElementById('delivery-form-' + assignId).submit();
    }

    function handleMarkDelivered(assignId, dueAmount, customerName, orderNumber) {
        // If order has no due amount (already paid online or ₹0)
        if (dueAmount <= 0) {
            Swal.fire({
                title: 'Mark as Delivered?',
                text: 'Confirm delivery for Order #' + orderNumber + ' (' + customerName + ')?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delivered!',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold px-4 py-2 text-sm',
                    cancelButton: 'rounded-xl font-bold px-4 py-2 text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('collected-cash-' + assignId).value = 0;
                    submitStatus(assignId, 'delivered');
                }
            });
            return;
        }

        // For Cash On Delivery / Due amount orders:
        Swal.fire({
            title: '<div class="text-sm font-black text-slate-800">Cash Collection</div>',
            html: `
                <div class="text-left space-y-2">
                    <div class="px-2.5 py-2 bg-amber-50 rounded-lg border border-amber-200">
                        <div class="flex justify-between items-center text-[11px] text-amber-900 font-medium">
                            <span>Order #${orderNumber}</span>
                            <span class="font-bold text-slate-700">${customerName}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-black text-amber-950 mt-0.5">
                            <span>Total Due:</span>
                            <span class="text-sm text-rose-600">₹${dueAmount.toFixed(2)}</span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-2 rounded-lg border border-slate-200">
                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-0.5">Cash Received (₹)</label>
                        <div class="relative">
                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 font-black text-slate-400 text-sm">₹</span>
                            <input id="swal-input-cash" type="number" step="0.01" value="${dueAmount.toFixed(2)}" class="w-full pl-6 pr-2 py-1.5 bg-white border border-slate-300 focus:border-emerald-500 rounded-lg text-sm font-black text-slate-900 outline-none">
                        </div>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: '<i class="fa-solid fa-money-bill-wave mr-1"></i> Yes, Cash Collected',
            denyButtonText: '<i class="fa-solid fa-clock mr-1"></i> No (Cash Not Collected)',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-2xl !max-w-[310px] !w-[88%] !p-2',
                confirmButton: 'bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white',
                denyButton: 'bg-orange-500 hover:bg-orange-600 active:scale-[0.98] text-white',
                cancelButton: 'bg-slate-200 hover:bg-slate-300 active:scale-[0.98] text-slate-700'
            },
            preConfirm: () => {
                const val = document.getElementById('swal-input-cash').value;
                const enteredCash = parseFloat(val);
                if (isNaN(enteredCash) || enteredCash < 0) {
                    Swal.showValidationMessage('Please enter a valid cash amount');
                    return false;
                }
                return enteredCash;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Cash collected
                const collectedAmount = result.value;
                document.getElementById('collected-cash-' + assignId).value = collectedAmount;
                submitStatus(assignId, 'delivered');
            } else if (result.isDenied) {
                // Not collected - mark as pending due
                document.getElementById('collected-cash-' + assignId).value = 0;
                submitStatus(assignId, 'delivered');
            }
        });
    }
    </script>
</body>
</html>
