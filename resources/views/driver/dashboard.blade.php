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
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold p-3 rounded-xl flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold p-3 rounded-xl flex items-center space-x-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

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
                    <span id="assign-status-badge-{{ $assign->id }}" class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
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

                    <div onclick="window.open('{{ $order->google_maps_url }}', '_blank')" class="text-slate-600 text-xs bg-slate-50 hover:bg-slate-100 cursor-pointer p-2.5 rounded-xl border border-slate-100 transition group" title="Click to open in Google Maps">
                        <div class="flex items-start justify-between space-x-1.5">
                            <div class="flex items-start space-x-1.5">
                                <i class="fa-solid fa-location-dot text-rose-500 mt-0.5 shrink-0 group-hover:scale-110 transition"></i>
                                <div>
                                    <span class="font-semibold text-slate-800">
                                        {{ $order->address_snapshot['door_number'] ?? '' }}, {{ $order->address_snapshot['house_or_building'] ?? '' }}, {{ $order->address_snapshot['street'] ?? '' }}
                                    </span>
                                    <span class="text-slate-500 block text-[11px]">
                                        {{ $order->address_snapshot['area_locality'] ?? '' }}, {{ $order->address_snapshot['city'] ?? '' }} - {{ $order->address_snapshot['pincode'] ?? '' }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-1.5 py-0.5 rounded border border-sky-100 whitespace-nowrap self-center">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[9px] mr-0.5"></i> Maps
                            </span>
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
                    <input type="hidden" name="payment_method" id="payment-method-{{ $assign->id }}" value="cash_offline">
                    <input type="hidden" name="razorpay_payment_id" id="rzp-payment-id-{{ $assign->id }}" value="">
                    <input type="hidden" name="razorpay_order_id" id="rzp-order-id-{{ $assign->id }}" value="">
                    <input type="hidden" name="razorpay_signature" id="rzp-signature-{{ $assign->id }}" value="">

                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="handleStartRoute('{{ $assign->id }}', '{{ $assign->status }}', '{{ $order->google_maps_url }}')" class="flex-1 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow transition active:scale-95">
                            <i class="fa-solid fa-truck-fast mr-1"></i> Start Route
                        </button>
                        <button type="button" onclick="handleMarkDelivered('{{ $assign->id }}', {{ (float)$order->due_amount }}, '{{ addslashes($order->user->name ?? 'Customer') }}', '{{ $order->order_number }}', '{{ $order->user->email ?? '' }}', '{{ $order->user->phone ?? '' }}')" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition active:scale-95">
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
    function handleStartRoute(assignId, currentStatus, mapUrl) {
        // 1. Immediately open Google Maps Navigation in new tab/app
        if (mapUrl) {
            const win = window.open(mapUrl, '_blank');
            if (!win || win.closed || typeof win.closed === 'undefined') {
                window.location.href = mapUrl;
                return;
            }
        }

        // 2. If status is not yet 'in_transit', update status to in_transit
        if (currentStatus !== 'in_transit') {
            const form = document.getElementById('delivery-form-' + assignId);
            if (form) {
                const formData = new FormData(form);
                formData.set('status', 'in_transit');

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(() => {
                    const badge = document.getElementById('assign-status-badge-' + assignId);
                    if (badge) {
                        badge.textContent = 'in_transit';
                        badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800';
                    }
                }).catch(err => {
                    console.error('Status update error:', err);
                });
            }
        }
    }

    function submitStatus(assignId, status) {
        document.getElementById('status-input-' + assignId).value = status;
        document.getElementById('delivery-form-' + assignId).submit();
    }

    function handleMarkDelivered(assignId, dueAmount, customerName, orderNumber, customerEmail, customerPhone) {
        // If order has no due amount (already paid online or ₹0)
        if (dueAmount <= 0) {
            Swal.fire({
                title: 'Mark as Delivered?',
                text: 'Confirm delivery for Order #' + orderNumber + ' (' + customerName + ')?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa-solid fa-circle-check mr-1"></i> Yes, Delivered!',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold px-4 py-2 text-sm',
                    cancelButton: 'rounded-xl font-bold px-4 py-2 text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('collected-cash-' + assignId).value = 0;
                    document.getElementById('payment-method-' + assignId).value = 'cash_offline';
                    submitStatus(assignId, 'delivered');
                }
            });
            return;
        }

        // For Cash On Delivery / Due amount orders: Provide Cash OR Razorpay UPI
        Swal.fire({
            title: '<div class="text-sm font-black text-slate-800">Payment & Delivery Confirmation</div>',
            html: `
                <div class="text-left space-y-2.5">
                    <div class="px-3 py-2 bg-amber-50 rounded-xl border border-amber-200">
                        <div class="flex justify-between items-center text-[11px] text-amber-900 font-medium">
                            <span>Order #${orderNumber}</span>
                            <span class="font-bold text-slate-800">${customerName}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-black text-amber-950 mt-0.5">
                            <span>Order Total / Due:</span>
                            <span class="text-sm text-rose-600 font-black">₹${dueAmount.toFixed(2)}</span>
                        </div>
                    </div>

                    <!-- Payment Mode Selection Tabs -->
                    <div class="flex items-center space-x-1.5 p-1 bg-slate-100 rounded-xl text-xs font-bold">
                        <button type="button" id="tab-cash-btn" onclick="selectPaymentTab('cash')" class="flex-1 py-1.5 rounded-lg bg-white text-slate-900 shadow-xs text-center transition flex items-center justify-center space-x-1">
                            <i class="fa-solid fa-money-bill-wave text-emerald-600 text-xs"></i>
                            <span>Cash on Delivery</span>
                        </button>
                        <button type="button" id="tab-razorpay-btn" onclick="selectPaymentTab('razorpay')" class="flex-1 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 text-center transition flex items-center justify-center space-x-1">
                            <i class="fa-solid fa-qrcode text-indigo-600 text-xs"></i>
                            <span>Razorpay / UPI</span>
                        </button>
                    </div>

                    <!-- Cash Pane -->
                    <div id="pane-cash" class="space-y-1.5">
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">Cash Received from Customer (₹)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 font-black text-slate-400 text-sm">₹</span>
                                <input id="swal-input-cash" type="number" step="0.01" value="${dueAmount.toFixed(2)}" class="w-full pl-7 pr-3 py-2 bg-white border border-slate-300 focus:border-emerald-500 rounded-lg text-sm font-black text-slate-900 outline-none">
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 italic">Collect physical currency and confirm with customer upon handover.</p>
                    </div>

                    <!-- Razorpay UPI Pane -->
                    <div id="pane-razorpay" class="hidden space-y-1.5">
                        <div class="bg-indigo-50/70 p-3 rounded-xl border border-indigo-100 text-center space-y-2">
                            <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center mx-auto shadow-sm">
                                <i class="fa-solid fa-qrcode text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-indigo-950">Pay via UPI / QR / Card</h4>
                                <p class="text-[11px] text-indigo-800">Customer can scan QR code with GPay, PhonePe, Paytm, or BHIM.</p>
                            </div>
                            <button type="button" id="btn-launch-driver-rzp" onclick="launchDriverRazorpay('${assignId}', ${dueAmount}, '${orderNumber}', '${customerName}', '${customerEmail}', '${customerPhone}')" class="w-full py-2.5 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl shadow-md transition flex items-center justify-center space-x-1.5 active:scale-95">
                                <i class="fa-solid fa-bolt text-xs"></i>
                                <span>Open Razorpay Doorstep Payment (₹${dueAmount.toFixed(2)})</span>
                            </button>
                        </div>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: '<i class="fa-solid fa-circle-check mr-1"></i> Collect Cash & Mark Delivered',
            denyButtonText: '<i class="fa-solid fa-clock mr-1"></i> Mark Delivered (Unpaid Due)',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-2xl !max-w-[340px] !w-[92%] !p-3',
                confirmButton: 'bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white',
                denyButton: 'bg-amber-500 hover:bg-amber-600 active:scale-[0.98] text-white',
                cancelButton: 'bg-slate-200 hover:bg-slate-300 active:scale-[0.98] text-slate-700'
            },
            preConfirm: () => {
                const isRazorpayActive = $('#pane-razorpay').is(':visible');
                if (isRazorpayActive) {
                    Swal.showValidationMessage('Please click the "Open Razorpay Doorstep Payment" button above to complete online collection.');
                    return false;
                }
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
                document.getElementById('payment-method-' + assignId).value = 'cash_offline';
                submitStatus(assignId, 'delivered');
            } else if (result.isDenied) {
                // Not collected - mark as pending due with customer confirmation
                Swal.fire({
                    title: 'Customer Confirmation',
                    text: 'Confirm that Order #' + orderNumber + ' is handed over to ' + customerName + ' with payment marked as pending due?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Confirm Handover',
                    cancelButtonText: 'Cancel'
                }).then((subResult) => {
                    if (subResult.isConfirmed) {
                        document.getElementById('collected-cash-' + assignId).value = 0;
                        document.getElementById('payment-method-' + assignId).value = 'cash_offline';
                        submitStatus(assignId, 'delivered');
                    }
                });
            }
        });
    }

    function selectPaymentTab(type) {
        if (type === 'cash') {
            $('#tab-cash-btn').addClass('bg-white text-slate-900 shadow-xs').removeClass('text-slate-500');
            $('#tab-razorpay-btn').removeClass('bg-white text-slate-900 shadow-xs').addClass('text-slate-500');
            $('#pane-cash').removeClass('hidden');
            $('#pane-razorpay').addClass('hidden');
            $('.swal2-confirm').show();
        } else {
            $('#tab-razorpay-btn').addClass('bg-white text-slate-900 shadow-xs').removeClass('text-slate-500');
            $('#tab-cash-btn').removeClass('bg-white text-slate-900 shadow-xs').addClass('text-slate-500');
            $('#pane-razorpay').removeClass('hidden');
            $('#pane-cash').addClass('hidden');
            $('.swal2-confirm').hide();
        }
    }

    function launchDriverRazorpay(assignId, dueAmount, orderNumber, customerName, customerEmail, customerPhone) {
        const btn = $('#btn-launch-driver-rzp');
        btn.attr('disabled', 'disabled').html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Generating QR / Payment...');

        $.ajax({
            url: `/driver/delivery/${assignId}/razorpay-order`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                amount: dueAmount
            },
            success: function(orderData) {
                btn.removeAttr('disabled').html('<i class="fa-solid fa-bolt text-xs"></i> <span>Open Razorpay Doorstep Payment (₹' + dueAmount.toFixed(2) + ')</span>');

                const options = {
                    key: "{{ env('RAZORPAY_KEY_ID', 'rzp_test_waterdelivery101') }}",
                    amount: orderData.amount,
                    currency: orderData.currency || "INR",
                    name: "{{ $appSettings['business_name'] ?? 'AquaFlow Water Delivery' }}",
                    description: `Doorstep Collection for Order #${orderNumber}`,
                    image: "/icons/logo.svg",
                    order_id: orderData.id,
                    prefill: {
                        name: customerName,
                        email: customerEmail || "customer@aquaflowdelivery.test",
                        contact: customerPhone || ""
                    },
                    theme: {
                        color: "#059669"
                    },
                    handler: function(response) {
                        Swal.close();
                        Swal.fire({
                            title: 'Payment Successful!',
                            text: 'Processing delivery confirmation...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        document.getElementById('collected-cash-' + assignId).value = dueAmount;
                        document.getElementById('payment-method-' + assignId).value = 'razorpay';
                        document.getElementById('rzp-payment-id-' + assignId).value = response.razorpay_payment_id;
                        document.getElementById('rzp-order-id-' + assignId).value = response.razorpay_order_id;
                        document.getElementById('rzp-signature-' + assignId).value = response.razorpay_signature;

                        submitStatus(assignId, 'delivered');
                    },
                    modal: {
                        ondismiss: function() {
                            btn.removeAttr('disabled').html('<i class="fa-solid fa-bolt text-xs"></i> <span>Open Razorpay Doorstep Payment (₹' + dueAmount.toFixed(2) + ')</span>');
                        }
                    }
                };

                const rzp = new Razorpay(options);
                rzp.open();
            },
            error: function(xhr) {
                btn.removeAttr('disabled').html('<i class="fa-solid fa-bolt text-xs"></i> <span>Retry Payment</span>');
                Swal.showValidationMessage(xhr.responseJSON?.error || 'Failed to initialize Razorpay.');
            }
        });
    }
    </script>
</body>
</html>
