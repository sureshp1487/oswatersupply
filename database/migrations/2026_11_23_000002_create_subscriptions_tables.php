<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Subscription Plans
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('frequency', ['daily', 'alternate_days', 'weekly']);
            $table->unsignedInteger('deliveries_count');
            $table->decimal('discount_percentage', 5, 2)->default(0.00);
            $table->string('badge')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Customer Subscriptions
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_number')->unique()->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('quantity_per_delivery')->default(1);
            $table->foreignId('delivery_slot_id')->constrained('delivery_slots')->cascadeOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->json('address_snapshot');
            $table->string('frequency'); // 'daily', 'alternate_days', 'weekly'
            $table->string('preferred_weekday')->nullable(); // 'monday', 'tuesday', etc. for weekly
            $table->date('start_date');
            $table->date('end_date');
            $table->date('next_delivery_date')->nullable()->index();
            $table->unsignedInteger('total_deliveries');
            $table->unsignedInteger('completed_deliveries')->default(0);
            $table->decimal('price_per_can', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->enum('status', ['active', 'paused', 'cancelled', 'completed'])->default('active')->index();
            $table->enum('payment_method', ['cod', 'pay_later', 'razorpay'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->text('special_instructions')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
