<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order Notification</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f0f9ff; margin: 0; padding: 24px; color: #0f172a;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e0f2fe;">
        <div style="background: linear-gradient(135deg, #0284c7, #0369a1); padding: 28px; text-align: center; color: white;">
            <h1 style="margin: 0; font-size: 22px; font-weight: 700;">💧 AquaFlow Delivery Alert</h1>
            <p style="margin: 6px 0 0 0; opacity: 0.9; font-size: 14px;">A new water can order has been placed successfully!</p>
        </div>

        <div style="padding: 28px;">
            <div style="display: flex; justify-content: space-between; border-bottom: 2px dashed #e2e8f0; padding-bottom: 16px; margin-bottom: 20px;">
                <div>
                    <span style="font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 600;">Order ID</span>
                    <h3 style="margin: 2px 0 0 0; color: #0284c7; font-size: 18px;">#{{ $order->order_number }}</h3>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 600;">Delivery Date</span>
                    <h4 style="margin: 2px 0 0 0; font-size: 15px;">{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}</h4>
                </div>
            </div>

            <!-- Customer Details -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <h4 style="margin: 0 0 8px 0; font-size: 14px; color: #334155; text-transform: uppercase; letter-spacing: 0.5px;">Customer & Delivery Details</h4>
                <p style="margin: 4px 0; font-size: 14px;"><strong>Customer:</strong> {{ $order->user->name }}</p>
                <p style="margin: 4px 0; font-size: 14px;"><strong>Phone:</strong> {{ $order->user->phone }}</p>
                <p style="margin: 4px 0; font-size: 14px;"><strong>Time Slot:</strong> {{ $order->slot?->name ?? 'Standard Schedule' }}</p>
                <p style="margin: 4px 0; font-size: 14px;"><strong>Address:</strong> {{ $order->address_snapshot['door_number'] ?? '' }}, {{ $order->address_snapshot['house_or_building'] ?? '' }}, {{ $order->address_snapshot['street'] ?? '' }}, {{ $order->address_snapshot['area_locality'] ?? '' }}, {{ $order->address_snapshot['city'] ?? '' }} - {{ $order->address_snapshot['pincode'] ?? '' }}</p>
                @if($order->delivery_instructions)
                    <p style="margin: 4px 0; font-size: 13px; color: #b45309;"><strong>Instructions:</strong> {{ $order->delivery_instructions }}</p>
                @endif
            </div>

            <!-- Order Items -->
            <h4 style="margin: 0 0 12px 0; font-size: 14px; color: #334155; text-transform: uppercase;">Items Ordered</h4>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="border-bottom: 1px solid #cbd5e1; text-align: left; font-size: 12px; color: #64748b;">
                        <th style="padding: 8px 0;">Product</th>
                        <th style="padding: 8px; text-align: center;">Qty</th>
                        <th style="padding: 8px 0; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr style="border-bottom: 1px solid #f1f5f9; font-size: 14px;">
                        <td style="padding: 10px 0;">{{ $item->product_name }}</td>
                        <td style="padding: 10px; text-align: center;">{{ $item->quantity }}</td>
                        <td style="padding: 10px 0; text-align: right; font-weight: 600;">₹{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary -->
            <div style="background: #e0f2fe; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 14px;">
                    <span>Payment Method:</span>
                    <strong style="text-transform: uppercase;">{{ str_replace('_', ' ', $order->payment_method) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 14px;">
                    <span>Payment Status:</span>
                    <strong style="color: {{ $order->payment_status === 'paid' ? '#16a34a' : '#d97706' }}; text-transform: uppercase;">{{ $order->payment_status }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 10px; padding-top: 10px; border-top: 1px solid #bae6fd; font-size: 17px; font-weight: 700; color: #0369a1;">
                    <span>Total Amount:</span>
                    <span>₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <p style="text-align: center; font-size: 12px; color: #94a3b8; margin: 0;">This is an automated notification from your AquaFlow Management System.</p>
        </div>
    </div>
</body>
</html>
