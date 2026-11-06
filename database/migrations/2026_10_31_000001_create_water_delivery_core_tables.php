<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Delivery Slots
        Schema::create('delivery_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Morning Slot (7:00 AM - 10:00 AM)"
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('max_daily_capacity')->default(50);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Customer Profiles
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('preferred_delivery_slot_id')->nullable()->constrained('delivery_slots')->nullOnDelete();
            $table->decimal('total_due_balance', 10, 2)->default(0.00);
            $table->unsignedInteger('total_empty_cans_held')->default(0);
            $table->boolean('onboarding_completed')->default(false);
            $table->timestamps();
        });

        // 3. Saved Addresses
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Home'); // Home, Office, Other
            $table->string('house_or_building');
            $table->string('door_number');
            $table->string('street');
            $table->string('area_locality');
            $table->string('city')->default('Chennai');
            $table->string('pincode', 10);
            $table->text('delivery_instructions')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 4. Product Categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Products (Dynamic Water Cans & Sizes)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name'); // e.g. "20-Litre Pure Drinking Water Can"
            $table->string('slug')->unique();
            $table->string('volume'); // e.g. "20", "10", "5", "2"
            $table->string('unit')->default('Litre'); // Litre, Bottle
            $table->text('description')->nullable();
            $table->decimal('selling_price', 10, 2);
            $table->unsignedInteger('stock_quantity')->default(100);
            $table->unsignedInteger('min_order_quantity')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_delivery_eligible')->default(true);
            $table->string('image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // 6. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->json('address_snapshot'); // Complete historical snapshot
            $table->date('expected_delivery_date')->index();
            $table->foreignId('delivery_slot_id')->nullable()->constrained('delivery_slots')->nullOnDelete();
            $table->enum('order_status', [
                'placed',
                'confirmed',
                'preparing',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->default('placed')->index();
            $table->enum('payment_method', ['razorpay', 'cod', 'pay_later'])->default('cod');
            $table->enum('payment_status', [
                'pending',
                'partially_paid',
                'paid',
                'failed',
                'refunded'
            ])->default('pending')->index();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->decimal('due_amount', 10, 2)->default(0.00);
            $table->text('delivery_instructions')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        // 7. Order Items (Historical Snapshot)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('volume_snapshot');
            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });

        // 8. Order Status Histories
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 9. Payment Transactions
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_reference')->unique()->index();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['razorpay', 'cod', 'cash_offline', 'upi_manual'])->default('razorpay');
            $table->string('gateway_payment_id')->nullable()->index();
            $table->string('gateway_order_id')->nullable()->index();
            $table->string('gateway_signature')->nullable();
            $table->enum('status', ['initiated', 'success', 'failed', 'refunded'])->default('initiated');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 10. Customer Payment Ledger (Double-entry transaction-level ledger)
        Schema::create('customer_payment_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('payment_transaction_id')->nullable()->constrained('payment_transactions')->nullOnDelete();
            $table->enum('entry_type', ['debit', 'credit']); // debit increases due, credit decreases due
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_after', 10, 2);
            $table->string('description');
            $table->timestamps();
        });

        // 11. Delivery Assignments
        Schema::create('delivery_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('assigned_at');
            $table->enum('status', ['assigned', 'in_transit', 'delivered', 'failed'])->default('assigned');
            $table->text('failure_reason')->nullable();
            $table->decimal('collected_cash', 10, 2)->default(0.00);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 12. Application Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('delivery_assignments');
        Schema::dropIfExists('customer_payment_ledger');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('customer_profiles');
        Schema::dropIfExists('delivery_slots');
    }
};
