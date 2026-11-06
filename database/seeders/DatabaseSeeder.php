<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Initial Delivery Slots
        $slots = [
            ['name' => 'Morning Express (06:30 AM - 09:30 AM)', 'start_time' => '06:30:00', 'end_time' => '09:30:00', 'max_daily_capacity' => 60, 'is_active' => true],
            ['name' => 'Midday Delivery (10:00 AM - 01:00 PM)', 'start_time' => '10:00:00', 'end_time' => '13:00:00', 'max_daily_capacity' => 50, 'is_active' => true],
            ['name' => 'Afternoon Slot (02:00 PM - 05:00 PM)', 'start_time' => '14:00:00', 'end_time' => '17:00:00', 'max_daily_capacity' => 50, 'is_active' => true],
            ['name' => 'Evening Prime (05:30 PM - 08:30 PM)', 'start_time' => '17:30:00', 'end_time' => '20:30:00', 'max_daily_capacity' => 70, 'is_active' => true],
        ];

        foreach ($slots as $slot) {
            DB::table('delivery_slots')->insert(array_merge($slot, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 2. Initial Categories
        $categories = [
            ['name' => 'Commercial Water Cans', 'slug' => 'commercial-water-cans', 'description' => 'Heavy duty 20L & 10L reusable purified mineral water cans.'],
            ['name' => 'Compact & Home Dispenser Cans', 'slug' => 'home-dispenser-cans', 'description' => '5L and mini portable water dispensers.'],
            ['name' => 'Packaged Bottled Water', 'slug' => 'bottled-water', 'description' => '1L and 2L portable mineral water packs.'],
        ];

        foreach ($categories as $cat) {
            DB::table('product_categories')->insert(array_merge($cat, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]));
        }

        // 3. Dynamic Products
        $products = [
            [
                'category_id' => 1,
                'name' => '20-Litre Pure Mineral Water Can',
                'slug' => '20-litre-pure-mineral-water-can',
                'volume' => '20',
                'unit' => 'Litre',
                'description' => '10-stage RO + UV + Ozonated pure mineral drinking water can. Sanitized refillable container.',
                'selling_price' => 50.00,
                'stock_quantity' => 250,
                'min_order_quantity' => 1,
                'is_active' => true,
                'is_delivery_eligible' => true,
                'image' => 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => 1,
                'name' => '10-Litre Convenient Easy-Pour Can',
                'slug' => '10-litre-convenient-easy-pour-can',
                'volume' => '10',
                'unit' => 'Litre',
                'description' => 'Ergonomic easy-lift 10-litre drinking water can equipped with premium dispensing tap.',
                'selling_price' => 35.00,
                'stock_quantity' => 120,
                'min_order_quantity' => 1,
                'is_active' => true,
                'is_delivery_eligible' => true,
                'image' => 'https://images.unsplash.com/photo-1527661591475-527312dd65f5?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => 2,
                'name' => '5-Litre Tabletop Mineral Water Jar',
                'slug' => '5-litre-tabletop-mineral-water-jar',
                'volume' => '5',
                'unit' => 'Litre',
                'description' => 'Compact 5L jar with sturdy top handle and leak-proof tap, ideal for small kitchen counters and desks.',
                'selling_price' => 25.00,
                'stock_quantity' => 80,
                'min_order_quantity' => 2,
                'is_active' => true,
                'is_delivery_eligible' => true,
                'image' => 'https://images.unsplash.com/photo-1564419320461-6870880221ad?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => 3,
                'name' => '2-Litre Packaged Mineral Water (Pack of 6)',
                'slug' => '2-litre-packaged-mineral-water-pack-of-6',
                'volume' => '2',
                'unit' => 'Litre x 6',
                'description' => 'Pack of six 2-litre sealed mineral water bottles. Excellent for traveling and events.',
                'selling_price' => 120.00,
                'stock_quantity' => 90,
                'min_order_quantity' => 1,
                'is_active' => true,
                'is_delivery_eligible' => true,
                'image' => 'https://images.unsplash.com/photo-1559839914-ba2ac5cb3a59?w=500&auto=format&fit=crop&q=80',
            ],
        ];

        foreach ($products as $prod) {
            DB::table('products')->insert(array_merge($prod, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 4. Default Settings
        $settings = [
            ['key' => 'business_name', 'value' => 'AquaFlow Water Delivery', 'group' => 'general'],
            ['key' => 'business_tagline', 'value' => 'Pure Hydration Delivered To Your Doorstep', 'group' => 'general'],
            ['key' => 'business_phone', 'value' => '+91 98765 43210', 'group' => 'general'],
            ['key' => 'business_email', 'value' => 'support@aquaflowdelivery.test', 'group' => 'general'],
            ['key' => 'currency_symbol', 'value' => '₹', 'group' => 'general'],
            ['key' => 'delivery_fee', 'value' => '0', 'group' => 'delivery'],
            ['key' => 'min_order_amount', 'value' => '50', 'group' => 'delivery'],
            ['key' => 'min_advance_booking_hours', 'value' => '2', 'group' => 'delivery'],
            ['key' => 'razorpay_key_id', 'value' => 'rzp_test_waterdelivery101', 'group' => 'payment'],
            ['key' => 'razorpay_key_secret', 'value' => 'demoSecretKeyAqua123456', 'group' => 'payment'],
            ['key' => 'admin_notification_email', 'value' => 'admin@aquaflowdelivery.test', 'group' => 'notifications'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert(array_merge($setting, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 5. Seed Users: Admin, Driver, and Demo Customer
        // Admin
        $admin = User::create([
            'name' => 'AquaFlow Admin',
            'email' => 'admin@aquaflow.com',
            'phone' => '9876543210',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Driver
        $driver = User::create([
            'name' => 'Ramesh Kumar (Delivery Captain)',
            'email' => 'driver@aquaflow.com',
            'phone' => '9876543211',
            'password' => Hash::make('driver123'),
            'role' => 'driver',
            'status' => 'active',
        ]);

        // Customer
        $customer = User::create([
            'name' => 'Suresh Sharma',
            'email' => 'suresh@example.com',
            'phone' => '9876543212',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        // Customer Profile
        DB::table('customer_profiles')->insert([
            'user_id' => $customer->id,
            'preferred_delivery_slot_id' => 1,
            'total_due_balance' => 0.00,
            'total_empty_cans_held' => 3,
            'onboarding_completed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Customer Default Address
        $addressId = DB::table('addresses')->insertGetId([
            'user_id' => $customer->id,
            'label' => 'Home',
            'house_or_building' => 'Palm Grove Apartments',
            'door_number' => 'Flat 402, 4th Floor',
            'street' => '12th Main Road, Anna Nagar West',
            'area_locality' => 'Anna Nagar',
            'city' => 'Chennai',
            'pincode' => '600040',
            'delivery_instructions' => 'Call security gate on arrival. Lift available directly to 4th floor.',
            'is_default' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Seed a sample Completed Order, sample Out For Delivery Order, and sample Pay Later Due Order
        // Sample Pay Later Due Order
        $tomorrow = Carbon::now()->addDay()->toDateString();
        $order1 = DB::table('orders')->insertGetId([
            'order_number' => 'AQF-' . strtoupper(bin2hex(random_bytes(3))),
            'user_id' => $customer->id,
            'address_id' => $addressId,
            'address_snapshot' => json_encode([
                'label' => 'Home',
                'house_or_building' => 'Palm Grove Apartments',
                'door_number' => 'Flat 402, 4th Floor',
                'street' => '12th Main Road, Anna Nagar West',
                'area_locality' => 'Anna Nagar',
                'city' => 'Chennai',
                'pincode' => '600040',
                'delivery_instructions' => 'Call security gate on arrival.',
            ]),
            'expected_delivery_date' => $tomorrow,
            'delivery_slot_id' => 1,
            'order_status' => 'confirmed',
            'payment_method' => 'pay_later',
            'payment_status' => 'pending',
            'subtotal' => 100.00,
            'delivery_fee' => 0.00,
            'tax_amount' => 0.00,
            'total_amount' => 100.00,
            'paid_amount' => 0.00,
            'due_amount' => 100.00,
            'delivery_instructions' => 'Please bring 2 fresh 20L cans.',
            'created_at' => now()->subHours(2),
            'updated_at' => now(),
        ]);

        DB::table('order_items')->insert([
            'order_id' => $order1,
            'product_id' => 1,
            'product_name' => '20-Litre Pure Mineral Water Can',
            'volume_snapshot' => '20 Litre',
            'unit_price' => 50.00,
            'quantity' => 2,
            'total_price' => 100.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update customer profile due balance and record in customer_payment_ledger
        DB::table('customer_profiles')->where('user_id', $customer->id)->update([
            'total_due_balance' => 100.00,
        ]);

        DB::table('customer_payment_ledger')->insert([
            'user_id' => $customer->id,
            'order_id' => $order1,
            'entry_type' => 'debit',
            'amount' => 100.00,
            'balance_after' => 100.00,
            'description' => 'Order placed with Pay Later / Outstanding Due',
            'created_at' => now()->subHours(2),
            'updated_at' => now(),
        ]);

        DB::table('order_status_histories')->insert([
            'order_id' => $order1,
            'status' => 'confirmed',
            'notes' => 'Order confirmed and scheduled for tomorrow morning.',
            'updated_by' => $admin->id,
            'created_at' => now()->subHours(1),
            'updated_at' => now(),
        ]);
    }
}
